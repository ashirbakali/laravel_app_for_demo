<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Lang;
use App\Notifications\PasswordResetLink;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use Validator;

class ForgotPasswordController extends Controller
{

    public function sendResetLinkEmail(Request $request)
    {
        try
            {
            $validate = Validator::make($request->all(), [
                'phone' => 'required|exists:users,phone',
            ]);

            if($validate->fails())
            {
                return $this->sendErrorResponse($validate->errors()->first(), 'User not Found', 404);
            }

            $user = User::where('phone', $request->phone)->first();

            $token = rand(1000, 9999);

            $resetPassword = DB::table('password_resets')->where(['email' => $user->phone])->first();

            if(!$resetPassword)
            {
                DB::table('password_resets')->insert(['email' => $user->phone, 'token' => $token, 'created_at' => Carbon::parse(now())]);
            }else
            {
                DB::table('password_resets')->where(['email' => $user->phone])->update(['token' => $token]);
            }

            if($user->email)
            {
                $user->notify(new PasswordResetLink($token));
            }


            return $this->sendResponse(['token' => $token, 'message' => 'We sent you an 4 Digit OTP'],'OTP Sent Successfully',200);
        }catch(Exception $e)
        {
            return $this->sendErrorResponse($e->getMessage(), 'Something went Wrong', 400);
        }

    }

   public function validateOTP(Request $request)
   {
        try
            {
            $validate = Validator::make($request->all(), [
                'phone' => 'required|exists:password_resets,email',
                'token' => 'required|exists:password_resets,token'
            ]);

            if($validate->fails())
            {
                return $this->sendErrorResponse($validate->errors()->first(), 'Invalid Token or Expired', 404);
            }

            $delete_token = DB::table('password_resets')->where(['email' => $request->phone ,'token' => $request->token])->first();
            if($delete_token) DB::table('password_resets')->where(['email' => $request->phone ,'token' => $request->token])->delete();
            else throw new Exception("OTP not Matched", 1);

            return $this->sendResponse('OTP Matched Successfully', 'OTP Matched', 200);
        }catch(Exception $e)
        {
            return $this->sendErrorResponse($e->getMessage(), 'Something went Wrong', 400);
        }
   }

   public function resetPassword(Request $request)
   {
        try
            {
            $validate = Validator::make($request->all(), [
                'phone' => 'required|exists:users,phone',
                'password' => 'required|min:8',
                'cpass' => 'required|same:password'
            ]);

            if($validate->fails())
            {
                return $this->sendErrorResponse($validate->errors()->first(), 'Something Missing', 404);
            }

            $user = User::where('phone', $request->phone)->first();
            $user->password = Hash::make($request->password);
            $user->save();

            return $this->sendResponse('Password Updated', 'password Changed Successfully', 200);
        }catch(Exception $e)
        {
            return $this->sendErrorResponse($e->getMessage(), 'Something went Wrong', 400);
        }
   }

}
