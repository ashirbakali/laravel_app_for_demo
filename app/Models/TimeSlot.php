<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TimeSlot extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['doctor_availability_id', 'start_time', 'end_time'];

    public function doctor_availability()
    {
        return $this->belongsTo(DoctorAvailability::class, 'doctor_availability_id');
    }
}
