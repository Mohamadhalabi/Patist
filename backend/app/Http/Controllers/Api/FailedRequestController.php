<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FailedRequest;
use Illuminate\Http\Request;

class FailedRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'status' => 'success',
            'requests' => FailedRequest::with('user')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_id = auth('sanctum')->user()->id;
        $failedRequest = new FailedRequest();
        $failedRequest->user_id = $user_id;
        $failedRequest->number = $request->number;
        $failedRequest->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Request saved successfully',
            'failedRequest' => $failedRequest,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FailedRequest  $failedRequest
     * @return \Illuminate\Http\Response
     */
    public function show(FailedRequest $failedRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FailedRequest  $failedRequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FailedRequest $failedRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FailedRequest  $failedRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(FailedRequest $failedRequest)
    {
        $failedRequest->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Request deleted successfully',
        ]);
    }
}
