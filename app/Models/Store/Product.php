<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
  use HasFactory;
  protected $fillable = [
    'category_id',
    'name',
    'description',
    'slug',
    'is_active',
    'is_featured',
  ];

  protected $casts = [
    'is_active' => 'boolean',
    'is_featured' => 'boolean',
  ];

  public function category(): BelongsTo
  {
    return $this->belongsTo(Category::class, 'category_id');
  }

  public function variants(): HasMany
  {
    return $this->hasMany(ProductVariant::class);
  }

  public function images(): HasMany
  {
    return $this->hasMany(ProductImage::class);
  }
}
