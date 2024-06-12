<?php

namespace App\Http\Controllers;

use DateTimeZone;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = auth('sanctum')->user();

        $profile = $request['profile'];
        if($profile['name'] != $user->name) $user->name = $profile['name'];
        if($profile['email'] != $user->email) $user->email = $profile['email'];
        if($profile['phone'] != $user->phone) $user->phone = $profile['phone'];
        if($profile['address'] != $user->address) $user->address = $profile['address'];
        if($profile['country'] != $user->country) $user->country = $profile['country'];
        if($profile['state'] != $user->state) $user->state = $profile['state'];
        if($profile['city'] != $user->city) $user->city = $profile['city'];
        if($profile['timezone'] != $user->timezone) $user->timezone = $profile['timezone'];
        if($profile['use_calendar_password'] != $user->use_calendar_password) $user->use_calendar_password = $profile['use_calendar_password'];
        if($profile['calendar_password'] != $user->calendar_password) $user->calendar_password = $profile['calendar_password'];

        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Profile updated successfully',
            'user' => $user
        ], 200);
    }

    public function timezones()
    {
        $timezones = DateTimeZone::listIdentifiers(DateTimeZone::ALL);

        return response()->json([
            'status' => 'success',
            'timezones' => $timezones
        ], 200);
    }
}
