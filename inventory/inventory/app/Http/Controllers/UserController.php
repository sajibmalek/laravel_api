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

    function UserLogin(Request $request):string
    {
        try {
            $userCount = User::where('email', '=', $request->input('email'))
                ->where('password', '=', $request->input('password'))
                ->count();
            if ($userCount == 1) {
                $token = JWTToken::CreateToken($request->input('email'));
                return response()->json([
                    "status" => "success",
                    "message" => "User login success",
                    "token" => $token
                ],200);
            } else {
                return response()->json([
                    "status" => "failed",
                    "message" => "unauthorized"
                ],401);
            }
        }catch (Exception $e){
            return response()->json([
                "status" => "failed",
                "message" => "Something went wrong"
            ],404);
        }



    }

    function EmailVerify(Request $request):string
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
            ],404);
        }


    }

    function VerifyOTP(Request $request):string{

        try {
            $email = $request->input('email');
            $otp = $request->input('top');
            $count= User::where('email', '=', $email)
                ->where('top', '=', $otp)->count();
            if($count==1){
                User::where('email', '=', $email)->update(['top' => '0']);

                $token = JWTToken::CreateVerifyOTP($request->input('email'));
                return response()->json([
                    "status" => "success",
                    "message" => "OTP Verify successfully",
                    "token" => $token
                ]);
            }
            else{
                return response()->json([
                    "status" => "failed",
                    "message" => "Invalid OTP"
                ]);
            }
        }catch (Exception $e){
            return response()->json([
                "status" => "failed",
                "message" => "Something went wrong"

            ]);
        }


    }

    function ResetPassword(Request $request):string{
        try {
            $email = $request->header('email');
            $password = $request->header('password');
            User::where('email', '=', $email)->update(['password' => $password]);

            return response()->json([
                "status" => "success",
                "message" => "Password reset successfully"
            ]);
        }catch (Exception $exception){
            return response()->json([
                "status" => "failed",
                "message" => "Something went wrong"
            ]);
        }

    }
}
