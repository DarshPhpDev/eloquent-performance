<?php

namespace App\Models;

use App\Models\OrderTracking;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    public $timestamps = false;

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity');
    }

    public function trackings()
    {
        return $this->hasMany(OrderTracking::class);
    }

    protected static function booted(){
        static::created(fn () => Cache::forget('users_orders_report'));
        static::updated(fn () => Cache::forget('users_orders_report'));
        static::deleted(fn () => Cache::forget('users_orders_report'));
    }
}
