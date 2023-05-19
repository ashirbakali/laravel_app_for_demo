<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCourse extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'user_id',
        'card_id',
        'amount',
    ];

    protected $appends = ['card', 'course'];

    public function getCardAttribute()
    {
        return $this->belongsTo(Card::class, 'card_id')->first();
    }

    public function getCourseAttribute()
    {
        return $this->belongsTo(Service::class, 'course_id')->first();
    }

    public function course()
    {
        return $this->belongsTo(Service::class, 'course_id');
    }

    public function card()
    {
        return $this->belongsTo(Card::class, 'card_id');
    }
}
