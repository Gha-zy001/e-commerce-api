<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('order_id');
            $table->uuid('payment_method_id');
            $table->string('transaction_id')->nullable()->index()->comment('Gateway transaction reference');
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('EGP')->comment('ISO 4217 currency code');
            $table->string('status')->default('pending')->index()->comment('pending, completed, failed, refunded, partially_refunded');
            $table->string('payment_type')->default('full')->comment('full, partial, deposit');
            $table->timestamp('paid_at')->nullable()->index();
            $table->json('gateway_response')->nullable()->comment('Store complete gateway response');
            $table->json('metadata')->nullable()->comment('Store additional payment metadata');
            $table->timestamps();

            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->restrictOnDelete();

            $table->foreign('payment_method_id')
                ->references('id')
                ->on('payment_methods')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
