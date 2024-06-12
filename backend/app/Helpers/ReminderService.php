<?php

namespace App\Helpers;

use Carbon\Carbon;

class ReminderService
{
    public $specificDates = [];

    /**
     * getFrequency
     *
     * @param  integer $frequency
     * @param  mixed $frequencyType
     * @return array
     */
    public function getFrequency($frequency, $frequencyType): array
    {
        switch ($frequencyType['value']) {
            case 'days':
                $frequency *= 1;
                break;
            case 'business-days':
                $frequency *= 1;
                break;
            case 'weeks':
                $frequency *= 7;
                break;
            case 'months':
                $frequency *= 30;
                break;
            default:
                $frequency *= 1;
                break;
        }

        return [
            'days' => $frequency,
            'type' => $frequencyType['value']
        ];
    }

    public function specificDatesRequirements($reminderDates = []): array
    {
        if ($this->specificDates != []) {
            // $this->specificDates içerisinden type'ı add olanları alıp reminderDates'e ekleyeceğiz.
            // eğer type'ı remove ise reminderDates içerisinden çıkaracağız.
            foreach ($this->specificDates as $specificDate) {
                if ($specificDate['type'] == 'add') {
                    $reminderDates[] = [
                        'title' => 'Speficic Date Notification',
                        'start_date' => $specificDate['date'],
                        'end_date' => $specificDate['date'],
                        'repeat' => false,
                    ];
                } else {
                    $reminderDates = array_filter($reminderDates, function ($reminderDate) use ($specificDate) {
                        return $reminderDate['start_date'] != $specificDate['date'];
                    });
                }
            }
        }

        return $reminderDates;
    }

    public function getOneTimeReminderDate($startDate): array
    {
        $reminderDates[] = [
            'title' => 'One Time Notification',
            'start_date' => $startDate->format('Y-m-d') . ' 09:00:00',
            'end_date' => $startDate->format('Y-m-d') . ' 09:15:00',
            'repeat' => false,
        ];

        return $this->specificDatesRequirements($reminderDates) ?? [];
    }

    /**
     * Get reminder dates
     *
     * @param  mixed $lastReminderDate
     * @param  mixed $lastDate
     * @param  mixed $frequency
     * @param  mixed $repeat
     * @return array
     */
    public function getReminderDates($lastReminderDate, $lastDate, $frequency, $repeat = false): array
    {
        $counter = 1;
        $reminderDates = [];
        while ($lastReminderDate <= $lastDate) {
            if ($lastReminderDate >= Carbon::now()) {
                $title = ($repeat == true ? 'Next' : $counter . '.') . ' Notification';
                $reminderDates[] = [
                    'title' => $title,
                    'start_date' => $lastReminderDate->format('Y-m-d') . ' 09:00:00',
                    'end_date' => $lastReminderDate->format('Y-m-d') . ' 09:15:00',
                    'repeat' => $repeat,
                ];
                $counter++;
            }
            $lastReminderDate = $lastReminderDate->addDays($frequency['days']);
        }

        return $this->specificDatesRequirements($reminderDates) ?? [];
    }

    public function getReminderDatesWithRepetition($lastReminderDate, $frequency, $repetition, $repeat = false)
    {
        $counter = 1;
        $loop = 1;
        $reminderDates = [];
        while ($loop <= $repetition) {
            if ($lastReminderDate >= Carbon::now()) {
                $reminderDates[] = [
                    'title' => $counter . '. Notification',
                    'start_date' => $lastReminderDate->format('Y-m-d') . ' 09:00:00',
                    'end_date' => $lastReminderDate->format('Y-m-d') . ' 09:15:00',
                    'repeat' => $repeat,
                ];
                $counter++;
            }
            $loop++;
            $lastReminderDate = $lastReminderDate->addDays($frequency);
        }
        return $this->specificDatesRequirements($reminderDates) ?? [];
    }
}
