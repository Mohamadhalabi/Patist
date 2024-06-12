<?php

namespace App\Helpers\Services;

use App\Helpers\Currency;
use App\Helpers\Patent;
use App\Models\AnnuityFee;
use App\Models\OfficialFee;
use App\Models\ServiceFee;
use Carbon\Carbon;

class PCTEntry
{
    public static function getServiceFee($status, $priorities, $fullText)
    {
        /**
         * * PCT Entry Service Fee
         * PCT Entry
         * ? Extra Fee: PCT Additional Time Fee
         *
         * * PCT Entry Official Fee Codes
         * 1.01.02 Rüçhan Hakkı Talebi Ücreti (Her Bir Rüçhan İçin)
         * 1.01.23 3.Yıl Sicil Kayıt Ücreti
         * 1.01.41 Patent İşbirliği Antlaşması Kapsamında Ulusal Aşamaya Giren Başvurunun Ücreti
         * 1.01.51 İnceleme Raporu Düzenlenmesi Ücreti (1.01.50 Sayılı Satır Haricindeki Başvurular İçin)
         * Extra Fee: 1.01.42 Patent İşbirliği Antlaşması Kapsamında Ulusal Aşamaya Giren Başvurunun Yapılması İçin Ek Süre Ücreti
         */

        $officialFee = [];
        $serviceFee = [];

        /**
         * * Official Fees
         */
        $officialFee[] = OfficialFee::where('code', '1.0.1.23')->first()->fee;
        $officialFee[] = OfficialFee::where('code', '1.0.1.41')->first()->fee;
        $officialFee[] = OfficialFee::where('code', '1.0.1.51')->first()->fee;

        // * Priority Fee
        if($priorities != null){
            $priorityFee = OfficialFee::where('code', '1.0.1.02')->first()->fee;
            $priorityFee->name = 'Priority Fee';
            $fee["priority"]["priorities"] = $priorities;
            $fee["priority"]["fee"]["amount"] = $priorityFee->amount;
            $fee["priority"]["fee"]["currency"] = $priorityFee->currency;
            $fee["priority"]["count"] = count($priorities);
            $fee["priority"]["fee"]["total"] = $priorityFee->amount * count($priorities);
        }

        /**
         * * Service Fee
         */
        $serviceFee[] = ServiceFee::where('slug', 'pct-entry')->where('year', date("Y"))->first();

        /**
         * * Translate Fee
         */
        $pctTranslateFee = ServiceFee::where('slug', 'translate-fee')->where('year', date("Y"))->first();
        $wordCount = str_word_count($fullText) + env('DEFAULT_ABSTRACT_LENGTH');
        $translateFee = [
            'name' => $pctTranslateFee->name,
            'amount' => $pctTranslateFee->amount,
            'currency' => $pctTranslateFee->currency,
            'wordCount' => $wordCount,
            'total' => $wordCount * $pctTranslateFee->amount,
        ];


        /**
         * * Additional Fees
         */
        if ($status == 'close-to-expire' || $status == 'very-close-to-expire') {
            /**
             * * Extra Service Fees
             * PCT Additional Time Fee
             */
            $pctAdditionalTimeFee = ServiceFee::where('slug', 'pct-additional-time-fee')->where('year', date("Y"))->first();
            $pctAdditionalTimeFee->name = 'PCT Additional Time Fee';
            $serviceFee[] = $pctAdditionalTimeFee;
            /**
             * * Extra Official Fees
             * 1.01.42 Patent İşbirliği Antlaşması Kapsamında Ulusal Aşamaya Giren Başvurunun Yapılması İçin Ek Süre Ücreti
             */

            $officialFee[] = OfficialFee::where('code', '1.0.1.42')->first()->fee;
        }

        $total['officialFee'] = array_sum(array_column($officialFee, 'amount')) / Currency::getCurrency('USD') + (($fee["priority"]["fee"]["total"] ?? 0) / Currency::getCurrency('USD'));
        $total['serviceFee'] = array_sum(array_column($serviceFee, 'amount'));
        $total['translateFee'] = $translateFee['total'];
        $total['priorityFee'] = $fee["priority"]["fee"]["total"] ?? 0;
        $total['total'] = $total['officialFee'] + $total['serviceFee'] + $total['translateFee'];

        $fee['currency']['usd'] = Currency::getCurrency('USD');
        $fee["officialFee"] = $officialFee;
        $fee["serviceFee"] = $serviceFee;
        $fee["translationFee"] = $translateFee;
        $fee["total"] = $total;
        $fee["status"] = "close-to-expire";

        return $fee;
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

        $response["priorities"] = $tmp ?? [];
        $response["response"]["bibliographicData"]["publicationDate"] = $publicationDate->format('Y-m-d');
        $response["response"]["bibliographicData"]["status"] = $check;
        $response["response"]["bibliographicData"]["applicationDate"] = $applicationDate->format('Y-m-d');
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
}
