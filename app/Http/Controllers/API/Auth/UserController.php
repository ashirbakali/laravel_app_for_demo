<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\BankDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helper;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }

    public function user()
    {
        $user = Auth::user();
        return $this->sendResponse($user, 'User Retrived', 200);
    }

    public function updateprofile(Request $request)
    {
        $user = Auth::user();

        if ($request->hasFile('imageFile')) {
            if(!empty($user->image)){
                $file = str_ireplace("storage/app/",'', $user->image);
                if(Storage::exists($file)){
                    Storage::delete($file);
                }
            }

            $request['image'] = Helper::file_upload($request, 'imageFile', 'upload/user');
        }

        $user->update($request->except('imageFile'));
        return $this->sendResponse($user, 'User Profile Update', 200);
    }

    public function addBankDetail(Request $request)
    {
        $user = Auth::user();
        $request['user_id'] = $user->id;
        $bank = BankDetail::create($request->all());
        return $this->sendResponse($bank, 'Bank Detail Successfully added', 200);
    }

    public function userBankDetailList()
    {
        $user = Auth::user();
        return $this->sendResponse($user->bank, 'Retrived Bank Detail', 200);
    }

    public function getNotifications()
    {
        $user = Auth::user();
        return $this->sendResponse($user->unreadNotifications, 'All Unread Notifications', 200);
    }

    public function getNotificationRead($id)
    {
        $notification = auth()->user()->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();

            return $this->sendResponse($notification, 'Read Notification', 200);
        }

        return $this->sendErrorResponse('Notification not Found', 'Read Notification', 404);
    }

    public function readAllNotification()
    {

        Auth::user()->unreadNotifications->markAsRead();

        return $this->sendResponse([], 'Read All Notifications', 200);
    }

    public function changePassword(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required',
        ]);

        if($validate->fails())
        {
            return $this->sendErrorResponse($validate->errors()->first(), 'Something Missing..', 400);
        }
        $user = User::find(Auth::id());
        if(Hash::check($request->old_password, $user->password)){
            $user->update(['password'=> Hash::make($request->new_password)]);
            return $this->sendResponse([], "Password successfully Changed", 200);
        }
        return $this->sendErrorResponse([],"Old Password Not Match",402);
    }

}
