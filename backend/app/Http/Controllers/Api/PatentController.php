<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Patent;
use App\Helpers\Services\EPValidation as EPValidationService;
use App\Helpers\Services\PCTEntry;
use App\Models\EPValidation;
use App\Models\Error as ModelsError;
use App\Models\OfficialFee;
use App\Models\Query;
use App\Models\Service;
use App\Models\ServiceFee;
use App\Models\ServiceOfficialFee;
use App\Models\ServiceServiceFee;
use App\Models\Token;
use App\Models\TranslationFee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PatentController extends BaseController
{

    public function query($query)
    {
        $patent = new Patent;
        $ep = new EPValidationService;
        $number = $query;
        $number = strtoupper($number);
        $urgent = false;
        if (substr($number, -3) == 'URG') {
            $number = str_replace('URG', '', $number);
            $urgent = true;
        }
        // if number last 2 charf != B1
        if (substr($number, -2) != 'B1') {
            $number = $number . 'B1';
        }
        // if number first 2 char not contain EP
        if (substr($number, 0, 2) != 'EP') {
            $number = 'EP' . $number;
        }

        if (!is_string($patent->getBearerToken())) {
            return $patent->getBearerToken();
        }
        // Test numbers
        require(app_path() . '/Helpers/TestNumbers.php');
        if ($response['isTest']) {
            return $response['data'];
        }
        try {
            // Fetch cache data if this query has been cached before.
            $cache = Cache::get($number . "ep-validation");
            if ($cache) return $cache;

            $fullCycle = $ep->getFullCycleData($number); // OKEY
            $description = $ep->getDescriptionData($number); // OKEY
            $claims = $ep->getClaimsData($number); // OKEY

            // Check if there is an error in the returned response.
            if ($fullCycle['status'] == false || $description['status'] == false || $claims['status'] == false) {
                $response['status'] = false;
                $response["code"] = 404;
                $response["message"] = "Data not found.";
                $response["parts"]["fullCycle"] = $fullCycle;
                $response["parts"]["description"] = $description;
                $response["parts"]["claims"] = $claims;
                return $response;
            }
            // Create an empty array for the response and specify the patent number.
            $response = array();
            $response["number"] = $number;

            //Make description and claims data into a single string.
            $fullText = "";
            $fullText .= $claims["data"];
            $fullText .= $description["data"];

            $publicationDate = $fullCycle["response"]["bibliographicData"]["publicationDate"];
            $diffInMonths = Carbon::now()->diffInMonths($publicationDate);
            $diffInDays = Carbon::now()->diffInDays($publicationDate);

            $expirationDate = Carbon::parse($publicationDate)->addMonths(3);
            $quoteChangeDate = Carbon::parse($publicationDate)->addMonths(2);
            $expirationStatus = false;
            $quoteChangeStatus = false;

            $numberOfPages = round(str_word_count($fullText) / 833);
            /**
             * 15 sayfaya kadar 7 gün,
             * 15-30 sayfa 9 gün
             * 30-45 sayfa 10 gün
             * 45-60 sayfa 11 gün
             * 60-90 sayfa 12 gu
             * 90-120 sayfa 13 gün
             * 120-150 sayfa 14 gün
             * 150+ max 20 Gün
             */
            $translationDays = 0;
            switch ($numberOfPages) {
                case $numberOfPages <= 15:
                    $translationDays = 7;
                    break;
                case $numberOfPages <= 30:
                    $translationDays = 9;
                    break;
                case $numberOfPages <= 45:
                    $translationDays = 10;
                    break;
                case $numberOfPages <= 60:
                    $translationDays = 11;
                    break;
                case $numberOfPages <= 90:
                    $translationDays = 12;
                    break;
                case $numberOfPages <= 120:
                    $translationDays = 13;
                    break;
                case $numberOfPages <= 150:
                    $translationDays = 14;
                    break;
                default:
                    $translationDays = 20;
                    break;
            }

            /**
             * Case 1# Expired : $diffInMonths >= 3
             */
            if ($diffInMonths >= 3) {
                $status = "expired";
            }
            /**
             * Case 2# Very Close To Expire : $diffInDays <= 3
             */
            else if ($diffInDays <= 3) {
                $status = $urgent ? "close-to-expire" : "very-close-to-expire";
                $expirationStatus = true;
                // Warning check
                if ($diffInDays + 7 <= 90) {
                    $warning = true;
                    $expirationDate = Carbon::parse($publicationDate)->addMonths(3)->format('d-m-Y');
                }
            }
            /**
             * Case 3# Close To Expire : $translationDays <= $diffInDays
             */
            else if ($translationDays <= $diffInDays) {
                $status = "close-to-expire";

                // Warning check
                if ($translationDays + 7 <= $diffInDays) {
                    $expirationStatus = true;
                }
            }
            /**
             * Case 4# Valid
             */
            else {
                $status = "valid";

                if ($diffInDays + 7 <= 90) {
                    $quoteChangeStatus = true;
                }
            }

            $warning = [
                'expiration' => [
                    'status' => $expirationStatus ?? false,
                    'expirationDate' => $expirationStatus ? $expirationDate->format('d-m-Y') : null,
                ],
                'quoteChange' => [
                    'status' => $quoteChangeStatus ?? false,
                    'quoteChangeDate' => $quoteChangeStatus ? $quoteChangeDate->format('d-m-Y') : null,
                ],
            ];

            // Add the full text to the response.
            $response["response"]["bibliographicData"] = $fullCycle["response"]["bibliographicData"];
            $response["response"]["bibliographicData"]["status"] = $status;
            $response["response"]["bibliographicData"]["publicationDate"] = Carbon::parse($publicationDate)->format('d-m-Y');
            $response["response"]["warning"] = $warning;
            // Calculate the service fee.
            $response['fee'] = $ep->getServiceFee($status, $fullText, $urgent);
            if ($response['fee']["status"] == false) {
                return $response['fee'];
            };

            // Cache the response for 2 hours.
            Cache::put($number . "ep-validation", $response, now()->addHours(2));
        } catch (\Throwable $th) {
            $error = ModelsError::create([
                'number' => $number,
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
                'trace' => $th->getTraceAsString(),
            ]);

            $response = [
                'status' => false,
                'code' => 410,
                'message' => "Parse Error. See error err_no_$error->id for more detailed information."
            ];
        }

        return response()->json($response);
    }

    public function queryPCT($query)
    {
        $patent = new Patent;
        $pct = new PCTEntry;
        $number = $query;
        $number = strtoupper($number);
        $urgent = false;

        // Fetch cache data if this query has been cached before.
        $cache = Cache::get($number . "pct-entry");
        if ($cache) {
            return response()->json($cache["fullCycle"]);
        }

        $fullCycle = $pct->getFullCycleData($number);
        $description = $pct->getDescriptionData($number);
        $claims = $pct->getClaimsData($number);

        $data = [
            "fullCycle" => $fullCycle,
            "description" => $description,
            "claims" => $claims
        ];

        Cache::put($number . "pct-entry", $data, now()->addHours(2));
        return response()->json($data["fullCycle"]);
    }

    public function queryPCTFees(Request $request, $query)
    {
        $patent = new Patent;
        $pct = new PCTEntry;

        $number = $query;
        $number = strtoupper($number);

        // Fetch cache data if this query has been cached before.
        $cache = Cache::get($number . "pct-entry");
        if (!$cache) {
            $fullCycle = $pct->getFullCycleData($number); // OKEY
            $description = $pct->getDescriptionData($number); // OKEY
            $claims = $pct->getClaimsData($number); // OKEY
            $data = [
                "fullCycle" => $fullCycle,
                "description" => $description,
                "claims" => $claims
            ];
            Cache::put($number . "pct-entry", $data, now()->addHours(2));
            $cache = $data;
        }
        $response = $cache;
        $fullCycle = $response["fullCycle"];
        $fullText = "";
        $fullText .= $response["claims"]["data"];
        $fullText .= $response["description"]["data"];

        $priorities = $fullCycle["priorities"] ?? [];
        $newPriorities = $request->priorities ?? [];

        if ($priorities == [] && $request->priorities == []) {
            $baseDate = Carbon::parse($response["bibliographicData"]["applicationDate"]);
        } else {
            $priorities = array_merge($priorities, $newPriorities);
            $priorities = array_map("unserialize", array_unique(array_map("serialize", $priorities)));
            // sort the priorities by date
            usort($priorities, function ($a, $b) {
                return strtotime($a['date']) - strtotime($b['date']);
            });

            $baseDate = Carbon::parse($priorities[0]["date"]);
        }
        $diffInMonths = Carbon::now()->diffInMonths($baseDate);
        $diffInDays = Carbon::now()->diffInDays($baseDate);
        // add 30 months to the base date
        $validityDate = $baseDate->addMonths(30);

        $expirationDate = $validityDate->addMonths(3)->subDays(3);
        $quoteChangeDate = $validityDate->subDays(7);

        $expirationStatus = false;
        $quoteChangeStatus = false;
        /**
         * Case 1# Expired : $baseDate > 33 months
         */
        if ($diffInMonths > 33) {
            $status = "expired";
        }
        /**
         * Case 2# Close To Expire : Bugünkü tarih $validityDate'den 7 gün önce ise
         */
        else if (Carbon::now() >= $validityDate->subDays(7)) {
            $status = "close-to-expire";
            $expirationStatus = true;
        }
        /**
         * Case 3# Very Close To Expire : Bugünkü tarih $validityDate+3ay'dan 3 gün önce ise
         */
        else if (Carbon::now() >= $expirationDate) {
            $status = "very-close-to-expire";
        }
        /**
         * Case 4# Valid
         */
        else {
            $status = "valid";

            if (Carbon::now() >= $quoteChangeDate) {
                $quoteChangeStatus = true;
            }
        }


        $warning = [
            'expiration' => [
                'status' => $expirationStatus ?? false,
                'expirationDate' => $expirationStatus ? $expirationDate->format('d-m-Y') : null,
            ],
            'quoteChange' => [
                'status' => $quoteChangeStatus ?? false,
                'quoteChangeDate' => $quoteChangeStatus ? $quoteChangeDate->format('d-m-Y') : null,
            ],
        ];

        $fee = $pct->getServiceFee($status, $priorities, $fullText);
        $fee['warning'] = $warning;
        $fee['expirationDate'] = $expirationDate ?? null;
        return $fee;
    }

    public function UsdTry()
    {
        if (Cache::has('currency')) {
            $currency = Cache::get('currency');
        } else {
            $exchange = simplexml_load_file('http://www.tcmb.gov.tr/kurlar/today.xml');
            $currency = (string)$exchange->Currency[0]->BanknoteBuying;
            Cache::put('currency', $currency, now()->addDay());
        }
        return response()->json($currency);
    }

    public function EurTry()
    {
        if (Cache::has('currency')) {
            $currency = Cache::get('currency');
        } else {
            $exchange = simplexml_load_file('http://www.tcmb.gov.tr/kurlar/today.xml');
            $currency = (string)$exchange->Currency[3]->BanknoteBuying;
            Cache::put('currency', $currency, now()->addDay());
        }
        return response()->json($currency);
    }

    public function queryTR($query)
    {
        $response = Patent::TRRequest($query);
        return response()->json($response);
    }

    public function dataList($query)
    {
        $response = array();
        if ($query == "token") $response = Token::all();
        else if ($query == "official-fee") $response = OfficialFee::all();
        else if ($query == "service-fee") $response = ServiceFee::all();
        else if ($query == "service") $response = Service::all();
        else if ($query == "query") $response = Query::all();
        else $response = "data not found";
        $response = json_encode($response);
        return response()->json($response);
    }

    public function storeEpValidation(Request $request)
    {

        $service = new Service();
        $service->title = "EP Validation";
        $service->patent_number = $request->patentNumber;
        $service->save();

        $fee = new TranslationFee();
        $fee->service_id = $service->id;
        $fee->fee = $request->fee;
        $fee->total_word_count = $request->total_word_count;
        $fee->amount = $request->amount;
        $fee->tax = 0;
        $fee->currency = $request->currency;
        $fee->save();

        $ep = new EPValidation();
        $ep->service_id = $service->id;
        $ep->name = $request->name;
        $ep->email = $request->email;
        $ep->phone = $request->phone;
        $ep->group = $request->group;
        $ep->company_name = $request->company;
        $ep->tax_number = $request->taxNumber;
        $ep->country = $request->country;
        $ep->city = $request->city;
        $ep->state = $request->state;
        $ep->post_code = $request->postCode;
        $ep->relationship = $request->relationship;
        $ep->save();

        $serviceOfficialFee = new ServiceOfficialFee();
        $serviceOfficialFee->service_id = $service->id;
        $serviceOfficialFee->official_fee_id = $request->officialFeeId;
        $serviceOfficialFee->tax = 0;
        $serviceOfficialFee->save();

        $serviceServiceFee = new ServiceServiceFee();
        $serviceServiceFee->service_id = $service->id;
        $serviceServiceFee->service_fee_id = $request->serviceFeeId;
        $serviceServiceFee->tax = 0;
        $serviceServiceFee->save();

        return response()->json($request);
    }
}
