<?php

use App\ProductType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->enum('productType', array_column(ProductType::cases(), 'value'))->default(ProductType::Men->value);
            $table->foreignIdFor(\App\Models\Category::class);
            $table->foreignIdFor(\App\Models\Brand::class);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
