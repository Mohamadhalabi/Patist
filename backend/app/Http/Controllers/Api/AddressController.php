<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    
    /**
     * Lists all countries
     *
     * @return void
     */
    public function country()
    {
        $countries = Country::all();
        return response()->json($countries);
    }
    
    /**
     * List the states of a country
     *
     * @param  mixed $country_id
     * @return void
     */
    public function state($country_id)
    {
        $country = Country::where('id', $country_id)->first();
        $states = $country->states;
        return response()->json($states);
    }
    
    /**
     * List the cities of a state
     *
     * @param  mixed $state_id
     * @return void
     */
    public function city($state_id)
    {
        $state = State::where('id', $state_id)->first();
        $cities = $state->cities;
        return response()->json($cities);
    }
}
