<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reminder;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CalendarController extends Controller
{
    public function getCalendar($secret_id, $password = null)
    {
        $secret_key = env('APP_SECRET_KEY');
        $id = openssl_decrypt($secret_id, "AES-128-ECB", $secret_key);
        $user = User::find($id);

        if ($secret_id == null) {
            if ($password == null) {
                return response()->json([
                    'message' => 'Please provide secret code',
                    'status' => 401
                ], 401);
            }
        }

        if(!$user){
            return response()->json([
                'message' => 'Please provide valid secret code',
                'status' => 404
            ], 404);
        }
        if ($user->use_calendar_password) {
            if ($password == null) {
                return response()->json([
                    'message' => 'Please provide password',
                    'status' => 401
                ], 401);
            } else {
                if ($password != $user->calendar_password) {
                    return response()->json([
                        'message' => 'Unauthorized',
                        'status' => 401
                    ], 401);
                }
            }
        }

        $timezone = $user->timezone;
        $reminders = Reminder::where('user_id', $user->id)->where('is_active', true)->get();
        $events = [];
        foreach ($reminders as $reminder) {
            foreach ($reminder->fragments as $fragment) {
                $fragment->start_date = Carbon::parse($fragment->start_date)->setTime(9, 0, 0);
                $fragment->end_date = Carbon::parse($fragment->end_date)->setTime(9, 15, 0);
                $event = [
                    'title' => $fragment->title,
                    'description' => $fragment->title,
                    'startDateTime' => Carbon::parse($fragment->start_date)->setTimezone($timezone)->format('Y-m-d H:i:s'),
                    'endDateTime' => Carbon::parse($fragment->end_date)->setTimezone($timezone)->format('Y-m-d H:i:s'),
                    'location' => 'https://patent.istanbul',
                    'alertMessage' => $fragment->title
                ];
                array_push($events, $event);
            }
        }

        $icsContent = "BEGIN:VCALENDAR\r\n";
        $icsContent .= "VERSION:2.0\r\n";
        $icsContent .= "PRODID:-//patent.istanbul//NONSGML//EN\r\n";
        $icsContent .= "CALSCALE:GREGORIAN\r\n";
        // Add custom title
        $icsContent .= "X-WR-CALNAME:Reminders - patent.istanbul\r\n";
        foreach ($events as $eventData) {
            $icsContent .= "BEGIN:VEVENT\r\n";
            $icsContent .= "UID:" . uniqid() . "@patent.istanbul\r\n"; // Use a unique identifier for each event
            $icsContent .= "DTSTAMP:" . gmdate('Ymd\THis\Z') . "\r\n"; // Current UTC time in the format YYYYMMDDTHHMMSSZ
            $icsContent .= "DTSTART:" . gmdate('Ymd\THis\Z', strtotime($eventData['startDateTime'])) . "\r\n";
            $icsContent .= "DTEND:" . gmdate('Ymd\THis\Z', strtotime($eventData['endDateTime'])) . "\r\n";
            $icsContent .= "SUMMARY:" . $eventData['title'] . "\r\n";
            $icsContent .= "DESCRIPTION:" . $eventData['description'] . "\r\n";
            $icsContent .= "LOCATION:" . $eventData['location'] . "\r\n";

            $icsContent .= "BEGIN:VALARM\r\n";
            $icsContent .= "TRIGGER:PT9M\r\n"; // Trigger 9H 30min after the event
            $icsContent .= "ACTION:DISPLAY\r\n";
            $icsContent .= "DESCRIPTION:" . $eventData['alertMessage'] . "\r\n";
            $icsContent .= "END:VALARM\r\n";

            $icsContent .= "END:VEVENT\r\n";
        }

        $icsContent .= "END:VCALENDAR\r\n";

        return response($icsContent)
            ->header('Content-Type', 'text/calendar')
            ->header('Content-Disposition', 'attachment; filename=event.ics');
    }
}
