<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Appointment extends Model
{
    use HasFactory;

    // protected $appends = ['card'];

    protected $fillable = [
        'category_id',
        'appointment_type',
        'appointment_status',
        'reject_reason',
        'address',
        'latitude',
        'longitude',
        'user_id',
        'vendor_id',
        'status',
        'link',
        'vendor_service_id',
        'time_slot_id',
        'card_id',
        'appointment_datetime',
        'amount',
    ];

    // public function getCardAttribute()
    // {
    //     return $this->belongsTo(Card::class,'card_id')->where('status',1)->first();
    // }

    public function card()
    {
        return $this->belongsTo(Card::class,'card_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function doctor()
    {
        return $this->belongsTo(User::class,'vendor_id');
    }

    public function category()
    {
        return $this->belongsTo(Categories::class,'category_id');
    }
    public function vendor_services()
    {
        return $this->belongsTo(VendorService::class,'vendor_service_id');
    }
}
