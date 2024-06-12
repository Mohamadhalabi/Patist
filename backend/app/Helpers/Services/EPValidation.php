<?php

namespace App\Helpers\Services;

use App\Helpers\Currency;
use App\Helpers\Patent;
use App\Models\AnnuityFee;
use App\Models\OfficialFee;
use App\Models\ServiceFee;
use Carbon\Carbon;

class EPValidation
{
    /**
     * getServiceFee
     *
     * @param  string $status
     * @param  integer $wordCount
     * @param  boolean $urgent
     * @return array
     */
    public static function getServiceFee($status, $wordCount, $urgent)
    {
        $code = '1.0.1.45';
        $officialFee = OfficialFee::where('code', $code)->first();
        $annuity = AnnuityFee::where('official_fee_id', $officialFee->id)->where('year', date('Y'))->first();
        $annuity->name = "EP validation official fee";
        if ($annuity) {
            $fee['officialFee'][] = $annuity;
        }
        $serviceFee = ServiceFee::where('slug', 'ep-validation')->where('year', date("Y"))->first();
        $translateFee = ServiceFee::where('slug', 'translate-fee')->where('year', date("Y"))->first();
        $fee['serviceFee'][] = $serviceFee;
        if ($status == 'close-to-expire' || ($status == 'very-close-to-expire' && $urgent)) {
            // ep-additional-time-fee
            $epAdditionalTimeFee = ServiceFee::where('slug', 'ep-additional-time-fee')->where('year', date("Y"))->first();
            $epAdditionalOfficialItem = OfficialFee::where('code', '1.0.1.46')->first();
            $epAdditionalOfficialFee = AnnuityFee::where('official_fee_id', $epAdditionalOfficialItem->id)->where('year', date('Y'))->first();
            $epAdditionalOfficialFee->name = "Extension of 3 months official fees";
            $fee['officialFee'][] = $epAdditionalOfficialFee;
            $fee['serviceFee'][] = $epAdditionalTimeFee;
        }

        // Check if there is an error in the returned response.
        if (!$annuity || !$officialFee || !$translateFee) {
            $response = [
                'status' => false,
                'code' => 411,
                'message' => 'Price information not found.'
            ];
            return $response;
        }

        $fee['translation100keywordFee'] = $translateFee->amount;
        $fee["translationFee"]["fullTextCount"] = str_word_count($wordCount) + env('DEFAULT_ABSTRACT_LENGTH');
        $fee["translationFee"]["amount"] = number_format((((str_word_count($wordCount) + env('DEFAULT_ABSTRACT_LENGTH')) / 100) * $fee['translation100keywordFee']), 2);
        $fee["translationFee"]["currency"] = "EUR";

        $fee['currency']['eur'] = Currency::getCurrency();
        $fee['status'] = true;

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
                    $bibliographicData = $value["bibliographic-data"];
                }
            }
        } else {
            $bibliographicData = $output["ops:world-patent-data"]['exchange-documents']['exchange-document']["bibliographic-data"];
        }

        // Publication date.
        $filter = array_filter($bibliographicData["publication-reference"]["document-id"], function ($value) {
            return $value["@document-id-type"] == "epodoc";
        });
        $publicationDate = $filter[array_key_first($filter)]["date"]["$"];
        $publicationDate = date("Y-m-d", strtotime($publicationDate));
        $publicationDate = Carbon::createFromFormat('Y-m-d H:i:s',  $publicationDate . ' 00:00:00');

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

        $response["response"]["bibliographicData"]["publicationDate"] = $publicationDate;
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
