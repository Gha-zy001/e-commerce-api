<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('order_id');
            $table->uuid('product_variant_id')->nullable();
            $table->string('product_name')->comment('Snapshot: Product name at time of order');
            $table->string('sku')->comment('Snapshot: Product SKU at time of order');
            $table->string('product_slug')->nullable()->comment('Snapshot: Product slug at time of order');
            $table->string('brand_name')->nullable()->comment('Snapshot: Brand name at time of order');
            $table->decimal('price', 12, 2)->comment('Snapshot: Price at time of order');
            $table->decimal('compare_price', 12, 2)->nullable()->comment('Snapshot: Compare price at time of order');
            $table->unsignedInteger('quantity');
            $table->decimal('discount_amount', 12, 2)->default(0)->comment('Discount applied to this item');
            $table->decimal('tax_amount', 12, 2)->default(0)->comment('Tax applied to this item');
            $table->decimal('total', 12, 2)->comment('Snapshot: Total price for this item');
            $table->json('variant_attributes')->nullable()->comment('Snapshot: Variant attributes at time of order');
            $table->json('customizations')->nullable()->comment('Store any custom product customizations');
            $table->timestamps();

            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->cascadeOnDelete();

            $table->foreign('product_variant_id')
                ->references('id')
                ->on('product_variants')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
