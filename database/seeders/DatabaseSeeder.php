<?php

namespace Database\Seeders;

use App\Models\Store\Category;
use App\Models\Store\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  use WithoutModelEvents;

  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    // Call RoleSeeder to create Spatie roles
    // $this->call(RoleSeeder::class);
    $this->call(ProductSeeder::class);
    // $this->call(ProductSeeder::class);
// Category::factory()->create([
//   'name' => 'Test Category',
//   'slug' => 'test-category',
// ]);
    // User::factory()->create([
    //   'name' => 'Test User',
    //   'email' => 'test@example.com',
    // ]);
  }
}
