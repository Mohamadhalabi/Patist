<?php

namespace App\Http\Controllers\Api\Renewal;

use App\Http\Controllers\Controller;
use App\Models\Reminder;
use App\Models\ReminderFragment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Workflow\WorkflowStub;

class ReminderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reminders = Reminder::with('renewal', 'user','fragments')->get();

        return response()->json($reminders);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return response()->json([
            "message" => "store",
            "request" => $request->all()
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Reminder  $reminder
     * @return \Illuminate\Http\Response
     */
    public function show(Reminder $reminder)
    {
        $reminder->teams = json_decode($reminder->teams);
        $reminder->emails = json_decode($reminder->emails);
        return response()->json($reminder->load('renewal', 'user', 'email_history', 'fragments'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Reminder  $reminder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reminder $reminder)
    {
        $status = $request->status;
        $data = $request['data'];
        // Update workflow status
        if($status == 'update'){
            $reminder->is_active = $request->is_active;
            $reminder->save();

            return response()->json([
                "message" => "Reminder updated"
            ]);
        }

        $workflow = WorkflowStub::load($reminder->workflow_id);
        if ($status == 'RNWo.1.1') {
            $workflow->setStatus($status, true);
        } else if ($status == 'RNWo.1.2') {
            if($data['confirmToggle']){
                $workflow->setStatus($status, true);
            }else{
                $reminder->workflow->delete();
                $reminder->renewal->delete();
                $reminder->delete();
            }
        } else if($status == 'RNWo.2.1') {
            $workflow->setStatus($status, true);
            $workflow->setStatus('RNWo.1.2', true);
            $workflow->setStatus('RNWo.2.2', true);
            $reminder->status = 'RNWo.2.3';
            $reminder->save();
        } else if($status == 'RNWo.2.2') {
            $workflow->setStatus($status, true);
            $reminder->renewal->is_payment = true;
            $reminder->renewal->save();
        } else if($status == 'RNWo.2.3') {
            $workflow->setStatus($status, true);
            $workflow->setStatus('RNWo.1.2', true);
            $workflow->setStatus('RNWo.2.2', true);
            $workflow->setStatus('RNWo.2.3', true);
        } else if($status == 'RNWo.3') {
            $workflow->setStatus($status, true);
        } else if($status == 'RNWo.4') {
            $workflow->setStatus($status, true);
        } else if($status = 'updateEmail'){
            $reminder->send_email = $data['send_email'];
            $reminder->save();
        }
        else{
            return "else";
        }

        return "ok";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Reminder  $reminder
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reminder $reminder)
    {
        $reminder->delete();
        return response()->json([
            "message" => "Reminder deleted",
        ]);
    }
    public function addFragment(Request $request, $reminder_id)
    {
        $fragment = new ReminderFragment();
        $fragment->reminder_id = $reminder_id;
        $fragment->title = "Custom Fragment";
        $fragment->start_date = $request->start_date;
        $fragment->end_date = $request->start_date;
        $fragment->is_completed = false;
        $fragment->save();

        return response()->json([
            "status" => "success",
            "message" => "Fragment added",
            "fragment" => $fragment
        ]);
    }

    public function deleteFragment($reminder_id, $fragment_id){
        ReminderFragment::where('reminder_id', $reminder_id)->where('id', $fragment_id)->delete();

        return response()->json([
            "status" => "success",
            "message" => "Fragment deleted",
            "fragments" => Reminder::find($reminder_id)->fragments
        ]);
    }

}
