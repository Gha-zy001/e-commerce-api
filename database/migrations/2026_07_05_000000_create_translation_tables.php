<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::table('products', function (Blueprint $table) {
      $table->dropUnique(['slug']);
      $table->dropColumn(['name', 'description', 'slug']);
    });

    Schema::table('categories', function (Blueprint $table) {
      $table->dropUnique(['slug']);
      $table->dropColumn(['name', 'slug']);
    });

    Schema::create('product_translations', function (Blueprint $table) {
      $table->foreignId('id')->constrained('products')->cascadeOnDelete();
      $table->string('language');
      $table->boolean('is_default')->default(false);
      $table->string('name');
      $table->text('description')->nullable();
      $table->string('slug');
      $table->primary(['id', 'language']);
      $table->timestamps();
    });

    Schema::create('category_translations', function (Blueprint $table) {
      $table->foreignId('id')->constrained('categories')->cascadeOnDelete();
      $table->string('language');
      $table->boolean('is_default')->default(false);
      $table->string('name');
      $table->string('slug');
      $table->primary(['id', 'language']);
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('category_translations');
    Schema::dropIfExists('product_translations');

    Schema::table('categories', function (Blueprint $table) {
      $table->string('name');
      $table->string('slug')->unique();
    });

    Schema::table('products', function (Blueprint $table) {
      $table->string('name');
      $table->text('description')->nullable();
      $table->string('slug')->unique();
    });
  }
};
