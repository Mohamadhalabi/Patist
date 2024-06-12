<?php
/*
* Fill in the CUSTOMER_SECRET and CUSTOMER_KEY fields in the .env file according to the data received from the API.
*/

namespace App\Helpers;

use App\Models\AnnuityFee;
use App\Models\Error;
use App\Models\OfficialFee;
use App\Models\ServiceFee;
use App\Models\TestNumber;
use App\Models\Token;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;


class Patent
{
    /**
     * Converts Turkish characters in the entered text to English
     *
     * @param  mixed $text
     * @return void
     */
    function replace_tr($text)
    {
        $text = trim($text);
        $search = array('Ç', 'ç', 'Ğ', 'ğ', 'ı', 'İ', 'Ö', 'ö', 'Ş', 'ş', 'Ü', 'ü', ' ');
        $replace = array('c', 'c', 'g', 'g', 'i', 'i', 'o', 'o', 's', 's', 'u', 'u', '-');
        $new_text = str_replace($search, $replace, $text);
        return $new_text;
    }

    /**
     * Camelizes the entered text
     *
     * @param  mixed $input
     * @return void
     */
    function camelize($input)
    {
        $arr = explode(" ", $input);
        $new_str = "";
        foreach ($arr as $key => $value) {
            if ($key === array_key_first($arr)) {
                $new_str .= strtolower($value);
            } else {
                $new_str .= ucfirst($value);
            }
        }
        return $this->replace_tr($new_str);
    }

    /**
     * patentAnnuityCondition
     *
     * @param  mixed $registrationDate
     * @param  mixed $patentType
     * @param  mixed $paymentDates
     * @return void
     */
    public static function patentAnnuityCondition($registrationDate, $patentType, $paymentDates)
    {
        if ($patentType == 'Patent') $validityYear = 20;
        else if ($patentType == 'Faydalı Model') $validityYear = 10;
        else return false;

        // Format registation date
        $registrationDate = Carbon::createFromFormat('Y-m-d', $registrationDate);
        if($paymentDates == null){
            $response['message'] = 'This patent can be renewed.';
            $response['status'] = true;
        }

        elseif ($paymentDates != null) {
            $lastPaymentYear = Carbon::createFromFormat('Y', end($paymentDates)['paymentDate'])->year;
            $lastPaymentYearWithMonthAndDay = Carbon::createFromFormat('Y-m-d', $lastPaymentYear . '-' . $registrationDate->month . '-' . $registrationDate->day);
            $response['lastPaymentYear'] = $lastPaymentYearWithMonthAndDay->addYear(1)->format('Y-m-d');
        }

        /**
         * Condition 1: Validity date check.
         * If the patent type is a 'Patent', it is valid for 20 years, if it is a 'Faydalı Model', it is valid for 10 years.
         */
        if ($registrationDate->year + $validityYear < Carbon::now()->year) {
            $response['message'] = 'The validity of the patent has expired.';
            $response['status'] = false;
        }

        /**
         * Condition 2: Payment dates control.
         * If no renewal has been made before and it has not been more than 2 years and 8 months since the patent application has been filed.
         */
        elseif ($paymentDates == null && Carbon::now()->diffInMonths($registrationDate) > 32) {
            $response['message'] = 'The patent has not been renewed before and cannot be renewed due overtime.';
            $response['status'] = false;
        }

        /**
         * Condition 3: Payment dates control.
         * If the payment has been made for this year, no new payment can be made.
         */
        elseif ($paymentDates != null && end($paymentDates)['paymentDate'] == Carbon::now()->year) {
            $response['message'] = 'The patent has been renewed for this year.';
            $response['status'] = false;
            $response['lateFee'] = false;
        }

        /**
         * Condition 4: Payment dates control.
         * If 1 year and 6 months have passed since the last payment, it cannot be renewed.
         */
        elseif ($paymentDates != null && Carbon::now()->diffInMonths($lastPaymentYearWithMonthAndDay) > 18) {
            $response['message'] = 'The patent cannot be renewed due overtime';
            $response['status'] = false;
        }

        /**
         * Condition 5: Payment dates control.
         * If 12 to 18 months have passed since the last patent renewal, the patent can be renewed by paying a late fee.
         */
        elseif ($paymentDates != null && Carbon::now()->diffInMonths($lastPaymentYearWithMonthAndDay) < 18 && Carbon::now()->diffInMonths($lastPaymentYearWithMonthAndDay) > 12) {
            $response['message'] = 'You can renew your patent by paying a late fee.';
            $response['status'] = true;
            $response['lateFee'] = true;
        }

        /**
         * Condition 6: Payment dates control.
         * If it has not been 12 months since the last patent renewal, the patent can be renewed without additional charge.
         */
        elseif ($paymentDates != null && Carbon::now()->diffInMonths($lastPaymentYearWithMonthAndDay) <= 12) {
            $response['message'] = 'This patent can be renewed.';
            $response['status'] = true;
        } else {
            $response['message'] = 'Something went wrong. Please contact.';
            $response['status'] = false;
        }

        return $response;
    }

