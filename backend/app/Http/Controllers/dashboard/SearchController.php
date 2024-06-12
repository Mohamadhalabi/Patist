<?php

namespace App\Http\Controllers\dashboard;

use App\Helpers\Patent;
use App\Http\Controllers\Controller;
use App\Models\Reminder;
use App\Models\Renewal;
use App\Workflows\Renewal\NewRenewalInstructionWorkflow;
use App\Workflows\Renewal\VerifyRenewalWorkflow;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Workflow\WorkflowStub;

class SearchController extends Controller
{
    public function search(Request $request)
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

        $patentNumber = $request->patentNumber;
        // $patentNumber i cache ye al. eğer cache yoksa yeni sorgu yap ve cache ekle.
        if (Cache::has($patentNumber)) {
            $content = Cache::get($patentNumber);
        } else {
            $content = file_get_contents('https://portal.turkpatent.gov.tr/anonim/arastirma/patent/sonuc/detay', false, stream_context_create([
                'http' => [
                    'method' => 'POST',
                    'header'  => "Content-type: application/x-www-form-urlencoded",
                    'content' => http_build_query([
                        'partial' => true, 'applicationNumber' => str_replace('-', '/', $patentNumber)
                    ])
                ],
                "ssl" => array(
                    "verify_peer" => false,
                    "verify_peer_name" => false,
                ),
            ]));

            Cache::put($patentNumber, $content, 240);
        }

        // In order not to send too many requests, we keep a queried number in the cache for 240 minutes.


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
        $applicantInfo['name'] = $applicant[2];
        $applicantInfo['address'] = $applicant[3];

        // Payment dates
        $odemeTarihleri = array();
        $html_output = $content;

