<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attribute_value_translations', function (Blueprint $table) {
            $table->uuid('attribute_value_id');
            $table->string('locale', 10);
            $table->string('value');
            $table->timestamps();

            $table->primary(['attribute_value_id', 'locale']);

            $table->foreign('attribute_value_id')
                ->references('id')
                ->on('attribute_values')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attribute_value_translations');
    }
};
