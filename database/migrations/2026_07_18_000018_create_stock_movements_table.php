<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('product_variant_id');
            $table->string('type')->index()->comment('in, out, adjustment, return');
            $table->unsignedInteger('quantity');
            $table->decimal('unit_cost', 12, 2)->nullable()->comment('Cost per unit at time of movement');
            $table->string('reference_type')->nullable()->comment('order, purchase_order, adjustment');
            $table->uuid('reference_id')->nullable();
            $table->text('notes')->nullable();
            $table->uuid('admin_id')->nullable();
            $table->timestamps();

            $table->foreign('product_variant_id')
                ->references('id')
                ->on('product_variants')
                ->restrictOnDelete();

            $table->foreign('admin_id')
                ->references('id')
                ->on('admins')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
