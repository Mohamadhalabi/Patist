<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\ServiceFee;
use Illuminate\Http\Request;

class Price extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $prices = ServiceFee::all();
        return view('dashboard.prices.index',compact('prices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.prices.create');
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
            'currency' => 'required'
        ]);

        ServiceFee::create($request->post());

        return redirect()->route('price.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ServiceFee $price
     * @return \Illuminate\Http\Response
     */
    public function edit(ServiceFee $price)
    {
        return view('dashboard.prices.edit',compact('price'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ServiceFee $price
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ServiceFee $price)
    {
        $request->validate([
            'item' => 'required',
            'amount' => 'required',
            'currency' => 'required'
        ]);

        $price->fill($request->post())->save();

        return redirect()->route('price.index')->with('success','Company Has Been updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ServiceFee  $price
     * @return \Illuminate\Http\Response
     */
    public function destroy(ServiceFee $price)
    {
        $price->delete();
        return redirect()->back();

    }
}
