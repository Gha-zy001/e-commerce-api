<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code')->unique()->index();
            $table->string('type')->default('percentage')->index()->comment('percentage, fixed');
            $table->decimal('value', 10, 2)->comment('Discount value (percentage or fixed amount)');
            $table->decimal('minimum_order', 12, 2)->default(0)->comment('Minimum order amount to apply coupon');
            $table->decimal('maximum_discount', 12, 2)->nullable()->comment('Maximum discount amount');
            $table->unsignedInteger('usage_limit')->nullable()->comment('Total number of times coupon can be used');
            $table->unsignedInteger('usage_per_customer')->default(1)->comment('Number of times each customer can use coupon');
            $table->timestamp('starts_at')->nullable()->index();
            $table->timestamp('expires_at')->nullable()->index();
            $table->boolean('is_active')->default(true)->index();
            $table->boolean('apply_to_shipping')->default(false)->comment('Apply discount to shipping cost');
            $table->json('product_ids')->nullable()->comment('Specific products this coupon applies to');
            $table->json('category_ids')->nullable()->comment('Specific categories this coupon applies to');
            $table->json('brand_ids')->nullable()->comment('Specific brands this coupon applies to');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