    public static function getServiceFee($slug, $year = 2)
    {
        if ($slug == 'ep-validation') {
            $code = '1.0.1.45';
            $officialFee = OfficialFee::where('code', $code)->first();
            $annuity = AnnuityFee::where('official_fee_id', $officialFee->id)->where('year', date('Y'))->first();
            if ($annuity) {
                $fee['officialFee'] = $annuity;
            }
        } else if ($slug == 'pct-entry') {
            $codes = ['1.0.1.51', '1.0.1.41', '1.0.1.02', '1.0.1.23', '1.0.1.42'];
            $officialFees = OfficialFee::whereIn('code', $codes)->get();
            $additionalTimeFee = ServiceFee::where('slug', 'additional-time-fee')->first();
            $fee = [];
            $fee['additionalTimeFee'] = $additionalTimeFee;
            foreach ($officialFees as $officialFee) {
                $annuity = AnnuityFee::where('official_fee_id', $officialFee->id)->where('year', date('Y'))->first();
                if ($annuity) {
                    $officialFee->amount = $annuity->amount;
                    $officialFee->year = $annuity->year;
                    $officialFee->error = false;
                }
            }
            $fee['officialFees'] = $officialFees;
        } else if ($slug == 'patent-annuity') {
            $code = '1.0.1.' . 19 + $year;
            $officialFee = OfficialFee::where('code', $code)->first();
            $annuity = AnnuityFee::where('official_fee_id', $officialFee->id)->where('year', date('Y'))->first();
            if ($annuity) {
                $fee['officialFee'] = $annuity;
            }
        }

        $translateFee = ServiceFee::where('slug', 'translate-fee')->where('year', date("Y"))->first();
        if (!$annuity || !$officialFee || !$translateFee) {
            $response = [
                'status' => false,
                'code' => 411,
                'message' => 'Price information not found.'
            ];
            return $response;
        }

        $fee['currency']['eur'] = Currency::getCurrency();
        $fee['serviceFee'] = ServiceFee::where('slug', $slug)->where('year', date("Y"))->first();
        $fee['translation100keywordFee'] = $translateFee->amount;
        $fee['status'] = true;
        return $fee;
    }

    /**
     * Get Basic Auth Token
     *
     * @return void
     */
    function getBasicAuthToken()
    {
        $basicAuth = base64_encode(env('CUSTOMER_KEY') . ':' . env('CUSTOMER_SECRET'));
        return $basicAuth;
    }

    /**
     * Get Bearer Token
     * API Url : https://ops.epo.org/3.2/auth/accesstoken
     *
     * @return void
     */
    function getBearerToken()
    {
        $token = Token::latest()->first();
        if ($token === null || $token->created_at < Carbon::now()->subMinutes(15)) {
            $basicAuth = Patent::getBasicAuthToken();
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://ops.epo.org/3.2/auth/accesstoken');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Authorization: Basic ' . $basicAuth,
                'Content-Type: application/x-www-form-urlencoded'
            ));
            $output = curl_exec($ch);
            curl_close($ch);

