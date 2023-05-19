<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    protected $table = 'categories';
    protected $fillable = [ 'image', 'name','status','user_id','is_archive', 'parent_id'];
    use HasFactory;
    protected $appends = ['childs'];
    protected $hidden = ['user_id'];

    public function getChildsAttribute()
    {
        return Categories::where('parent_id', $this->id)->get();

    }
    public function items(){
        return $this->hasMany(Items::class);
    }

    public function parents()
    {
        return $this->belongsTo(Categories::class, 'parent_id');
    }
}
