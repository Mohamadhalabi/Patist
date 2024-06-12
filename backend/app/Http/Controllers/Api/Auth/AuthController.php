<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
class AuthController extends BaseController
{
    public function signin(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $authUser = Auth::user();
            $authUser->role = $authUser->roles()->first()->name ?? 'user';
            // Check if user is verified
            if (!$authUser->email_verified_at && !$authUser->is_verified) {
                return $this->sendError('Unverified.', ['error' => 'unverified']);
            }
            $authUser->country = json_decode($authUser->country);
            $authUser->city = json_decode($authUser->city);
            $authUser->state = json_decode($authUser->state);
            $success['token'] =  $authUser->createToken('auth_token', ['email' => $authUser->email])->plainTextToken;
            $success['user'] =  $authUser;

            return $this->sendResponse($success, 'User login successfully.');
        } else {
            return $this->sendError('Unauthorised.', ['error' => 'unauthorized']);
        }
    }

    function loginAfterEmailValidation(Request $request)
    {
        $token = $request->token;
        $email = $request->email;

        $user = User::where('email', $email)->first();
        if (!$user) {
            return $this->sendError('Unauthorised.', ['error' => 'unauthorized']);
        }

        $success['token'] =  $user->createToken('auth_token', ['email' => $user->email])->plainTextToken;
        $success['user'] =  $user;

        return $this->sendResponse($success, 'User login successfully.');
    }

    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error validation', $validator->errors());
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $user->assignRole('client-user');
        $success['token'] =  $user->createToken('auth_token', ['email' => $user->email])->plainTextToken;
        $success['user'] =  $user;

        $hash = sha1($user->email . time());
        $user->verification_code = $hash;

        $user->is_verified = true; // TODO: By default, accounts will be verified until the problem in verification is resolved.

        $user->save();
        // Email Verification
        Mail::send('mails.verification', ['hash' => $hash ,'name' => $request->name,'email' => $request->email], function ($message) use ($user) {
            $message->to($user->email, $user->name)->subject('Welcome to patent.istanbul');
        });

        return $this->sendResponse($success, 'User created successfully.');
    }

    public function firstRegister(Request $request)
    {
        // Check if user is exist
        if(User::where('email', $request->email)->first()){
            return $this->sendError('User already exist.', ['error' => 'user already exist']);
        }
        $user = new User();
        $user->first_register = true;
        $user->email = $request->email;
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->country = $request->country;
        $user->city = $request->city;
        $user->state = $request->state;
        $user->address = $request->address;
        $user->password = substr(md5(uniqid(rand(), true)), 0, 8);
        $hash = sha1($user->email . time());
        $user->verification_code = $hash;
        $user->save();

        // Email e ilk defa hesap açan kullanıcıya gönderilecek ve şifre sıfırlama linki içerecek
        Mail::send('emails.first-register', ['hash' => $hash,'user_name'=>$user->name], function ($message) use ($user) {
            $message->to($user->email, $user->name)->subject('Create your password');
        });
    }

    public function verifyFirstRegister(Request $request)
    {
        $password = $request->password;
        $verification_code = $request->token;

        $user = User::where('verification_code', $verification_code)->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Wrong verification code'
            ], 200);
        }

        $user->password = bcrypt($password);
        $user->verification_code = null;
        $user->is_verified = true;
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Password reset successfully',
            'email' => $user->email
        ], 200);
    }
}
