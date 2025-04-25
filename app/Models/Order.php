<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;
    protected $fillable = ['customer_id', 'pay_method', 'status', 'amount'];
    public function customer()
    {
        return $this->belongsTo(Customers::class);
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItems::class);
    }
    public function updateAmount()
    {
        $total = $this->orderItems->sum(function ($item) {
            return ($item->amount - $item->discount) * $item->quantity;
        });

        $this->update(['amount' => $total]);
    }
}
