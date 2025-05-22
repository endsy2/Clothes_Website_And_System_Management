<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    /** @use HasFactory<\Database\Factories\DiscountFactory> */
    use HasFactory;
    protected $fillable = ['discount_name', 'discount', 'start_date', 'end_date'];

    public function productVariant()
    {
        return $this->hasMany(ProductVariant::class);
    }
}