        // $html_output içerisinde <form></form> etikesi arasındaki tüm verileri sil.
        // $html_output içerisinde <i></i> etikesi arasındaki tüm verileri sil.
        // $html_output içerisinde <div class="btn-group"></div> etikesi arasındaki tüm verileri sil.
        // $html_output içerisinde #5C9ACF kodunu #3a4f72 ile değiştir.
        // $html_output içerisinde caption-subject font-white bold yazan kısımları caption-subject olarak değiştir.
        // $html_output içerisinde ilk eşleşen portlet-title class'ına sahip div etiketini sil.
        // $html_output içerisindeki search-print class'ına sahip div etiketini sil.
        // $html_output içerisindeki <div></div> içerisindeki tüm style="" etiketlerini sil.
        // $html_output içerisindeki <td></td> içerisinde <div></div> bulunan bütün td etiketlerini sil.
        // $html_output içerisindeki > < arasındaki tüm boşlukları sil.
        $html_output = mb_convert_encoding($html_output, 'UTF-8', 'UTF-8');
        $html_output = preg_replace('/>\s+</', '><', $html_output);
        $html_output = preg_replace('/<td><div.*?<\/div><\/td>/s', '', $html_output);
        $html_output = preg_replace('/<th>Evrak<\/th>/s', '', $html_output);
        $html_output = preg_replace('/style=".*?"/s', '', $html_output);
        $html_output = preg_replace('/<div class="search-print">.*?<\/div>/s', '', $html_output);
        $html_output = preg_replace('/<div class="portlet-title">.*?<\/div>/s', '', $html_output);
        $html_output = preg_replace('/caption-subject font-white bold/', 'caption-subject', $html_output);
        $html_output = preg_replace('/#5C9ACF/', '#3a4f72', $html_output);
        $html_output = preg_replace('/<div class="btn-group">.*?<\/div>/s', '', $html_output);
        $html_output = preg_replace('/<form.*?\/form>/s', '', $html_output);
        $html_output = preg_replace('/<i.*?\/i>/s', '', $html_output);
        $html_output = preg_replace('/<td>Buluşun Adı<\/td>/s', '', $html_output);
        // <!-- --> etiketlerini sil.
        $html_output = preg_replace('/<!--.*?-->/s', '', $html_output);
        // convert "Başvuru Bilgileri" to "Test Title"
        $html_output = preg_replace('/Buluşun Adı/', '<span style="font-weight:bold">Invention Name</span>', $html_output);
        $html_output = preg_replace('/Buluşun Özeti/', '<span style="font-weight:bold">Invention Summary</span>', $html_output);
        $html_output = preg_replace('/Adı/', '<span style="font-weight:bold">Name</span>', $html_output);
        $html_output = preg_replace('/Adresi/', '<span style="font-weight:bold">Address</span>', $html_output);
        $html_output = preg_replace('/Başvuru Tarihi/', '<span>Application Date</span>', $html_output);
        $html_output = preg_replace('/Başvuru Numarası/', '<span>Application No</span>', $html_output);
        $html_output = preg_replace('/Başvuru Bilgileri/', '<span>Application Details</span>', $html_output);
        $html_output = preg_replace('/Başvuru Sahipleri/', '<span>Applicants</span>', $html_output);
        $html_output = preg_replace('/Kişi No/', '<span>Person No</span>', $html_output);
        $html_output = preg_replace('/Buluş Bilgileri/', '<span>Invention Information</span>', $html_output);
        $html_output = preg_replace('/Buluş Sahipleri/', '<span>Inventors</span>', $html_output);
        $html_output = preg_replace('/Vekil Bilgileri/', '<span>Attorney Information</span>', $html_output);
        $html_output = preg_replace('/Rüçhan Bilgileri/', '<span>Priority Information</span>', $html_output);
        $html_output = preg_replace('/Rüçhan Tarihi/', '<span>Priority Date</span>', $html_output);
        $html_output = preg_replace('/Rüçhan Numarası/', '<span>Priority No</span>', $html_output);
        $html_output = preg_replace('/Rüçhanın Alındığı Ülke/', '<span>Country of Priority</span>', $html_output);
        $html_output = preg_replace('/Buluşun Tasnif Sınıfları/', '<span>Classification Classes of the Invention</span>', $html_output);
        $html_output = preg_replace('/Patent Başvurusuna İlişkin İşlemler/', '<span>Procedures Regarding Patent Application</span>', $html_output);
        $html_output = preg_replace('/İşlem/', '<span>Procedure</span>', $html_output);
        $html_output = preg_replace('/Tebliğ Tarihi/', '<span>Notification Date</span>', $html_output);
        $html_output = preg_replace('/Ödeme Tarihleri/', '<span>Payment Dates</span>', $html_output);
        $html_output = preg_replace('/Ödeme Tarihi/', '<span>Payment Date</span>', $html_output);
        $html_output = preg_replace('/Tescil Numarası/', '<span>Registration Number</span>', $html_output);
        $html_output = preg_replace('/Tescil Tarihi/', '<span>Registration Date</span>', $html_output);
        $html_output = preg_replace('/Koruma Tipi/', '<span>Protection Type</span>', $html_output);
        $html_output = preg_replace('/Evrak Numarası/', '<span>Document Number</span>', $html_output);
        $html_output = preg_replace('/Evrak Tarihi/', '<span>Document Date</span>', $html_output);
        $html_output = preg_replace('/Başvuru Şekli/', '<span>Application Form</span>', $html_output);
        $html_output = preg_replace('/Yayınlar/', '<span>Publications</span>', $html_output);
        $html_output = preg_replace('/Açıklama/', '<span>Description</span>', $html_output);
        $html_output = preg_replace('/Yayın Tarihi/', '<span>Publication Date</span>', $html_output);
        $html_output = preg_replace('/Tarih/', '<span>Date</span>', $html_output);
        $html_output = preg_replace('/Sıra/', '<span>Order</span>', $html_output);
        $html_output = preg_replace('/<th>Yıl<\/th>/', '<th><span>Year</span></th>', $html_output);
        $html_output = preg_replace('/Ödenen Miktar/', '<span>Amount Paid</span>', $html_output);
        // save search history
        // portlet-body form olan div'i seç
        $html_output = preg_replace('/<div class="col-md-12">.*?<\/div>/s', '', $html_output);
        // $html_output değişkeninin başındaki ve sonundaki ilk 1 <div> ve son 1 </div> etiketlerini sil.
        $html_output = preg_replace('/^<div>/', '', $html_output);
        $html_output = preg_replace('/<\/div>$/', '', $html_output);
        $html_output = preg_replace('/^<div class="row">/', '', $html_output);
        $html_output = preg_replace('/<\/div>$/', '', $html_output);
        $html_output = preg_replace('/^<div class="portlet-body form">/', '', $html_output);
        $html_output = preg_replace('/<\/div>$/', '', $html_output);

