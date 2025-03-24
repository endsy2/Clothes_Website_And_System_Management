<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;  // ✅ Ensures factory() method is available

    protected $fillable = ['product_variant_id', 'images'];

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
}
