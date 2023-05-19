<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helpers\Helper;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    protected $appends = ['mobile', 'services', 'service_count','country','state','city','favorite_doctor'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'image',
        'bio',
        'type',
        'phone',
        'address',
        'latitude',
        'longitude',
        'status',
        'is_archive',
        'is_admin_approve',
        'license',
        'banner_img',
        'insurance_coverage',
        'state_id',
        'city_id',
        'country_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];



    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function getMobileAttribute()
    {
        return Helper::phoneNumberFormat($this->phone);
    }
    public function getFavoriteDoctorAttribute()
    {
        return $this->hasMany(FavoriteDoctor::class,'user_id')->get();
    }


    public function getServicesAttribute()
    {
        return $this->hasMany(VendorService::class)->get();
    }

    public function getCountryAttribute()
    {
        return $this->belongsTo(Country::class,'country_id')->select('id','name')->first();
    }
    public function getStateAttribute()
    {
        return $this->belongsTo(State::class,'state_id')->select('id','name')->first();
    }
    public function getCityAttribute()
    {
        return $this->belongsTo(City::class,'city_id')->select('id','name')->first();
    }

    public function getServiceCountAttribute()
    {
        return $this->hasMany(VendorService::class)->count();
    }

    public function Addedservices()
    {
        return $this->hasMany(VendorService::class);
    }

    public function courses()
    {
        return $this->hasMany(Service::class, 'user_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'vendor_id');
    }

    public function userAppointments()
    {
        return $this->hasMany(Appointment::class, 'user_id');
    }

    public function availability()
    {
        return $this->hasMany(DoctorAvailability::class);
    }

    public function rating()
    {
        return $this->morphMany(Rating::class, 'rateable')->avg('rating');
    }

    public function ratings()
    {
        return $this->morphMany(Rating::class, 'rateable');
    }

    public function bank()
    {
        return $this->hasMany(BankDetail::class, 'user_id');
    }

    public function complains()
    {
        return $this->hasMany(Complain::class, 'user_id');
    }

}
