<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    /** @use HasFactory<\Database\Factories\DiscountFactory> */
    use HasFactory;
    protected $fillable = ['product_variant_id', 'discount', 'start', 'end'];

    public function productVariant()
    {
        return $this->hasMany(ProductVariant::class);
    }
}
