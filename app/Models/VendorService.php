<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VendorService extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['category_id', 'user_id', 'price', 'description', 'experience', 'home_service', 'home_service_price', 'online_consultancy', 'online_consultancy_price','banner_image', 'name'];
    protected $appends = ['ratings'];

    public function getRatingsAttribute()
    {
        return $this->morphMany(Rating::class, 'rateable')->avg('rating');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Categories::class);
    }
}
