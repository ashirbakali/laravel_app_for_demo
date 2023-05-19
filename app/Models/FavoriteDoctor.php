<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavoriteDoctor extends Model
{
    use HasFactory;
    protected $appends = ['doctor'];
    protected $fillable = ['user_id', 'doctor_id'];

    public function getDoctorAttribute()
    {
        return $this->belongsTo(User::class, 'doctor_id')->first();
    }
}
