<?php

namespace Database\Seeders;

use App\Models\Store\Category;
use App\Models\Store\CategoryTranslation;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
  public function run(): void
  {
    $categories = [
      [
        'en' => ['name' => 'Electronics', 'slug' => 'electronics'],
        'ar' => ['name' => 'إلكترونيات', 'slug' => 'electronics-ar'],
      ],
      [
        'en' => ['name' => 'Clothing', 'slug' => 'clothing'],
        'ar' => ['name' => 'ملابس', 'slug' => 'clothing-ar'],
      ],
      [
        'en' => ['name' => 'Home & Kitchen', 'slug' => 'home-kitchen'],
        'ar' => ['name' => 'منزل ومطبخ', 'slug' => 'home-kitchen-ar'],
      ],
      [
        'en' => ['name' => 'Books', 'slug' => 'books'],
        'ar' => ['name' => 'كتب', 'slug' => 'books-ar'],
      ],
      [
        'en' => ['name' => 'Sports', 'slug' => 'sports'],
        'ar' => ['name' => 'رياضة', 'slug' => 'sports-ar'],
      ],
    ];

    foreach ($categories as $data) {
      $category = Category::create([]);
      CategoryTranslation::create([
        'id' => $category->id,
        'language' => 'en',
        'is_default' => true,
        'name' => $data['en']['name'],
        'slug' => $data['en']['slug'],
      ]);
      CategoryTranslation::create([
        'id' => $category->id,
        'language' => 'ar',
        'is_default' => false,
        'name' => $data['ar']['name'],
        'slug' => $data['ar']['slug'],
      ]);
    }
  }
}
