<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helper;
use App\Notifications\AdminNewRegistration;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        // dd($request->all());
        $this->validator($request->all())->validate();
        if($this->_checkUserType() == 'CLIENT')
        {
            $this->_clientValidation($request->all())->validate();
        }

        if($request->has('insurance_coverage_image'))
        {
            $request['insurance_coverage'] = Helper::file_upload($request, 'insurance_coverage_image', 'client');
        }

        if($request->has('license_image'))
        {
            $request['license'] = Helper::file_upload($request, 'license_image', 'client');
        }

        if($request->has('profile_image'))
        {
            $request['image'] = Helper::file_upload($request, 'profile_image', 'client');
        }

        if($request->has('banner_image'))
        {
            $request['banner_img'] = Helper::file_upload($request, 'banner_image', 'client');
        }
        $user = $this->create($request->all());
        event(new Registered($user));

        // $this->guard()->login($user);

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return $request->wantsJson()
                    ? new JsonResponse([], 201)
                    : redirect($this->redirectPath());
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'same:cpass'],
            'cpass' => ['required', 'string', 'min:8'],
            'phone' => ['required', 'numeric'],
            'country_id' => 'required',
            'state_id' => 'required',
            'city_id' => 'required:',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'type' => $this->_checkUserType(),
            'address' => $data['address']??null,
            'latitude' => $data['latitude']??null,
            'longitude' => $data['longitude']??null,
            'is_admin_approve' => $this->_checkUserType() != 'CLIENT'?'1':'0',
            'image' => $data['image']??null,
            'phone' => $data['phone']??null,
            'insurance_coverage' => $data['insurance_coverage']??null,
            'license' => $data['license']??null,
            'banner_img' => $data['banner_img']??null,
            'country_id' => $data['country_id']??null,
            'state_id' => $data['state_id']??null,
            'city_id' => $data['city_id']??null,
        ]);
    }

    protected function _checkUserType(): String
    {
        return strtoupper(request()->route()->getName());
    }

    protected function registered(Request $request, $user)
    {
        $user->token = $user->createToken('bearer')->accessToken;
        $admin = User::where('type', 'ADMIN')->first();
        $admin->notify(new AdminNewRegistration($user));
        
        return $this->sendResponse($user, 200);
    }

    protected function _clientValidation(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'same:cpass'],
            'cpass' => ['required', 'string', 'min:8'],
            'insurance_coverage_image' => ['required', 'file', 'max:1024', 'mimes:jpeg,png,jpg,gif'],
            'license_image' => ['required', 'file', 'max:1024', 'mimes:jpeg,png,jpg,gif'],
            'profile_image' => ['nullable', 'file', 'max:1024', 'mimes:jpeg,png,jpg,gif'],
            'banner_image' => ['nullable', 'file', 'max:1024', 'mimes:jpeg,png,jpg,gif']
        ]);
    }
}
