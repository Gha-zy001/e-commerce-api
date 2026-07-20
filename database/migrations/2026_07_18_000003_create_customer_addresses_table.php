<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('label')->comment('home, work, other');
            $table->string('country', 2)->index()->comment('ISO 3166-1 alpha-2 country code');
            $table->string('governorate')->nullable();
            $table->string('city');
            $table->string('district')->nullable();
            $table->string('street');
            $table->string('building')->nullable();
            $table->string('floor')->nullable();
            $table->string('apartment')->nullable();
            $table->string('postal_code')->nullable();
            $table->decimal('latitude', 10, 8)->nullable()->comment('GPS latitude for location services');
            $table->decimal('longitude', 11, 8)->nullable()->comment('GPS longitude for location services');
            $table->boolean('is_default')->default(false);
            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_addresses');
    }
};
