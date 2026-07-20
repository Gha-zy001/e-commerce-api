<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('product_id');
            $table->string('sku')->unique()->index();
            $table->string('barcode')->nullable()->unique();
            $table->decimal('price', 12, 2);
            $table->decimal('compare_price', 12, 2)->nullable()->comment('Original price before discount');
            $table->decimal('cost', 12, 2)->nullable()->comment('Cost price for inventory valuation');
            $table->unsignedInteger('stock')->default(0);
            $table->unsignedInteger('low_stock_threshold')->default(5);
            $table->decimal('weight', 10, 3)->nullable()->comment('Weight in kilograms');
            $table->decimal('length', 10, 3)->nullable()->comment('Length in centimeters');
            $table->decimal('width', 10, 3)->nullable()->comment('Width in centimeters');
            $table->decimal('height', 10, 3)->nullable()->comment('Height in centimeters');
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
