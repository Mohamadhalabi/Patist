<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class VerificationController extends Controller
{
    public function verify(Request $request)
    {
        $user = User::where('verification_code', $request->hash)->first();
        if (!$user) {
            return redirect()->away(env('FRONT_URL') . '/login?email_account=' . $user->email.'&status=invalid');
        }
        $token = $user->createToken('auth_token', ['email' => $user->email])->plainTextToken;
        $user->email_verified_at = now();
        $user->is_verified = true;
        $user->save();

        Mail::send('emails.welcomeEmail', ['user_name' => $user->name], function ($message) use ($user) {
            $message->to($user->email, $user->name)->subject('Welcome To Patent.Istanbul Monitor');
        });

        return redirect()->away(env('FRONT_URL') . '/login?email_account=' . $user->email.'&status=verified&token='.$token.'');
    }

    public function resend(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'We can\'t find a user with that e-mail address.'
            ], 200);
        }
        if ($user->email_verified_at) {
            return response()->json([
                'status' => 'error',
                'message' => 'This email has already been verified.'
            ], 200);
        }

        // eğer kullanıcı daha önce doğrulama maili almışsa ve 3 dakika geçmemişse tekrar mail gönderme
        if ($user->verification_code && (time() - strtotime($user->updated_at)) < 180) {
            return response()->json([
                'status' => 'error',
                'message' => 'A verification email has already been sent to the email address you provided during registration. Please check your inbox.'
            ], 200);
        }

        $hash = sha1($user->email . time());
        $user->verification_code = $hash;
        $user->save();
        // Email Verification
        Mail::send('emails.verification', ['hash' => $hash,'email' => $request->email], function ($message) use ($user) {
            $message->to($user->email, $user->name)->subject('Verify your email address');
        });
        return response()->json([
            'status' => 'success',
            'message' => 'A new verification link has been sent to the email address you provided during registration.'
        ]);
    }
}
