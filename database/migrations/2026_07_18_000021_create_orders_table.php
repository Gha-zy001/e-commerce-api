<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->nullable();
            $table->uuid('customer_address_id')->nullable();
            $table->uuid('shipping_method_id')->nullable();
            $table->uuid('coupon_id')->nullable();
            $table->string('order_number')->unique()->index();
            $table->string('status')->default('pending')->index()->comment('pending, confirmed, processing, shipped, delivered, cancelled, refunded');
            $table->decimal('subtotal', 12, 2);
            $table->decimal('shipping_amount', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('total', 12, 2);
            $table->string('currency', 3)->default('EGP')->comment('ISO 4217 currency code');
            $table->string('payment_status')->default('pending')->index()->comment('pending, paid, partially_paid, refunded, failed');
            $table->string('shipping_status')->default('pending')->index()->comment('pending, processing, shipped, delivered, returned');
            $table->text('notes')->nullable();
            $table->json('shipping_data')->nullable()->comment('Store shipping-specific data');
            $table->json('billing_data')->nullable()->comment('Store billing-specific data');
            $table->uuid('admin_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->foreign('customer_address_id')
                ->references('id')
                ->on('customer_addresses')
                ->nullOnDelete();

            $table->foreign('admin_id')
                ->references('id')
                ->on('admins')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
