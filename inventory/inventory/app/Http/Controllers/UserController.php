<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Models\User;
use Illuminate\Http\Request;
use Mockery\Exception;

class UserController extends Controller
{
   public function userRegistration(Request $request):string
   {
       try {
           User::create([
               'firstName'=>$request->input('firstName'),
               'lastName'=>$request->input('lastName'),
               'email'=>$request->input('email'),
               'mobile'=>$request->input('mobile'),
               'password'=>$request->input('password')
           ]);
           return response()->json([
               'status'=>"success",
               "message"=>"User registration successfully"
           ]);
       }catch (Exception $e){
           return response()->json([
               "status"=>'Failed',
               'message'=>"user create failed"
           ]);
       }

   }

   function UserLogin(Request $request){

      $userCount= User::where('email','=',$request->input('email'))
           ->where('password','=',$request->input('password'))
           ->count();
      if($userCount==1){
            $token=JWTToken::CreateToken($request->input('email'));
          return response()->json([
              "status"=>"success",
              "message"=>"User login success",
              "token"=>$token
          ]);
      }
      else{
            return response()->json([
                "status"=>"failed",
                "message"=>"unauthorized"
            ]);
      }

   }
}
