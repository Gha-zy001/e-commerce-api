<?php

namespace Tests\Feature;

use App\Models\Store\Category;
use App\Models\Store\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocalizationTest extends TestCase
{
  use RefreshDatabase;

  public function test_can_set_application_locale_via_header(): void
  {
    $this->getJson('/api/user', [
      'Accept-Language' => 'ar',
    ]);
    $this->assertEquals('ar', app()->getLocale());

    $this->getJson('/api/user', [
      'Accept-Language' => 'en',
    ]);
    $this->assertEquals('en', app()->getLocale());
  }

  public function test_can_create_and_retrieve_translations(): void
  {
    $category = Category::create([
      'name' => 'Electronics',
      'slug' => 'electronics',
    ]);

    app()->setLocale('ar');
    $category->name = 'الإلكترونيات';
    $category->slug = 'electronics-ar';
    $category->save();

    app()->setLocale('en');
    $this->assertEquals('Electronics', $category->name);
    $this->assertEquals('electronics', $category->slug);

    app()->setLocale('ar');
    $this->assertEquals('الإلكترونيات', $category->name);
    $this->assertEquals('electronics-ar', $category->slug);

    app()->setLocale('en');
    $product = Product::create([
      'category_id' => $category->id,
      'name' => 'Phone',
      'description' => 'A smart phone',
      'slug' => 'phone',
    ]);

    app()->setLocale('ar');
    $product->name = 'هاتف';
    $product->description = 'هاتف ذكي';
    $product->slug = 'phone-ar';
    $product->save();

    app()->setLocale('en');
    $this->assertEquals('Phone', $product->name);
    $this->assertEquals('A smart phone', $product->description);

    app()->setLocale('ar');
    $this->assertEquals('هاتف', $product->name);
    $this->assertEquals('هاتف ذكي', $product->description);
  }
}
