<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;

class SendMailController extends Controller
{
    /**
     * Send mail
     *
     * @param  mixed $request
     * @return void
     */
    public function sendMail(Request $request)
    {
        App::setLocale($request->language);
        try {
            Mail::send('mails.orderEmail', $request->all(), function ($message) use ($request) {
                $patentNumber = $request->patentNumber;
                $type = $request->type;

                $message->to($request->email, $request->name)->bcc('info@marka.legal','Marka.Legal')->subject($type . ' quote for patent number ' . $patentNumber);
                $message->from("info@patent.istanbul", "Patent.Istanbul");
            });
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }

        return response()->json([
            'code' => 200,
            'message' => 'Mail sent successfully'
        ], 200);
    }
    public function sendInstruction(Request $request)
    {
        try {
            Mail::send('mails.instructionEmail', $request->all(), function ($message) use ($request) {
                $message->to($request->email, $request->name)->bcc('info@marka.legal','Marka.Legal')->subject("Instruction received");
                $message->from("info@patent.istanbul", "Patent.Istanbul");
            });
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }

        return response()->json([
            'code' => 200,
            'message' => 'Mail sent successfully'
        ], 200);
    }
}
