<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipping_methods', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code')->unique()->index()->comment('Unique shipping method code (standard, express, overnight)');
            $table->string('carrier')->nullable()->comment('Shipping carrier name (FedEx, DHL, etc.)');
            $table->boolean('is_active')->default(true)->index();
            $table->integer('sort_order')->default(0)->index();
            $table->decimal('base_cost', 12, 2)->default(0);
            $table->boolean('free_shipping')->default(false);
            $table->decimal('min_order_amount', 12, 2)->nullable()->comment('Minimum order amount for free shipping');
            $table->integer('estimated_delivery_min')->nullable()->comment('Estimated delivery time in days (min)');
            $table->integer('estimated_delivery_max')->nullable()->comment('Estimated delivery time in days (max)');
            $table->json('configuration')->nullable()->comment('Store shipping method configuration');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_methods');
    }
};
