<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Mail\Invoice;
use App\Models\Renewal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Workflow\WorkflowStub;

class RenewalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth('sanctum')->user();
        // if user roles is admin
        if($user->roles == 'admin'){
            $renewals = Renewal::with('user','reminder')->get();
        }
        else{
            $renewals = Renewal::where('user_id', $user->id)->with('user','reminder')->get();
        }

        return response()->json([
            'renewals' => $renewals,
            'status' => 200
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $renewal = new Renewal();
        return response()->json([
            'renewal' => $request->all(),
            'status' => 200
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Renewal  $renewal
     * @return \Illuminate\Http\Response
     */
    public function show(Renewal $renewal)
    {
        // Format numbers
        $renewal->official_fee_eur = number_format($renewal->official_fee_eur, 2, '.', ',');
        $renewal->official_fee_original = number_format($renewal->official_fee_original, 2, '.', ',');
        $renewal->agent_fee_eur = number_format($renewal->agent_fee_eur, 2, '.', ',');
        $renewal->agent_fee_original = number_format($renewal->agent_fee_original, 2, '.', ',');
        $renewal->service_fee_eur = number_format($renewal->service_fee_eur, 2, '.', ',');
        $renewal->total_amount_eur = number_format($renewal->total_amount_eur, 2, '.', ',');
        return response()->json([
            'renewal' => $renewal,
            'status' => 200
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Renewal  $renewal
     * @return \Illuminate\Http\Response
     */
    public function edit(Renewal $renewal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Renewal  $renewal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Renewal $renewal)
    {
        $type = $request->type;
        $value = $request->value;

        // is_completed
        if($type == 'complete-renewal')
        {
            $renewal->is_completed = true;
            $renewal->save();

            // $workflow = WorkflowStub::load($renewal->workflow_id);
            // $workflow->renewalApproved();
        }

        // is_payment
        else if($type == 'complete-payment')
        {
            $renewal->is_payment = true;
            $renewal->save();
        }

        // is_approved
        else if($type == 'admin-approve')
        {
            if($value == true){
                $renewal->is_approved = true;
                $renewal->save();
            }
            else{
                // delete renewal
                $renewal->delete();
            }
        }

        // error
        else{
            return response()->json([
                'status' => 400,
                'message' => 'undefined type'
            ]);
        }

        if($renewal->is_approved == true && $renewal->status == 'pending'){
            $workflow = WorkflowStub::load($renewal->workflow_id);
            $workflow->output('renewal process approved');
            $workflow->beforeRenewalApproved();
        }

        return response()->json([
            'status' => 200,
            'message' => 'process completed'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Renewal  $renewal
     * @return \Illuminate\Http\Response
     */
    public function destroy(Renewal $renewal)
    {
        //
    }

    public function calendar()
    {
        $user = auth('sanctum')->user() ?? User::find(1);
        $secret_key = env('APP_SECRET_KEY');
        $secret_id = openssl_encrypt($user->id, "AES-128-ECB", $secret_key);

        if($user->use_calendar_password) $link = 'https://api.patent.istanbul/api/v1/calendar/'.$secret_id. '/'. $user->calendar_password;
        else $link = 'https://api.patent.istanbul/api/v1/calendar/'.$secret_id;

        return response()->json([
            'link' => $link,
            'status' => 200
        ]);
    }
}
