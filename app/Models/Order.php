<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;
    protected $fillable = ['user_id', 'status', 'total_price'];
    public function customer()
    {
        return $this->belongsTo(Customers::class);
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItems::class);
    }
}