        // $html_output içerisinden <div class="portlet light bordered"><div class="portlet-title"><div class="caption"><span class="caption-subject "><span>Payment Dates(.*)<\/div> kısmını sil ve bir değişkene ata.
        preg_match('/<div class="portlet light bordered"><div class="portlet-title"><div class="caption"><span class="caption-subject "><span>Payment Dates(.*)<\/div>/s', $html_output, $paymentDates);
        $html_output = preg_replace('/<div class="portlet light bordered"><div class="portlet-title"><div class="caption"><span class="caption-subject "><span>Payment Dates(.*)<\/div>/s', '', $html_output);

        return response()->json([
            'status' => true,
            'message' => 'Patent information has been successfully retrieved.',
            'data' => $html_output
        ]);
    }

    /**
     * searchApplicationNo
     *
     * @param  mixed $request
     * @return void
     */
    public function searchApplicationNo(Request $request)
    {
        /**
         * Validity Control
         */
        $patentNumber = $request->patentNumber;
        $patentNumber = preg_replace('/[.,\/#!$%\^&\*;:{}=\-_`~()]/', '-', $patentNumber);
        $response = Patent::TRRequest($patentNumber);

        // eğer status varsa ve status = false, code = 404 ise hata döndür.
        if (isset($response['status']) && $response['status'] == false && $response['code'] == 404) {
            return response()->json([
                'status' => false,
                'message' => 'No results were found for the application number.',
                'code' => 404
            ]);
        }
        // bu patent numarası daha önce eklendiyse hata döndür.
        $renewal = Renewal::where('reference_no', $patentNumber)->first();
        if ($renewal) {
            return response()->json([
                'status' => false,
                'message' => 'This application number has already been added to your renewal list.',
                'code' => 400
            ]);
        }
        if ($response['patentRenewalStatus']['status'] == false) {

            return response()->json([
                'status' => false,
                'message' => $response['patentRenewalStatus']['message'],
                'code' => 201
            ]);
        }
        return response()->json([
            'status' => true,
            'message' => 'Application number has been successfully added.',
            'data' => $response
        ]);
    }

    public function saveRenewalRequest(Request $request)
    {

        /**
         * Validity Control
         */
        $patentNumber = $request->patentNumber;
        $patentNumber = preg_replace('/[.,\/#!$%\^&\*;:{}=\-_`~()]/', '-', $patentNumber);
        $response = Patent::TRRequest($patentNumber);
        // eğer status varsa ve status = false, code = 404 ise hata döndür.
        if (isset($response['status']) && $response['status'] == false && $response['code'] == 404) {
            return response()->json([
                'status' => false,
                'message' => 'No results were found for the application number.',
                'code' => 404
            ]);
        }

        // bu patent numarası daha önce eklendiyse hata döndür.
        $renewal = Renewal::where('reference_no', $patentNumber)->first();
        if ($renewal) {
            return response()->json([
                'status' => false,
                'message' => 'This application number has already been added to your renewal list.',
                'code' => 404
            ]);
        }
        if ($response['patentRenewalStatus']['status'] == false) {
            return response()->json([
                'status' => false,
                'message' => $response['patentRenewalStatus']['message'],
                'code' => 200
            ]);
        }


        $user = auth('sanctum')->user() ?? 1;

        $application['reference_no'] = $request->patentNumber;
        $application['cc'] = "TR";
        $application['region'] = "TURKPATENT";
        $application['annuity_status'] = 3 + count($response['odemeTarihleri']);
        $application['filing_date'] = $response['basvuruBilgileri']['basvuruTarihi'];
        $application['renewal_date'] = $response['patentRenewalStatus']['lastPaymentYear'];
        $application['official_fee_eur'] = $response['fee']['officialFee']['amount'] / $response['fee']['currency']['eur'];
        $application['official_fee_original'] = $response['fee']['officialFee']['amount'];
        $application['official_fee_currency'] = "TRY";
        $application['agent_fee_eur'] = "0";
        $application['agent_fee_original'] = "0";
        $application['agent_fee_currency'] = "TRY";
        $application['service_fee_eur'] = $response['fee']['serviceFee']['amount'] ?? 200;
        $application['service_fee_exchange_rate'] = "1";
        $application['agent_fee_exchange_rate'] = "1";
        $application['total_amount_eur'] = 140;

        $renewal = new Renewal();
        $renewal->user_id = auth('sanctum')->user()->id ?? 1;
        $renewal->reference_no = $application['reference_no'];
        $renewal->cc = $application['cc'];
        $renewal->region = $application['region'];
        $renewal->annuity_status = $application['annuity_status'];
        $renewal->filing_date = $application['filing_date'];
        $renewal->renewal_date = $application['renewal_date'];
        $renewal->official_fee_eur = $application['official_fee_eur'];
        $renewal->official_fee_original = 200;
        $renewal->official_fee_currency = "TRY";
        $renewal->agent_fee_eur = $application['agent_fee_eur'];
        $renewal->agent_fee_original = $application['agent_fee_original'];
        $renewal->agent_fee_currency = $application['agent_fee_currency'];
        $renewal->service_fee_eur = $application['service_fee_eur'];
        $renewal->service_fee_exchange_rate = $application['service_fee_exchange_rate'];
        $renewal->agent_fee_exchange_rate = $application['agent_fee_exchange_rate'];
        $renewal->total_amount_eur = $application['total_amount_eur'];
        $renewal->is_approved = false;
        $renewal->status = "payment-pending";

        // start renewal workflow here
        $workflow = WorkflowStub::make(NewRenewalInstructionWorkflow::class);
        $renewal->workflow_id = $workflow->id();
        $renewal->save();

        $reminder = new Reminder();
        $reminder->user_id = auth('sanctum')->user()->id ?? 1;
        $reminder->renewal_id = $renewal->id;
        $reminder->workflow_id = $workflow->id();
        $reminder->code = "RNW-" . $renewal->id;
        $reminder->status = 'RNWo.1.1';
        $reminder->start_time = Carbon::now();

        $reminder->emails = json_encode([$user->email]);
        $reminder->type = json_encode([
            'label' => 'Patent Renewal',
            'value' => 'patent-renewal'
        ]);
        $reminder->short_name = substr($response['bulus']['bulusAdi'], 0, 40);
        $reminder->start_date = Carbon::now()->format('Y-m-d');
        $reminder->end_date = Carbon::now()->format('Y-m-d');
        $reminder->deadline = Carbon::now()->format('Y-m-d');
        $reminder->frequency = 0;
        $reminder->repetition = 0;
        $reminder->save();

        $reminder->ref_code = "RNW" . '.' . $user->id . '.' . 1000 + $reminder->id;
        $reminder->save();

        $renewal->reminder_id = $reminder->id;
        $renewal->save();

        $workflow->start($renewal, $renewal);
        return response()->json([
            'status' => true,
            'message' => 'Application number has been successfully added.',
            'data' => $application,
            'response' => $response
        ]);
    }
}
