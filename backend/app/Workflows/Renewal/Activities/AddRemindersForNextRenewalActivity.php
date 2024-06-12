<?php

namespace App\Workflows\Renewal\Activities;

use App\Helpers\Patent;
use App\Jobs\AddReminderForNextYearJob;
use App\Models\Reminder;
use App\Models\Renewal;
use Workflow\Activity;
use Workflow\WorkflowStub;

class AddRemindersForNextRenewalActivity extends Activity
{
    public function execute($renewal)
    {
        $renewal->reminder->status = 'RNWo.5';
        $renewal->reminder->save();

        $applicationData = $renewal->reference_no;
        $response = Patent::TRRequest($applicationData);

        // eğer status varsa ve status = false, code = 404 ise hata döndür.
        if (isset($response['status']) && $response['status'] == false && $response['code'] == 404 && ($renewal->type == 'patent' && $renewal->annuity_status < 20 || $renewal->type == 'utility-model' && $renewal->annuity_status < 10)) {
            $status = false;
        } else {
            $application['reference_no'] = $applicationData;
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
            $renewal->user_id = auth('sanctum')->user()->id ?? 1;
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
            $workflow = WorkflowStub::make(NewRenewalInstructionWorkflow::class);
            $renewal->workflow_id = $workflow->id();
            $renewal->save();

            $reminder = new Reminder();
            $reminder->user_id = auth('sanctum')->user()->id ?? 1;
            $reminder->renewal_id = $renewal->id;
            $reminder->workflow_id = $workflow->id();
            $reminder->code = "RNW-".$renewal->id;
            $reminder->status = 'RNWo.1.1';
            $reminder->save();

            $renewal->reminder_id = $reminder->id;
            $renewal->save();

            // after 10 months
            AddReminderForNextYearJob::dispatch($renewal, $workflow)->delay(now()->addMonths(10));
        }

        // Reminder added successfully
        $reminder = $renewal->reminder;
        $reminder->workflow_status = 'being-followed';
        $reminder->save();
    }
}
