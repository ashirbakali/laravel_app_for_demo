<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Service extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'type',
        'desription',
        'what_we_learn',
        'charges',
        'total_duration',
        'category_id',
        'banner_image',
    ];

    protected $casts = ['date_format:H:i:s' => 'total_duration'];

    protected $appends = ['user_info', 'links', 'ratings', 'enrollment_counts'];

    public function getRatingsAttribute()
    {
        return $this->morphMany(Rating::class, 'rateable')->avg('rating');
    }

    public function ratings()
    {
        return $this->morphMany(Rating::class, 'rateable');
    }

    public function getEnrollmentCountsAttribute()
    {
        return 0;
    }

    public function getUserInfoAttribute()
    {
        return $this->belongsTo(User::class,'user_id')->first();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function serviceLink()
    {
        return $this->hasMany(ServiceLink::class, 'service_id');
    }

    public function getLinksAttribute()
    {
        return $this->serviceLink()->get();
    }

    public function getTotalDurationAttribute()
    {
        return Carbon::createFromTimeStamp(strtotime($this->attributes['total_duration']))->format('H:i:s');
    }

    public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }
}
