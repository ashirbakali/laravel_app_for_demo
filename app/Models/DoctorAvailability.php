<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorAvailability extends Model
{
    use HasFactory;

    protected $appends = ['time_slots'];
    protected $fillable = ['day', 'start_time', 'end_time', 'type', 'gap_count','status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getTimeSlotsAttribute()
    {
        return $this->hasMany(TimeSlot::class)->get();
    }

    public function slots()
    {
        return $this->hasMany(TimeSlot::class, 'doctor_availability_id');
    }
}
