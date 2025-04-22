<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    /** @use HasFactory<\Database\Factories\ProductVariantFactory> */
    use HasFactory;
    // protected $hidden = ['discount_id'];
    protected $fillable = ['product_id', 'discount_id', 'size', 'color', 'stock', 'price'];

    public function orderItems()
    {
        return $this->hasMany(OrderItems::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }
    public function productImages()
    {
        return $this->hasMany(ProductImage::class);
    }
}
