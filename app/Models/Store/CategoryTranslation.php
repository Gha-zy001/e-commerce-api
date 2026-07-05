<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Model;

class CategoryTranslation extends Model
{
  protected $table = 'category_translations';
  protected $fillable = [
    'id',
    'language',
    'is_default',
    'name',
    'slug',
  ];
  protected $casts = [
    'is_default' => 'boolean',
  ];
}
