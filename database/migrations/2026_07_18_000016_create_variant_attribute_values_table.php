<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('variant_attribute_values', function (Blueprint $table) {
            $table->uuid('product_variant_id');
            $table->uuid('attribute_value_id');
            $table->timestamps();

            $table->primary(['product_variant_id', 'attribute_value_id']);

            $table->foreign('product_variant_id')
                ->references('id')
                ->on('product_variants')
                ->cascadeOnDelete();

            $table->foreign('attribute_value_id')
                ->references('id')
                ->on('attribute_values')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('variant_attribute_values');
    }
};
