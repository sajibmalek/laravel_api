<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Mail\OTPMail;
use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Mockery\Exception;
use Nette\Utils\Random;

class UserController extends Controller
{
    function userRegistration(Request $request): string
    {
        try {
            User::create([
                'firstName' => $request->input('firstName'),
                'lastName' => $request->input('lastName'),
                'email' => $request->input('email'),
                'mobile' => $request->input('mobile'),
                'password' => $request->input('password')
            ]);
            return response()->json([
                'status' => "success",
                "message" => "User registration successfully"
            ]);
        } catch (Exception $e) {
            return response()->json([
                "status" => 'Failed',
                'message' => "user create failed"
            ]);
        }

    }

    function UserLogin(Request $request)
    {

        $userCount = User::where('email', '=', $request->input('email'))
            ->where('password', '=', $request->input('password'))
            ->count();
        if ($userCount == 1) {
            $token = JWTToken::CreateToken($request->input('email'));
            return response()->json([
                "status" => "success",
                "message" => "User login success",
                "token" => $token
            ]);
        } else {
            return response()->json([
                "status" => "failed",
                "message" => "unauthorized"
            ]);
        }

    }

    function EmailVerify(Request $request)
    {

        try {
            $mail = $request->input('email');
            $otp = rand(1000, 9999);
            $count = User::where('email', '=', $mail)->count();

            if ($count == 1) {
                Mail::to($mail)->send(new OTPMail($otp));
                User::where('email', '=', $mail)->update(['top' => $otp]);
                return response()->json([
                    "status" => "success",
                    "message" => "OTP send successfully"
                ]);
            } else {
                return response()->json([
                    "status" => "failed",
                    "message" => "Invalid Email"
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                "status" => "failed",
                "message" => "Something went wrong"
            ]);
        }


    }
}
