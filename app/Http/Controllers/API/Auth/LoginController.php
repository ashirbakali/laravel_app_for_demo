<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated(Request $request, $user)
    {
        $user->token = $user->createToken('bearer')->accessToken;

        return $this->sendResponse($user, 200);
    }

    protected function _checkUser($value){
        $query = User::where($this->username(),$value)->where("status",1)->first();
        if(!empty($query) && ($query->type === "CLIENT" or $query->type === "USER")){
            return true;
        }
        return false;
    }

    protected function validateLogin(Request $request)
    {
        Validator::extend('user_validator', function ($attribute, $value) {
            return $this->_checkUser($value);
        });


        $request->validate([
            $this->username() => [
                'required',
                'exists:users',
                'user_validator',
            ],
            'password' => 'required'
        ],[
            'user_validator' => 'Your account is no longer have access'
        ]);
    }

    public function login(Request $request)
    {
        if(!strstr($request->email, '@'))
        {
            $user = User::where('phone', $request->email)->first();
            if(!$user) return $this->sendErrorResponse('Email/Phone is Incorrect', 'Invalid Email/Phone', 400);
            $request['email'] = $user->email;
        }
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }


        if ($this->attemptLogin($request)) {
            $user = Auth::user();
            if($user->type == 'ADMIN'){
                Auth::logout();

                return $this->sendErrorResponse('Invalid Request','You\'r not Allow to Login Here', 400);
                }
            $user->latitude = $request->latitude??$user->latitude;
            $user->longitude = $request->longitude??$user->longitude;
            $user->save();
            $this->clearLoginAttempts($request);
            return $this->authenticated($request, Auth::user());
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        if(strstr(request()->email, '@')) return 'email';

        return 'phone';

    }
}
