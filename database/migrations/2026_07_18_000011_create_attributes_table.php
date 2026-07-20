<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attributes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code')->unique()->index()->comment('Unique attribute code (color, size, storage)');
            $table->string('type')->default('text')->index()->comment('text, color, select, multiselect, number');
            $table->boolean('is_filterable')->default(false)->index()->comment('Can be used as filter in catalog');
            $table->boolean('is_visible')->default(true)->index()->comment('Visible on product page');
            $table->boolean('is_required')->default(false)->comment('Required for product variants');
            $table->integer('sort_order')->default(0)->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attributes');
    }
};
