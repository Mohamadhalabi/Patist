<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Currency;
use App\Http\Controllers\Controller;
use App\Models\AnnuityFee;
use App\Models\OfficialFee;
use App\Models\ServiceFee;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class ServiceFeeController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fees = ServiceFee::all();
        return  $this->sendResponse($fees, 'Service Fees retrieved successfully.');
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
            'item' => 'required',
            'amount' => 'required',
        ]);
        $request->slug = Str::slug($request->item);
        $fee = ServiceFee::create($request->all());
        return $this->sendResponse($fee, 'Service Fee created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ServiceFee  $serviceFee
     * @return \Illuminate\Http\Response
     */
    public function show($slug, $year=2)
    {
        $fee = ServiceFee::where('slug', $slug)->first();
        $euro = Currency::getCurrency();

        if($slug == 'ep-validation') $code = '1.0.1.45';
        else if($slug == 'pct-entry') $code = '1.0.1.41';
        else if($slug == 'patent-annuity') {
            $code = '1.0.1.'. 20+$year;
        }
        else return response()->json(['message' => 'Not Found'], 404);
        $officialFee = OfficialFee::where('code', $code)->first();
        $officialFee['amount'] = AnnuityFee::where('official_fee_id',$officialFee->id)->where('year', date('Y'))->first()->amount;
        $serviceFee['officialFee'] = $officialFee;
        $serviceFee['serviceFee'] = $fee;
        $serviceFee['euro'] = number_format($euro,2);

        if(isset($euro) || empty($euro))
        {
            $total = $officialFee->amount / $euro + $fee->amount;
        }
        else $total = $officialFee->EUR + $fee->amount;

        $serviceFee['total'] = number_format($total,2);

        return $this->sendResponse(['fee' => $serviceFee], 'Service Fee retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ServiceFee  $serviceFee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ServiceFee $serviceFee)
    {
        $request->validate([
            'item' => 'required',
            'amount' => 'required'
        ]);
        $request->slug = Str::slug($request->item);
        $fee = $serviceFee->update($request->all());
        return $this->sendResponse($fee, 'Service Fee updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ServiceFee  $serviceFee
     * @return \Illuminate\Http\Response
     */
    public function destroy(ServiceFee $serviceFee)
    {
        $fee = $serviceFee->delete();
    }
}
