<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = ['name', 'image'];

    public function attachable(){
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getNameAttribute()
    {
        return $this->user()->first()->name??null;
    }

    public function getImageAttribute()
    {
        return $this->user()->first()->image??null;
    }
}
