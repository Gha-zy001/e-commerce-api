<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipping_method_translations', function (Blueprint $table) {
            $table->uuid('shipping_method_id');
            $table->string('locale', 10);
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->primary(['shipping_method_id', 'locale']);

            $table->foreign('shipping_method_id')
                ->references('id')
                ->on('shipping_methods')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_method_translations');
    }
};
