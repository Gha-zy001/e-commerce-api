<?php

namespace Database\Seeders;

use App\Models\Store\Category;
use App\Models\Store\Product;
use App\Models\Store\ProductTranslation;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all()->keyBy(function ($c) {
            return $c->translations->firstWhere('language', 'en')->slug ?? $c->id;
        });

        $products = [
            [
                'category_slug' => 'electronics',
                'is_active' => true,
                'is_featured' => true,
                'stock' => 50,
                'en' => ['name' => 'Wireless Headphones', 'description' => 'Premium noise-cancelling wireless headphones.', 'slug' => 'wireless-headphones'],
                'ar' => ['name' => 'سماعات لاسلكية', 'description' => 'سماعات لاسلكية فاخرة بخاصية إلغاء الضوضاء.', 'slug' => 'wireless-headphones-ar'],
            ],
            [
                'category_slug' => 'electronics',
                'is_active' => true,
                'is_featured' => false,
                'stock' => 30,
                'en' => ['name' => 'Smart Watch', 'description' => 'Feature-rich smartwatch with health tracking.', 'slug' => 'smart-watch'],
                'ar' => ['name' => 'ساعة ذكية', 'description' => 'ساعة ذكية غنية بالميزات مع تتبع الصحة.', 'slug' => 'smart-watch-ar'],
            ],
            [
                'category_slug' => 'clothing',
                'is_active' => true,
                'is_featured' => true,
                'stock' => 100,
                'en' => ['name' => 'Classic T-Shirt', 'description' => 'Comfortable 100% cotton t-shirt.', 'slug' => 'classic-t-shirt'],
                'ar' => ['name' => 'تيشيرت كلاسيكي', 'description' => 'تيشيرت مريح من القطن 100%.', 'slug' => 'classic-t-shirt-ar'],
            ],
            [
                'category_slug' => 'clothing',
                'is_active' => true,
                'is_featured' => false,
                'stock' => 75,
                'en' => ['name' => 'Slim Fit Jeans', 'description' => 'Modern slim fit denim jeans.', 'slug' => 'slim-fit-jeans'],
                'ar' => ['name' => 'جينز سليم فيت', 'description' => 'جينز دنيم سليم فيت عصري.', 'slug' => 'slim-fit-jeans-ar'],
            ],
            [
                'category_slug' => 'home-kitchen',
                'is_active' => true,
                'is_featured' => false,
                'stock' => 20,
                'en' => ['name' => 'Coffee Maker', 'description' => 'Automatic drip coffee maker with timer.', 'slug' => 'coffee-maker'],
                'ar' => ['name' => 'ماكينة قهوة', 'description' => 'ماكينة قهوة تلقائية مع مؤقت.', 'slug' => 'coffee-maker-ar'],
            ],
            [
                'category_slug' => 'books',
                'is_active' => true,
                'is_featured' => true,
                'stock' => 200,
                'en' => ['name' => 'Clean Code', 'description' => 'A handbook of agile software craftsmanship.', 'slug' => 'clean-code'],
                'ar' => ['name' => 'الكود النظيف', 'description' => 'دليل الحرفية البرمجية الرشيقة.', 'slug' => 'clean-code-ar'],
            ],
            [
                'category_slug' => 'sports',
                'is_active' => true,
                'is_featured' => false,
                'stock' => 60,
                'en' => ['name' => 'Yoga Mat', 'description' => 'Non-slip premium yoga mat.', 'slug' => 'yoga-mat'],
                'ar' => ['name' => 'حصيرة يوغا', 'description' => 'حصيرة يوغا فاخرة مضادة للانزلاق.', 'slug' => 'yoga-mat-ar'],
            ],
        ];

        foreach ($products as $data) {
            $category = $categories[$data['category_slug']] ?? Category::first();

            $product = Product::create([
                'category_id' => $category->id,
                'is_active' => $data['is_active'],
                'is_featured' => $data['is_featured'],
                'stock' => $data['stock'],
            ]);

            ProductTranslation::create([
                'id' => $product->id,
                'language' => 'en',
                'is_default' => true,
                'name' => $data['en']['name'],
                'description' => $data['en']['description'],
                'slug' => $data['en']['slug'],
            ]);

            ProductTranslation::create([
                'id' => $product->id,
                'language' => 'ar',
                'is_default' => false,
                'name' => $data['ar']['name'],
                'description' => $data['ar']['description'],
                'slug' => $data['ar']['slug'],
            ]);
        }
    }
}
