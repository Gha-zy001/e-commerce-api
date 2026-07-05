<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Model;

class ProductTranslation extends Model
{
  protected $table = 'product_translations';
  protected $fillable = [
    'id',
    'language',
    'is_default',
    'name',
    'description',
    'slug',
  ];
  protected $casts = [
    'is_default' => 'boolean',
  ];
}
