<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Color extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = [];

    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function ProductColor()
    {
        return $this->hasMany(ProductColor::class,'color_id');
    }
}
