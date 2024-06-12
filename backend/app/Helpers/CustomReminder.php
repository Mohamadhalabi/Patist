<?php

namespace App\Helpers;

use App\Jobs\Reminder\SetReminderJob;
use Carbon\Carbon;

class CustomReminder
{
    private $inputs;
    private $case;
    private $deadline;

    public $holidays = [
        // Yılbaşı
        '2023-01-01',
        // Ramazan Bayramı / Ulusal Egemenlik ve Çocuk Bayramı
        '2023-04-21', '2023-04-22', '2023-04-23',
        // İşçi Bayramı
        '2023-05-01',
        // Atatürk'ü Anma, Gençlik ve Spor Bayramı
        '2023-05-19',
        // Kurban Bayramı
        '2023-08-28', '2023-08-29', '2023-08-30', '2023-09-01',
        // Demokrasi Bayramı
        '2023-07-15',
        // Zafer Bayramı
        '2023-08-30',
        // Cumhuriyet Bayramı
        '2023-10-29',
    ];

    public function __construct($case, $deadline, $inputs = [], $holidays = [])
    {
        $this->inputs = $inputs;
        $this->holidays = $holidays;
        $this->case = $case;
        $this->deadline = $deadline;
    }

    /**
     * Check if the date is a business day
     *
     * @param  mixed $date
     * @return void
     */
    public function isBusinessDay($date)
    {
        $date = Carbon::createFromFormat('Y-m-d', $date);

        if($date->isWeekend())
        {
            return false;
        }

        if(in_array($date->format('Y-m-d'), $this->holidays))
        {
            return false;
        }

        return true;
    }

    public function getNextBusinessDay($date)
    {
        $date = Carbon::createFromFormat('Y-m-d', $date);

        while(!$this->isBusinessDay($date))
        {
            $date->addDay();
        }

        return $date;
    }


    /**
     * Set reminder for the case
     *
     * @return void
     */
    public function setReminder()
    {
        switch($this->case)
        {
            case 'case_1':
                /**
                 *  Title : Türkpatent Marka Başvurusu - Red İtiraz
                 *  Deadline : true
                 *  Start Date : $deadline - 15 days
                 *  Frequency : 7 days
                 *  End Date : $deadline
                 */

                $deadline = Carbon::createFromFormat('d/m/Y', $this->deadline);
                $start_date = Carbon::createFromFormat('d/m/Y', $this->deadline)->subDays(15);
                $end_date = $deadline;
                $frequency = 7;

                $reminder = [
                    'title' => 'Türkpatent Marka Başvurusu - Red İtiraz',
                    'deadline' => $deadline,
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'frequency' => $frequency,
                ];
                break;
            case 'case_2':
                /**
                 * Title : Türkpatent Marka Başvurusu - Yayından İnme
                 * Deadline : true
                 * Start Date : First working day after $deadline
                 * Frequency : 7 days
                 * End Date : infinite
                 */

                $deadline = Carbon::createFromFormat('d/m/Y', $this->deadline);
                $start_date = $this->getNextBusinessDay($deadline);
                $end_date = 'infinite';
                $frequency = 7;

                $reminder = [
                    'title' => 'Türkpatent Marka Başvurusu - Yayından İnme',
                    'deadline' => $deadline,
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'frequency' => $frequency,
                ];
                break;
            case 'case_3':
                /**
                 * Title : Türkpatent Marka Başvurusu - Tescil Harcının Ödenmesi
                 * Deadline : true
                 * Start Date : First working day after $deadline - 30 days
                 * Frequency : 7 days
                 * End Date : $deadline
                 */

                $deadline = Carbon::createFromFormat('d/m/Y', $this->deadline);
                $start_date = $this->getNextBusinessDay($deadline)->subDays(30);
                $end_date = $deadline;
                $frequency = 7;

                $reminder = [
                    'title' => 'Türkpatent Marka Başvurusu - Tescil Harcının Ödenmesi',
                    'deadline' => $deadline,
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'frequency' => $frequency,
                ];
                break;
            case 'case_4':
                /**
                 * Title : Türkpatent Vekillik Yenileme
                 * Deadline : true
                 * Start Date : $deadline - 30 days
                 * Frequency : 7 days
                 * End Date : infinite
                 */

                $deadline = Carbon::createFromFormat('d/m/Y', $this->deadline);
                $start_date = $deadline->subDays(30);
                $end_date = 'infinite';
                $frequency = 7;

                $reminder = [
                    'title' => 'Türkpatent Vekillik Yenileme',
                    'deadline' => $deadline,
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'frequency' => $frequency,
                ];
                break;
            case 'case_5':
                /**
                 * Title: Patent - Kullanım Son Tarihi
                 * Deadline : true
                 * Start Date : $deadline - 180 days
                 * Frequency : 30 days
                 * End Date : $deadline
                 */

                $deadline = Carbon::createFromFormat('d/m/Y', $this->deadline);
                $start_date = $deadline->subDays(180);
                $end_date = $deadline;
                $frequency = 30;

                $reminder = [
                    'title' => 'Patent - Kullanım Son Tarihi',
                    'deadline' => $deadline,
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'frequency' => $frequency,
                ];
            default:
                break;
        }

        $reminder['inputs'] = $this->inputs;
        $reminder['status'] = 'active';

        SetReminderJob::dispatch($reminder);

        if($reminder['inputs']['create_next_year_reminder'])
        {
            $reminder['deadline'] = $reminder['deadline']->addYear();
            $reminder['start_date'] = $reminder['start_date']->addYear();
            $reminder['end_date'] = $reminder['end_date']->addYear();

            SetReminderJob::dispatch($reminder)->delay(now()->addYear());
        }
    }
}
