<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function sendResponse($data, $message = null, $status = 200)
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
            'status' => $status,
        ], $status);
    }

    public function sendErrorResponse($data, $message = null, $status = 400)
    {
        return response()->json([
            'message' => $message,
            'errors' => $data,
            'status' => $status,
        ], $status);
    }

    public function uploadFile($file, $path)
    {
        $fileName = date('Y-m-d').'_'.time().'.'.$file->getClientOriginalExtension();
        // dd($fileName);
        $file->move(public_path($path), $fileName);
        return $path.'/'.$fileName;
    }

    public function userExists(Request $request)
    {
        $request->validate([
            'email' => 'required',
        ]);
        if(strstr($request->email, '@'))
        {
            $user = User::where('email', $request->email)->first();
        }else
        {
            $user = User::where('phone', $request->email)->first();
        }
        if(!$user) return $this->sendErrorResponse([], 'Account not Exists', 404);

        return $this->sendResponse($user, 'Account found', 200);
    }
}
