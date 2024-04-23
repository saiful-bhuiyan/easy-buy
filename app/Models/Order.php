<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = [];

    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function ShippingCharge()
    {
        return $this->belongsTo(ShippingCharge::class,'shipping_id');
    }

    public function OrderItem()
    {
        return $this->hasMany(OrderItem::class);
    }
}
