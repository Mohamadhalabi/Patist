<?php

namespace App\Http\Controllers\Api\Renewal;

use App\Helpers\ReminderService;
use App\Http\Controllers\Controller;
use App\Models\CustomReminder;
use App\Models\Reminder;
use App\Models\ReminderFragment;
use App\Models\ReminderList;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CustomReminderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get all custom reminders
        // $customReminders = ReminderList::with('fragments')->get();
        // return response()->json($customReminders);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $reminderService = new ReminderService();
        $frequency = $reminderService->getFrequency($request->frequency, $request->frequencyType);
        $reminderTab = $request->reminderTab['value'];

        $reminderService->specificDates = $request->specificDates;

        $reminder = new Reminder();
        $reminder->user_id = auth('sanctum')->user()->id ?? 1;
        $reminder->ref_code = $request->refCode;
        $reminder->short_name = $request->shortName;
        $reminderTab = $request->reminderTab['value'];

        /**
         * Reminder Tab : One Time
         */
        if ($reminderTab == 'one-time') {
            $start_date = Carbon::parse($request->startDate);
            if ($start_date >= Carbon::now()) {
                $reminder->start_date = $start_date;
                $reminder->end_date = $start_date;
                $reminder->deadline = $start_date;
                $reminderDates = $reminderService->getOneTimeReminderDate($start_date);
            }
            /**
             * Reminder Tab : Repeat
             */
        } else if ($reminderTab == 'repeat') {
            $panel = $request->panel['value'];

            /**
             * Panel : Until To
             */
            if ($panel == 'until-to') {
                $start_date = Carbon::now();
                $end_date = Carbon::parse($request->endDate);
                $last_reminder_date = Carbon::parse($start_date);
                $last_date = Carbon::parse($end_date);
                $reminderDates = $reminderService->getReminderDates($last_reminder_date, $last_date, $frequency);

                $reminder->start_date = $start_date;
                $reminder->end_date = $end_date;
                $reminder->deadline = $end_date;

                /**
                 * Panel : Since From
                 */
            } else if ($panel == 'since-from') {
                $start_date = Carbon::parse($request->startDate);
                $sinceFrom = $request->sinceFrom['value'];

                /**
                 * Panel : Since From : Until Dismissed
                 */
                if ($sinceFrom == 'until-dismissed') {
                    $start_date = Carbon::parse($request->startDate);
                    $reminder->until_dismissed = true;
                    $reminder->start_date = $start_date;
                    $reminder->end_date = $start_date;
                    $reminder->deadline = $start_date;

                    $last_reminder_date = Carbon::parse($start_date);
                    $last_date = Carbon::parse($start_date);
                    $reminderDates = $reminderService->getReminderDates($last_reminder_date, $last_date, $frequency, true);

                    /**
                     * Panel : Since From : Time
                     */
                } else if ($sinceFrom == 'Time') {
                    $repetition = $request->repetition;
                    $start_date = Carbon::parse($request->startDate);
                    $last_reminder_date = Carbon::parse($start_date);
                    $reminderDates = $reminderService->getReminderDatesWithRepetition($last_reminder_date, $frequency, $repetition);

                    $reminder->start_date = $start_date;
                    $reminder->end_date = $last_reminder_date;
                    $reminder->deadline = $last_reminder_date;
                }

                /**
                 * Panel : Between
                 */
            } else if ($panel == 'between') {
                $start_date = Carbon::parse($request->startDate);
                $end_date = Carbon::parse($request->endDate);
                $last_reminder_date = Carbon::parse($start_date);
                $last_date = Carbon::parse($end_date);
                $reminderDates = $reminderService->getReminderDates($last_reminder_date, $last_date, $frequency);

                $reminder->start_date = $start_date;
                $reminder->end_date = $end_date;
                $reminder->deadline = $end_date;

                /**
                 * Panel : Until Dismissed
                 */
            } else if ($panel == 'until-dismissed') {
                $start_date = Carbon::parse($request->startDate);
                $reminder->until_dismissed = true;
                $reminder->start_date = $start_date;
                $reminder->end_date = $start_date;
                $reminder->deadline = $start_date;

                $last_reminder_date = Carbon::parse($start_date);
                $last_date = Carbon::parse($start_date);
                $reminderDates = $reminderService->getReminderDates($last_reminder_date, $last_date, $frequency, true);
            } else {
                return "false";
            }
        } else {
            return "false";
        }
        $teams = $request->teams;
        $reminder->teams = json_encode($teams);
        $reminder->repetition = $request->repetition ?? 1;
        $reminder->frequency = $request->frequency;
        $reminder->type = json_encode($request->type);
        $reminder->emails = json_encode($request->emails);
        $reminder->status = "active";
        $reminder->note = $request->note;
        $reminder->send_reminder_email = $request->sendEmail;
        $reminder->use_reminder_alarm = $request->sendCalendarAlert;
        $reminder->send_notification_on_holiday = $request->sendOnHoliday;
        $reminder->save();

        if (count($reminderDates) > 0) {
            $i = 1;
            foreach ($reminderDates as $reminder_date) {
                $fragment = new ReminderFragment();
                $fragment->reminder_id = $reminder->id;
                $fragment->title = $i . '.' . " Reminder";
                $fragment->start_date = $reminder_date['start_date'];
                $fragment->end_date = $reminder_date['end_date'];
                $fragment->save();
                $i++;
            }
        }

        return response()->json([
            "message" => "Custom Reminder Created Successfully",
            "status" => 200
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CustomReminder  $customReminder
     * @return \Illuminate\Http\Response
     */
    public function show(ReminderList $customReminder)
    {
        $team_ids = json_decode($customReminder->teams);
        return response()->json($customReminder);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CustomReminder  $customReminder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reminder $customReminder)
    {
        $customReminder->ref_code = $request->refCode;
        $customReminder->short_name = $request->shortName;
        $customReminder->note = $request->note;
        $customReminder->send_reminder_email = $request->sendEmail;
        $customReminder->use_reminder_alarm = $request->sendCalendarAlert;
        $customReminder->send_notification_on_holiday = $request->sendOnHoliday;
        $customReminder->type = json_encode($request->type);
        $customReminder->emails = json_encode($request->emails);
        $customReminder->teams = json_encode($request->teams);
        $customReminder->is_active = $request->is_active;
        $customReminder->save();

        return response()->json([
            "message" => "Reminder Updated Successfully",
            "status" => 200,
            "reminder" => $customReminder
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CustomReminder  $customReminder
     * @return \Illuminate\Http\Response
     */
    public function destroy(ReminderList $customReminder)
    {
        //
    }

    /**
     * getReminderDates
     *
     * @param  mixed $request
     * @return void
     */
    public function getFields(Request $request)
    {
        $type = $request->type;
        $deadline = $request->deadline;
        $response = [];
        $response['tab'] = 'repeat';
        $response['time'] = 'Days';
        if ($type == 'marka-basvurusu-red-itiraz') {
            $response['frequency'] = 7;
            $response['start_date'] = Carbon::parse($deadline)->subDays(15)->format('Y/m/d');
            $response['end_date'] = Carbon::parse($deadline)->format('Y/m/d');
            $response['deadline'] = Carbon::parse($deadline)->format('Y/m/d');
            $response['panel'] = [
                'value' => 'between',
                'label' => 'Between'
            ];
        } else if ($type == 'marka-basvurusu-yayindan-inme') {
            $response['frequency'] = 7;
            $response['start_date'] = Carbon::parse($deadline)->subDays(15)->format('Y/m/d');
            $response['end_date'] = Carbon::parse($deadline)->subDays(15)->format('Y/m/d');
            $response['deadline'] = Carbon::parse($deadline)->subDays(15)->format('Y/m/d');
            $response['panel'] = [
                'value' => 'since-from',
                'label' => 'Since from'
            ];
        } else if ($type == 'marka-basvurusu-tescil-harcinin-odenmesi') {
            $response['frequency'] = 7;
            $response['start_date'] = Carbon::parse($deadline)->subDays(30)->format('Y/m/d');
            $response['end_date'] = Carbon::parse($deadline)->format('Y/m/d');
            $response['deadline'] = Carbon::parse($deadline)->format('Y/m/d');
            $response['panel'] = [
                'value' => 'between',
                'label' => 'Between'
            ];
        } else if ($type == 'vekillik-yenileme') {
            $response['frequency'] = 7;
            $response['start_date'] = Carbon::parse($deadline)->subDays(30)->format('Y/m/d');
            $response['panel'] = [
                'value' => 'since-from',
                'label' => 'Since from'
            ];
        } else if ($type == 'kullanim-son-tarihi') {
            $response['frequency'] = 30;
            $response['start_date'] = Carbon::parse($deadline)->subDays(180)->format('Y/m/d');
            $response['end_date'] = Carbon::parse($deadline)->format('Y/m/d');
            $response['deadline'] = Carbon::parse($deadline)->format('Y/m/d');
            $response['panel'] = [
                'value' => 'between',
                'label' => 'Between'
            ];
        }

        return $response;
    }
    /**
     * getReminderDates
     *
     * @param  mixed $request
     * @return void
     */
    public function getReminderDates(Request $request)
    {
        $reminderService = new ReminderService();
        $frequency = $reminderService->getFrequency($request->frequency, $request->frequencyType);
        $reminderTab = $request->reminderTab['value'];

        /**
         * Reminder Tab : One Time
         */
        if ($reminderTab == 'one-time') {
            $start_date = Carbon::parse($request->startDate);
            $reminderDates[] = $reminderService->getOneTimeReminderDate($start_date);

            /**
             * Reminder Tab : Repeat
             */
        } else if ($reminderTab == 'repeat') {
            $panel = $request->panel['value'];

            /**
             * Panel : Until To
             */
            if ($panel == 'until-to') {
                $start_date = Carbon::now();
                $end_date = Carbon::parse($request->endDate);
                $last_reminder_date = Carbon::parse($start_date);
                $last_date = Carbon::parse($end_date);
                $reminderDates = $reminderService->getReminderDates($last_reminder_date, $last_date, $frequency);

                /**
                 * Panel : Since From
                 */
            } else if ($panel == 'since-from') {
                $start_date = Carbon::parse($request->startDate);
                $sinceFrom = $request->sinceFrom['value'];

                /**
                 * Panel : Since From : Until Dismissed
                 */
                if ($sinceFrom == 'until-dismissed') {
                    $start_date = Carbon::parse($request->startDate);
                    $last_reminder_date = Carbon::parse($start_date);
                    $last_date = Carbon::parse($start_date);
                    $reminderDates = $reminderService->getReminderDates($last_reminder_date, $last_date, $frequency, true);

                    /**
                     * Panel : Since From : Time
                     */
                } else if ($sinceFrom == 'time') {
                    $repetition = $request->repetition;
                    $start_date = Carbon::parse($request->startDate);
                    $last_reminder_date = Carbon::parse($start_date);
                    $reminderDates = $reminderService->getReminderDatesWithRepetition($last_reminder_date, $frequency, $repetition);
                }

                /**
                 * Panel : Between
                 */
            } else if ($panel == 'between') {
                $start_date = Carbon::parse($request->startDate);
                $end_date = Carbon::parse($request->endDate);
                $last_reminder_date = Carbon::parse($start_date);
                $last_date = Carbon::parse($end_date);
                $reminderDates = $reminderService->getReminderDates($last_reminder_date, $last_date, $frequency);

                /**
                 * Panel : Until Dismissed
                 */
            } else if ($panel == 'until-dismissed') {
                $start_date = Carbon::parse($request->startDate);

                $last_reminder_date = Carbon::parse($start_date);
                $last_date = Carbon::parse($start_date);
                $reminderDates = $reminderService->getReminderDates($last_reminder_date, $last_date, $frequency, true);
            } else {
                return "false";
            }
        } else {
            return "false";
        }

        return $reminderDates ?? [];
    }
}
