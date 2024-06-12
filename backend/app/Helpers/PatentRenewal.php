<?php

namespace App\Helpers;

use App\Jobs\DeadlineReminder;
use App\Jobs\PaymentReminderJob;
use App\Models\Reminder;
use App\Models\Renewal;
use App\Models\TestCase;
use Carbon\Carbon;
use Workflow\WorkflowStub;
use App\Workflows\Renewal\NewRenewalInstructionWorkflow;

class PatentRenewal
{
    private $renewal = [];

    /**
     * is_existing
     *
     * @param  mixed $model
     * @param  mixed $patentNumber
     * @return void
     */
    public function is_existing($model, $patentNumber)
    {
        $count = $model->where('patent_number', $patentNumber)->count();

        return $count;
    }

    /**
     * is_wrong
     *
     * @param  mixed $response
     * @return void
     */
    public function is_wrong($response)
    {
        if (strpos($response, 'Sonuç bulunamadı') !== false) {
            return true;
        }

        return false;
    }

    /**
     * set_status
     *
     * @param  mixed $model
     * @param  mixed $status
     * @return void
     */
    public function set_status($model, $status)
    {
        $model->is_approved = $status;
        $model->is_payment = $status;
        $model->is_completed = $status;
        $model->save();

        if($model->is_approved == $status && $model->is_payment == $status && $model->is_completed == $status){
            return true;
        }

        return false;
    }

    /**
     * patent_validity
     *
     * @param  mixed $response
     * @param  mixed $type
     * @return void
     */
    public function patent_validity($response, $type)
    {
        $paymentDates = '/<tr>\s+<td>(.*)<\/td>\s+<td>(.*)<\/td>\s+<td>(.*)<\/td>\s+<!-- \/\*  <td data-th-text="\${payment\.paymentno}"><\/td>\*\/-->\s+<td>(.*)<\/td>\s+<\/tr>/i';
        preg_match_all($paymentDates, $response, $matches);

        $dates = array();
        if (!empty($matches[0])) {
            foreach ($matches[0] as $key => $value) {
                $dates[$key]["paymentNumber"] = $matches[1][$key];
                $dates[$key]["paymentDate"] = $matches[2][$key];
                $dates[$key]["paymentAmount"] = $matches[4][$key];
            }
            // Array sort by paymentNumber
            usort($dates, function ($a, $b) {
                return $a['paymentNumber'] <=> $b['paymentNumber'];
            });
        }

        if(($type == 'patent' && count($dates) < 19) || ($type == 'utility-model' && count($dates) < 9)){
            return true;
        }

        return false;
    }

    /**
     * create_communication_history_email
     *
     * @param  mixed $user
     * @param  mixed $renewal
     * @param  mixed $type
     * @param  mixed $title
     * @param  mixed $description
     * @param  mixed $content
     * @return void
     */
    public function create_communication_history_email($user,$renewal, $type, $title, $description, $content)
    {
        $user->notify(new \App\Notifications\Renewal\InstructionAcceptedNotification($renewal));
        \App\Models\Email::create([
            'renewal_id' => $renewal->reminder->id,
            'type' => $type,
            'title' => $title,
            'description' => $description,
            'content' => $content
        ]);
    }

    /**
     * set_payment_reminder_job
     *
     * @param  mixed $renewal
     * @param  mixed $email
     * @param  mixed $reminderTime
     * @return void
     */
    public function set_payment_reminder_job($renewal, $email, $reminderTime)
    {
        PaymentReminderJob::dispatch($email, $renewal)->delay(now()->addDays($reminderTime));
    }

    /**
     * set_deadline_reminder_job
     *
     * @param  mixed $renewal
     * @param  mixed $delay
     * @return void
     */
    public function set_deadline_reminder_job($renewal, $delay)
    {
        DeadlineReminder::dispatch($renewal)->delay(now()->addDays($delay));
    }

    /**
     * get_deadline_time
     *
     * @param  mixed $renewal
     * @return void
     */
    public function get_deadline_time($renewal)
    {
        $renewal_date = $renewal->renewal_date;
        $renewal_date = Carbon::createFromFormat('Y-m-d', $renewal_date);
        $current_date = Carbon::now();
        $days_difference = $current_date->diffInDays($renewal_date);

        return $days_difference;
    }

    public function set_workflow($reference)
    {
        /**
         * Validity Control
         */
        $patentNumber = preg_replace('/[.,\/#!$%\^&\*;:{}=\-_`~()]/', '-', $reference);
        $case = TestCase::where('test_case_name', 'set_workflow')->first();
        $response = Patent::TRRequest("2020/08705");
        // eğer status varsa ve status = false, code = 404 ise hata döndür.
        if (isset($response['status']) && $response['status'] == false && $response['code'] == 404) {
            return response()->json([
                'status' => false,
                'message' => 'No results were found for the application number.',
                'code' => 404
            ]);
        }

        // bu patent numarası daha önce eklendiyse hata döndür.
        $renewal = Renewal ::where('reference_no', $patentNumber)->first();
        if ($renewal) {
            return response()->json([
                'status' => false,
                'message' => 'This application number has already been added to your renewal list.',
                'code' => 404
            ]);
        }

        $application['reference_no'] = $reference;
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
        $reminder->code = "RNW-".$renewal->id;
        $reminder->status = 'RNWo.1.1';
        $reminder->start_time = Carbon::now();
        $reminder->save();

        $renewal->reminder_id = $reminder->id;
        $renewal->save();

        $workflow->start($renewal, $renewal);

        return [
            'workflow_id' => $workflow->id(),
            'renewal_id' => $renewal->id,
            'reminder_id' => $reminder->id,
        ];
    }


}
