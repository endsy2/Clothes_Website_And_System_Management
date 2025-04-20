<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    /** @use HasFactory<\Database\Factories\OrderItemsFactory> */
    use HasFactory;
    protected $fillable = ['order_id', 'product_variant_id', 'quantity', 'amount', 'discount'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
    protected static function booted()
    {
        static::saved(function ($item) {
            $item->order->updateAmount();
        });

        static::deleted(function ($item) {
            $item->order->updateAmount();
        });

        static::created(function ($item) {
            $item->order->updateAmount();
        });
    }
}