            try {
                $bearerToken = json_decode($output)->access_token;
                Token::create(['token' => $bearerToken]);

                return $bearerToken;
            } catch (\Throwable $th) {
                $xml = simplexml_load_string($output);
                $xml = json_encode($xml);
                $xml = json_decode($xml, true);

                $response = [
                    'status' => false,
                    'message' => $xml['message'],
                    'code' => $xml['code']
                ];

                return $response;
            }
        } else {
            return $token->token;
        }
    }

    /**
     * Sends a patent number and optionally a request to the API.
     *
     * @param  mixed $patentNumber
     * @param  mixed $requestType
     * @return void
     */
    public static function apiResponse($patentNumber, $requestType)
    {
        $patent = new Patent;

        // Get the token to connect to the api.
        $bearerToken = $patent->getBearerToken();

        // Create cURL request.
        $ch = curl_init();

        // Set cURL options.
        // The $requestType variable can take the following values: 'full-cycle', 'description', 'claims'
        curl_setopt($ch, CURLOPT_URL, 'http://ops.epo.org/3.2/rest-services/published-data/publication/docdb/' . $patentNumber . '/' . $requestType . '.json');

        // Set the Bearer token in the header.
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $headers = [
            "Authorization: Bearer " . $bearerToken
        ];

        // Set the headers.
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // Execute the request.
        $server_output = curl_exec($ch);

        if (str_contains($server_output, 'CLIENT.NotFound') || str_contains($server_output, 'SERVER.EntityNotFound') || str_contains($server_output, 'CLIENT.WrongReferenceFormatting') || str_contains($server_output, 'CLIENT.InvalidCountryCode')) {
            $xml = simplexml_load_string($server_output);
            $xml = json_encode($xml);
            $xml = json_decode($xml, true);

            $response = [
                'status' => false,
                'message' => $xml['message'],
                'code' => $xml['code']
            ];

            return $response;
        }

        // Close the connection.
        curl_close($ch);


        // Decode the JSON response.
        $output = json_decode($server_output, true);

        // If there is an error, state it in the reply.

        return $output;
    }

    /**
     * Get full-cycle data
     *
     * @param  mixed $patentNumber
     * @return void
     */
    public static function getFullCycleData($patentNumber)
    {
        $patent = new Patent;

        // Get information of full-cycle(abstract) from API.
        $output = $patent->apiResponse($patentNumber, 'full-cycle');

        // Check if there is an error in the returned response.
        if (isset($output["status"])) {
            $output['part'] = 'full-cycle';
            return $output;
        }
        // Get the priorities, application date and publication date.
        if (array_is_list($output["ops:world-patent-data"]["exchange-documents"]["exchange-document"])) {
            $data = $output["ops:world-patent-data"]["exchange-documents"]["exchange-document"];
            foreach ($data as $key => $value) {
                if ($value['@kind'] == 'B1') {
                    $priorities = $value["bibliographic-data"]["priority-claims"]["priority-claim"];
                    $bibliographicData = $value["bibliographic-data"];
                }
            }
        } else {
            $priorities = $output["ops:world-patent-data"]["exchange-documents"]["exchange-document"]["bibliographic-data"]["priority-claims"]["priority-claim"];
            $bibliographicData = $output["ops:world-patent-data"]['exchange-documents']['exchange-document']["bibliographic-data"];
        }
        // Priorities
        $tmp = [];
        if (array_is_list($priorities)) {
            foreach ($priorities as $key => $priority) {
                $tmp[$key]['documentNumber'] = $priority['document-id'][0]['doc-number']['$'];
                $year = substr($priority['document-id'][0]['date']['$'], 0, 4);
                $month = substr($priority['document-id'][0]['date']['$'], 4, 2);
                $day = substr($priority['document-id'][0]['date']['$'], 6, 2);
                $tmp[$key]['date'] = $year . "/" . $month . "/" . $day;
            }
        } else {
            $priority = $priorities['document-id'];
            if (array_is_list($priority)) {
                $priority = $priority[0];
            }
            $tmp[0]['documentNumber'] = $priority['doc-number']['$'];
            $year = substr($priority['date']['$'], 0, 4);
            $month = substr($priority['date']['$'], 4, 2);
            $day = substr($priority['date']['$'], 6, 2);
            $tmp[0]['date'] = $year . "/" . $month . "/" . $day;
        }
        // Publication date.
        $filter = array_filter($bibliographicData["publication-reference"]["document-id"], function ($value) {
            return $value["@document-id-type"] == "epodoc";
        });
        $publicationDate = $filter[array_key_first($filter)]["date"]["$"];
        $publicationDate = date("Y-m-d", strtotime($publicationDate));
        $publicationDate = Carbon::createFromFormat('Y-m-d H:i:s',  $publicationDate . ' 00:00:00');
        $check = $publicationDate >= Carbon::now()->subMonth(3) ? "valid" : "expired";


        // Application date.
        $filter = array_filter($bibliographicData["application-reference"]["document-id"], function ($value) {
            return $value["@document-id-type"] == "epodoc";
        });
        $applicationDate = $filter[array_key_first($filter)]["date"]["$"];
        $applicationDate = date("Y-m-d", strtotime($applicationDate));
        $applicationDate = Carbon::createFromFormat('Y-m-d H:i:s',  $applicationDate . ' 00:00:00');
        $monthIsDifferent = Carbon::now()->diffInMonths($applicationDate);

        // Get the bibliographic data.
        if ($bibliographicData) {
            $documentInfo = array_filter($bibliographicData["publication-reference"]["document-id"], function ($value) {
                return $value["@document-id-type"] == "docdb";
            });

            $response["response"]["bibliographicData"]["publicationReference"] = [
                "country" => $documentInfo[0]["country"]["$"],
                "docNumber" => $documentInfo[0]["doc-number"]["$"],
                "kind" => $documentInfo[0]["kind"]["$"],
                "date" => Carbon::parse($documentInfo[0]["date"]["$"])->format('Y-m-d'),
            ];

            $applicantInfo = $bibliographicData["parties"]["applicants"]["applicant"];
            if (array_is_list($applicantInfo)) {
                $applicant = $applicantInfo[0]["applicant-name"]["name"]["$"];
            } else {
                $applicant = $applicantInfo["applicant-name"]["name"]["$"];
            }

            $inventorInfo = $bibliographicData["parties"]["inventors"]["inventor"];
            if (array_is_list($inventorInfo)) {

                $inventionInfo = $bibliographicData['invention-title'];
                if (array_is_list($inventionInfo)) {
                    $inventionTitle = array_filter($inventionInfo, function ($value) {
                        return $value["@lang"] == "en";
                    });
                    $inventionTitle = $inventionTitle[array_key_first($inventionTitle)]['$'];
                } else {
                    $inventionTitle = $inventionInfo['$'];
                }

                $inventor = $inventorInfo[0]["inventor-name"]["name"]["$"];
            }

            $response["response"]["bibliographicData"]["inventionTitle"] = $inventionTitle;
            $response["response"]["bibliographicData"]["applicant"] = $applicant;
            $response["response"]["bibliographicData"]["inventor"] = $inventor;
        }

        $response["priorities"] = $tmp;
        $response["response"]["bibliographicData"]["publicationDate"] = $publicationDate->format('Y-m-d');
        $response["response"]["bibliographicData"]["status"] = $check;
        $response["response"]["bibliographicData"]["applicationDate"] = $applicationDate->format('Y-m-d');
        $response["response"]["bibliographicData"]["diff"] = $monthIsDifferent;
        $response["error"] = false;
        $response["status"] = true;

        return $response;
    }

    /**
     * Get description data
     *
     * @param  mixed $patentNumber
     * @return void
     */
    public static function getDescriptionData($patentNumber)
    {
        $patent = new Patent;

        // Get information of description from API.
        $output = $patent->apiResponse($patentNumber, 'description');
        // Check if there is an error in the returned response.
        if (isset($output["status"])) {
            $output['part'] = 'description';
            return $output;
        }

        // The path where the description key is located.
        $data = $output["ops:world-patent-data"]["ftxt:fulltext-documents"]["ftxt:fulltext-document"]["description"]["p"];

        // Make the description data a single text.
        $fullText = "";
        foreach ($data as $key => $value) {
            $fullText .= $value['$'];
        }

        $response["data"] = $fullText;
        $response["error"] = false;
        $response["status"] = true;

        return $response;
    }

    /**
     * Get claims data
     * It returns abstract as a string in response.
     *
     * @param  mixed $patentNumber
     * @return void
     */
    public static function getClaimsData($patentNumber)
    {
        $patent = new Patent;

        // Get information of claims from API.
        $output = $patent->apiResponse($patentNumber, 'claims');

        // Check if there is an error in the returned response.
        if (isset($output["status"])) {
            $output['part'] = 'claims';
            return $output;
        }

        // The path where the claims key is located.
        $data = $output["ops:world-patent-data"]["ftxt:fulltext-documents"]["ftxt:fulltext-document"]["claims"];
        if (array_is_list($data)) {
            $data = array_filter($data, function ($value) {
                return $value['@lang'] == 'EN';
            });
            $data = $data[array_key_first($data)]['claim']['claim-text'];
        } else $data = $data["claim"]["claim-text"];
        // Make the claims data a single text.
        $fullText = "";
        if (count($data) > 1) {
            foreach ($data as $key => $value) {
                $fullText .= $value['$'];
            }
        } else {
            $fullText = $data['$'];
        }

        $response["data"] = $fullText;
        $response["error"] = false;
        $response["status"] = true;
        return $response;
    }

    /**
     * API Request for PCT Entry - EP Validation
     * Patent number required for query.
     *
     * @param  mixed $number
     * @return void
     */
    public static function inquiryPatentNumber($number, $serviceType)
    {
        $patent = new Patent;

        $number = strtoupper($number);
        if ($serviceType == 'ep-validation') {
            // if number last 2 charf != B1
            if (substr($number, -2) != 'B1') {
                $number = $number . 'B1';
            }
            // if number first 2 char not contain EP
            if (substr($number, 0, 2) != 'EP') {
                $number = 'EP' . $number;
            }
        } else if ($serviceType == 'pct-entry') {
            // if number last 2 charf != B1
            //  if(substr($number, -2) != 'A1'){
            //     $number = $number . 'A1';
            // }
            // if number first 2 char not contain EP
            if (substr($number, 0, 2) != 'WO') {
                $number = 'WO' . $number;
            }
        }

        // include TestNumbers.php in app/Helpers

        // Test numbers
        require(app_path() . '/Helpers/TestNumbers.php');
        if ($response['isTest']) {
            return $response['data'];
        }

        if (!is_string($patent->getBearerToken())) {
            return $patent->getBearerToken();
        }

        try {
            // Fetch cache data if this query has been cached before.
            $cache = Cache::get($number . $serviceType);
            if ($cache) return $cache;

            $fullCycle = $patent->getFullCycleData($number);
            $description = $patent->getDescriptionData($number);
            $claims = $patent->getClaimsData($number);

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

            // Add the full text to the response.
            $response["response"]["priorities"] = $fullCycle["priorities"];
            $response["response"]["bibliographicData"] = $fullCycle["response"]["bibliographicData"];

            // Calculate the service fee.
            $response['fee'] = self::getServiceFee($serviceType);
            if ($response['fee']["status"] == false) {
                return $response['fee'];
            };

            // Calculate the full text count.
            $response['fee']["translationFee"]["fullTextCount"] = str_word_count($fullText) + env('DEFAULT_ABSTRACT_LENGTH');

            // Calculate the translation fee.
            $response['fee']["translationFee"]["amount"] = number_format((((str_word_count($fullText) + env('DEFAULT_ABSTRACT_LENGTH')) / 100) * $response['fee']['translation100keywordFee']), 2);
            $response['fee']["translationFee"]["currency"] = "EUR";
            // Cache the response for 2 hours.
            Cache::put($number . $serviceType, $response, now()->addHours(2));
        } catch (\Throwable $th) {
            $error = Error::create([
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

        return $response;
    }


    /**
     * API Request for Patent Annuity
     * Function that reaches the results of the entered patent number valid for Turkey
     *
     * @param  mixed $number
     * @return void
     */
    public static function TRRequest(string $number)
    {
        // Regex syntax we need to extract up HTML responses
        // Application information
        $basvuruBilgileri = '/<div class="col-md-4 col-xs-12 col-sm-4">\s+<div class="form-group">\s+<label class="control-label col-md-12 col-xs-12 col-sm-12">\s+<span style="font-weight: bold;"><span>(.*) : <\/span><label>(.*)<\/label><\/span>\s+<\/label>\s+<\/div>\s+<\/div>/i';

        // Payment dates
        $odemeTarihleri = '/<tr>\s+<td>(.*)<\/td>\s+<td>(.*)<\/td>\s+<td>(.*)<\/td>\s+<!-- \/\*  <td data-th-text="\${payment\.paymentno}"><\/td>\*\/-->\s+<td>(.*)<\/td>\s+<\/tr>/i';
        $patentTypes[] = '/<span style="font-weight: bold;"><span>Koruma Tipi : <\/span>\s+<label>(.*)<\/label>\s+<\/span>/i';
        $patentTypes[] = '/<span style="font-weight: bold;"><span>Koruma Tipi : <\/span>\s+<label>(.*)<\/label><\/span>/i';
        // Invention information
        $bulusAdi = '/<tr>\s+<td style="font-weight: bold;">Buluşun Adı<\/td>\s+<td>(.*)<\/td>\s+<\/tr>/i';
        $applicant = '/<table class="table table-bordered">\s+<thead>\s+<tr>\s+<th>Kişi No<\/th>\s+<th>Adı<\/th>\s+<th>Adresi<\/th>\s+<\/tr>\s+<\/thead>\s+<tbody>\s+<tr>\s+<td>(.*)<\/td>\s+<td>(.*)<\/td>\s+<td class="login-form">(.*)<\/td>\s+\s+<\/tr>\s+<\/tbody>\s+<\/table>/i';

        // In order not to send too many requests, we keep a queried number in the cache for 30 minutes.
        // Sending a query to the data source.
        $content = file_get_contents('https://portal.turkpatent.gov.tr/anonim/arastirma/patent/sonuc/detay', false, stream_context_create([
            'http' => [
                'method' => 'POST',
                'header'  => "Content-type: application/x-www-form-urlencoded",
                'content' => http_build_query([
                    'partial' => true, 'applicationNumber' => str_replace('-', '/', $number)
                ])
            ],
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
            ),
        ]));
        Cache::put($number, $content, now()->addMinutes(30));

        // If the response is empty, we return an error.
        if (str_contains($content, 'Sonuç bulunamadı')) {
            $response = [
                'status' => false,
                'message' => 'No results were found for the patent number.',
                'code' => 404
            ];
            return $response;
        }

        // We extract the data we need from the HTML response.
        preg_match_all($odemeTarihleri, $content, $result13);
        preg_match_all($basvuruBilgileri, $content, $result19);

        // Outputs 'Patent' or 'Faydalı Model'.
        foreach ($patentTypes as $patentType) {
            preg_match($patentType, $content, $patentType);
            if (!empty($patentType)) {
                $type = $patentType[1];
            }
        }

        preg_match($bulusAdi, $content, $bulusAdi);
        preg_match($applicant, $content, $applicant);

        // Applicant information
        $applicantInfo['name'] = $applicant[2] ?? '';
        $applicantInfo['address'] = $applicant[3] ?? '';

        // Payment dates
        $odemeTarihleri = array();
        if (!empty($result13[0])) {
            foreach ($result13[0] as $key => $value) {
                $odemeTarihleri[$key]["paymentNumber"] = $result13[1][$key];
                $odemeTarihleri[$key]["paymentDate"] = $result13[2][$key];
                $odemeTarihleri[$key]["paymentAmount"] = $result13[4][$key];
            }
            // Array sort by paymentNumber
            usort($odemeTarihleri, function ($a, $b) {
                return $a['paymentNumber'] <=> $b['paymentNumber'];
            });
        }

        $lastPayment = end($odemeTarihleri);
        if (empty($lastPayment)) {
            $lastPayment = 3;
        } else $lastPayment = $lastPayment["paymentNumber"] + 1;
        $fee = self::getServiceFee('patent-annuity', $lastPayment);
        if ($fee['status'] === false) {
            return $fee;
        }
        // Invention information
        $bulus['bulusAdi'] = $bulusAdi[1] ?? '';

        // Application information
        $patent = new Patent;
        $basvuruBilgileri = array();
        foreach ($result19[0] as $key => $value) {
            $basvuruBilgileri[$patent->camelize($result19[1][$key])] = $result19[2][$key];
        }
        if ($basvuruBilgileri == null) {
            $result["error"] = "true";
            $result["error"]["code"] = "101";
        } else {
            $result["error"] = false;
            $paymentInfo = array();

            // The registration date is formatted in an appropriate date format
            $paymentInfo['registrationDate'] = date("Y-m-d", strtotime($basvuruBilgileri['basvuruTarihi']));
            $patentRenewalStatus = self::patentAnnuityCondition($paymentInfo['registrationDate'], $type, $odemeTarihleri);

            // Pricing
            if ($odemeTarihleri == null) {
                $paymentInfo['status'] = "Yenileme yapılabilir";
                $paymentInfo['statusCode'] = 2;
            } else {
                if (end($odemeTarihleri)['paymentDate'] >= date('Y') - 2) {
                    $date = $paymentInfo['registrationDate'];
                    $date = end($odemeTarihleri)['paymentDate'] . substr($date, 4);
                    $lastYear = Carbon::createFromFormat('Y-m-d H:i:s',  $date . ' 00:00:00');
                    if (substr($paymentInfo['registrationDate'], 0, 4) >= 20) {
                        $paymentInfo['status'] = "Yenileme yapılamaz";
                        $paymentInfo['statusCode'] = 0;
                    }
                    if ($lastYear > Carbon::now()->addMonth(6)) {
                        $paymentInfo['status'] = "Yenileme yapılamaz";
                        $paymentInfo['statusCode'] = 0;
                    } else if ($lastYear >= Carbon::now() && $lastYear < Carbon::now()->addMonth(6)) {
                        $paymentInfo['status'] = "Yenileme yapılabilir(%25 Ek ücret)";
                        $paymentInfo['statusCode'] = 1;
                    } else {
                        $paymentInfo['status'] = "Yenileme yapılabilir";
                        $paymentInfo['statusCode'] = 2;
                    }
                } else {
                    $paymentInfo['status'] = "Yenileme yapılamaz - Son ödeme yılı " . end($odemeTarihleri)['paymentDate'];
                    $paymentInfo['statusCode'] = 0;
                    $paymentInfo['lastPayment'] = end($odemeTarihleri);
                }
            }

            $result = [
                'applicant' => $applicantInfo,
                'paymentInfo' => $paymentInfo,
                'fee' => $fee,
                'basvuruBilgileri' => $basvuruBilgileri,
                'odemeTarihleri' => $odemeTarihleri,
                'bulus' => $bulus,
                'patentRenewalStatus' => $patentRenewalStatus,
            ];
        }
        return $result;
    }
}
