<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('payment_id');
            $table->string('type')->index()->comment('authorization, capture, refund, void');
            $table->string('gateway')->comment('Payment gateway name (stripe, paypal, etc.)');
            $table->string('gateway_transaction_id')->nullable()->index();
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('EGP');
            $table->string('status')->default('pending')->index()->comment('pending, completed, failed, declined');
            $table->json('request_data')->nullable()->comment('Store request data sent to gateway');
            $table->json('response_data')->nullable()->comment('Store complete response from gateway');
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->foreign('payment_id')
                ->references('id')
                ->on('payments')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
