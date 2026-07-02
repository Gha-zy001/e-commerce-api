<?php

namespace Database\Seeders;

use App\Models\Store\Product;
use App\Models\Store\ProductVariant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {

    Product::factory()
      ->count(10)
      ->has(ProductVariant::factory()->count(3), 'variants')
      ->create();
  }
}
