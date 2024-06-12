<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OfficialFee;
use Illuminate\Http\Request;

class OfficialFeeController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fees = OfficialFee::all();
        return  $this->sendResponse($fees, 'Official Fees retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required',
            'type' => 'required',
            'item' => 'required',
        ]);
        $fee = OfficialFee::create($request->all());
        return $this->sendResponse($fee, 'Official Fee created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OfficialFee  $officialFee
     * @return \Illuminate\Http\Response
     */
    public function show(OfficialFee $officialFee)
    {
        return $this->sendResponse($officialFee, 'Official Fee retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OfficialFee  $officialFee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OfficialFee $officialFee)
    {
        $request->validate([
            'code' => 'required',
            'type' => 'required',
            'item' => 'required',
        ]);
        $officialFee->update($request->all());
        return $this->sendResponse($officialFee, 'Official Fee updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OfficialFee  $officialFee
     * @return \Illuminate\Http\Response
     */
    public function destroy(OfficialFee $officialFee)
    {
        $officialFee->delete();
        return $this->sendResponse($officialFee, 'Official Fee deleted successfully.');
    }
}
