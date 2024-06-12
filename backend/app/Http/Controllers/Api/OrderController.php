<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Test;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Response;
use App\Http\Requests\OrderDataRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Reminder;
use App\Models\Renewal;
use App\Helpers\Patent;
use Workflow\WorkflowStub;

class OrderController extends Controller
{
    public function store(OrderDataRequest $request)
    {
        $input = [
            'Application Number' => $request->application_number,
            'Publication Date' => $request->publication_date,
            'Title' => $request->invention_title,
            'Name' => $request->user_name,
            'Phone' => $request->user_phone,
            'Email' => $request->user_email,
            'service' => $request->service,
            'Service Fees' =>  $request->service_fee,
            'late_service_fee' => $request->late_service_fee,
            'Extension of 3 months official fees' => $request->official_fee_extension,
            'Official fees' => $request->official_fee,
            'EP validation official fee' => $request->ep_validation_official_fee,
            'Estimated words in claims, description, and figures' => $request->translation_quantity,
            'Translation Fees' => $request->translation_fee,
            'Total Payable' => $request->total,
            'Your Reference' =>  $request->reference,
            'EP validation service fees'  => $request->ep_validation_service_fee,
            'Translation fees per word' => $request->translation_fee_per_word,
            ];
        DB::table('order')->insert([
            'link' => $request->link,
            'details' => json_encode($input),
            ]);
    }
    public function show(Request $request)
    {
        $type = $request->link;
        $order = Test::where('link',$type)->limit(1)->get();
        return Response::json($order);
    }

    public function pctstore(OrderDataRequest $request)
    {
        $input = [
            'late_official_fee' =>$request->late_official_fee,
            'priority_length' => $request->priority_length,
            'priority_fee' => $request->priority_fee,
            'examination_fee' => $request->examination_fee,
            'pct_entry_fee' => $request->pct_entry_fee,
            'renewal_fee' => $request->renewal_fee,
            'Application Number' => $request->application_number,
            'Publication Date' => $request->publication_date,
            'Title' => $request->invention_title,
            'Name' => $request->user_name,
            'Phone' => $request->user_phone,
            'Email' => $request->user_email,
            'service' => $request->service,
            'Service Fees' =>  $request->service_fee,
            'late_service_fee' => $request->late_service_fee,
            'Official fees' => $request->official_fee,
            'Estimated words in claims, description, and figures' => $request->translation_quantity,
            'Translation Fees' => $request->translation_fee,
            'Total Payable' => $request->total,
            'Your Reference' =>  $request->reference,
        ];
        DB::table('order')->insert([
            'link' => $request->link,
            'details' => json_encode($input),
        ]);
    }

