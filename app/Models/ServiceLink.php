<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceLink extends Model
{
    use HasFactory;

    protected $fillable = ['service_id', 'title', 'link'];

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
