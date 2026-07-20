<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attribute extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected $fillable = [
        'code',
        'type',
        'is_filterable',
        'is_visible',
        'is_required',
        'sort_order',
    ];

    protected $casts = [
        'is_filterable' => 'boolean',
        'is_visible' => 'boolean',
        'is_required' => 'boolean',
        'sort_order' => 'integer',
    ];

    public const TYPE_TEXT = 'text';

    public const TYPE_COLOR = 'color';

    public const TYPE_SELECT = 'select';

    public const TYPE_MULTISELECT = 'multiselect';

    public const TYPE_NUMBER = 'number';

    public const TYPES = [
        self::TYPE_TEXT,
        self::TYPE_COLOR,
        self::TYPE_SELECT,
        self::TYPE_MULTISELECT,
        self::TYPE_NUMBER,
    ];

    public function translations()
    {
        return $this->hasMany(AttributeTranslation::class, 'attribute_id');
    }

    public function values()
    {
        return $this->hasMany(AttributeValue::class, 'attribute_id');
    }

    public function getNameAttribute(): ?string
    {
        return $this->translations->where('locale', app()->getLocale())->first()?->name
            ?? $this->translations->first()?->name
            ?? $this->code;
    }

    public function scopeFilterable($query)
    {
        return $query->where('is_filterable', true);
    }

    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    public function scopeRequired($query)
    {
        return $query->where('is_required', true);
    }

    public function scopeSorted($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}