    public function annuitystore(Request $request)
    {
        $input = [
            'Application Number' => $request->application_number,
            'service' => $request->service,
            'Publication Date' => $request->publication_date,
            'Title' => $request->invention_title,
            'Name' => $request->user_name,
            'Phone' => $request->user_phone,
            'Email' => $request->user_email,
            'Service Fees' =>  $request->service_fee,
            'Official fees' => $request->official_fee,
            'Total Payable' => $request->total,
            'Your Reference' => $request->reference,
            'Patent annuity official fee' => $request->patent_annuity_official_fee,
            'late_official_fee' =>$request->late_official_fee,
            'note' => $request->note,
        ];
        DB::table('order')->insert([
            'link' => $request->link,
            'details' => json_encode($input),
        ]);

         // TODO: burada reminder ve renewal oluşturulacak
        // Create renewal
        $patentNumber = $request->application_number;
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

        $application['reference_no'] = $request->application_number;
        $application['cc'] = "TR";
        $application['region'] = "TURKPATENT";
        $application['annuity_status'] = 3 + count($response['odemeTarihleri']);
        $application['filing_date'] = $response['basvuruBilgileri']['basvuruTarihi'];
        $application['renewal_date'] = $response['patentRenewalStatus']['lastPaymentYear'];
        $application['official_fee_eur'] = $response['fee']['officialFee']['amount']/$response['fee']['currency']['eur'];
        $application['official_fee_original'] = $response['fee']['officialFee']['amount'];
        $application['official_fee_currency'] = "TRY";
        $application['agent_fee_eur'] = "0";
        $application['agent_fee_original'] = "0";
        $application['agent_fee_currency'] = "TRY";
        $application['service_fee_eur'] = $response['fee']['serviceFee']['amount'];
        $application['service_fee_exchange_rate'] = "1";
        $application['agent_fee_exchange_rate'] = "1";
        $application['total_amount_eur'] = $response['fee']['serviceFee']['amount'] + $response['fee']['officialFee']['amount']/$response['fee']['currency']['eur'];

        $renewal = new Renewal();
        $renewal->user_id = 1;
        $renewal->reference_no = $application['reference_no'];
        $renewal->cc = $application['cc'];
        $renewal->region = $application['region'];
        $renewal->annuity_status = $application['annuity_status'];
        $renewal->filing_date = $application['filing_date'];
        $renewal->renewal_date = $application['renewal_date'];
        $renewal->official_fee_eur = $application['official_fee_eur'];
        $renewal->official_fee_original = $application['official_fee_original'];
        $renewal->official_fee_currency = $application['official_fee_currency'];
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
        // $workflow = WorkflowStub::make(VerifyRenewalWorkflow::class);
        // $workflow->start($renewal);
        // $renewal->workflow_id = $workflow->id();
        $renewal->workflow_id = 888;
        $renewal->save();

        $reminder = new Reminder();
        $reminder->user_id = $request->user()->id ?? 1;
        $reminder->renewal_id = $renewal->id;
        // $reminder->workflow_id = $workflow->id();
        $reminder->workflow_id = 888;
        $reminder->code = "RNW-".$renewal->id;
        $reminder->save();

        return response()->json([
            'status' => true,
            'message' => 'Application number has been successfully added.',
            'data' => $application
        ]);
    }
    public function acceptQuote (Request $request)
    {
        $orderNo = $request->link;
        $orderDetails = Test::where('link',$orderNo)->first();
        $metadataArray = json_decode($orderDetails->details, true);
        $metadataArray['accept_quote'] = $request->accept_quote;
        $metadataArray['exchange_rate'] = $request->exchange_rate;
        Test::where('link',$orderNo)->update(['details' => json_encode($metadataArray)]);
    }
    public function additionalInfo(Request $request)
    {
        $orderNo = $request->link;

        $service = $request->service;
        if($service == "EP Validation") {
            $orderDetails = Test::where('link', $orderNo)->first();
            $metadataArray = json_decode($orderDetails->details, true);
            $metadataArray['company'] = $request->company;
            $metadataArray['relationship'] = $request->relationship;
            $metadataArray['tax_number'] = $request->taxnumber;
            $metadataArray['country'] = $request->country;
            $metadataArray['state'] = $request->state;
            $metadataArray['city'] = $request->city;
            $metadataArray['address'] = $request->address;
            $metadataArray['post_code'] = $request->postcode;
            Test::where('link', $orderNo)->update(['details' => json_encode($metadataArray)]);
        }
        else if ($service =="PCT Entry"){
            $orderDetails = Test::where('link', $orderNo)->first();
            $metadataArray = json_decode($orderDetails->details, true);
            $metadataArray['company'] = $request->company;
            $metadataArray['chapter'] = $request->chapter;
            $metadataArray['tax_number'] = $request->taxnumber;
            $metadataArray['country'] = $request->country;
            $metadataArray['state'] = $request->state;
            $metadataArray['city'] = $request->city;
            $metadataArray['address'] = $request->address;
            $metadataArray['post_code'] = $request->postcode;
            Test::where('link', $orderNo)->update(['details' => json_encode($metadataArray)]);
        }
        else if ($service == 'Patent Annuity') {
            $orderDetails = Test::where('link', $orderNo)->first();
            $metadataArray = json_decode($orderDetails->details, true);
            $metadataArray['company'] = $request->company;
            $metadataArray['tax_number'] = $request->taxnumber;
            $metadataArray['country'] = $request->country;
            $metadataArray['state'] = $request->state;
            $metadataArray['city'] = $request->city;
            $metadataArray['address'] = $request->address;
            $metadataArray['post_code'] = $request->postcode;
            Test::where('link', $orderNo)->update(['details' => json_encode($metadataArray)]);
        }
    }
    public function generatepdf(Request $request,$order)
    {

        App::setLocale($request->currentLanguage);

        $exchange = simplexml_load_file('http://www.tcmb.gov.tr/kurlar/today.xml');
        $usdcurrency = (string)$exchange->Currency[0]->BanknoteBuying;

        $eurocurrency = (string)$exchange->Currency[3]->BanknoteBuying;


        $data =Test::where('link',$order)->first();
        $orderdetails = $data->details;
        $orderdetails = json_decode($orderdetails,true);

        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Verdana');


        $view = view('pdf', compact('orderdetails', 'order', 'usdcurrency', 'eurocurrency'));
        $html = $view->render();
        $pdf = PDF::loadHtml($html);

        $pdfData = $pdf->output();
        $pdfBase64 = base64_encode($pdfData);

        return response()->json([
            'pdf' => $pdfBase64,
        ]);
    }
}
