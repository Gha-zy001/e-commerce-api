<?php

namespace App\Models\Catalog;

use App\Models\Cart\CartItem;
use App\Models\Order\OrderItem;
use App\Models\Review\Review;
use App\Models\Wishlist\Wishlist;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use HasUuids;
    use LogsActivity;
    use SoftDeletes;

    protected $fillable = [
        'brand_id',
        'slug',
        'is_featured',
        'is_active',
        'published_at',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function translations()
    {
        return $this->hasMany(ProductTranslation::class, 'product_id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class, 'product_id')->where('is_primary', true);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id');
    }

    public function activeVariants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id')->where('is_active', true);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product')
            ->withPivot('is_primary')
            ->withTimestamps();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'product_id');
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class, 'product_id');
    }

    public function orderItems()
    {
        return $this->hasManyThrough(OrderItem::class, ProductVariant::class);
    }

    public function cartItems()
    {
        return $this->hasManyThrough(CartItem::class, ProductVariant::class);
    }

    public function getNameAttribute(): ?string
    {
        return $this->translations->where('locale', app()->getLocale())->first()?->name
            ?? $this->translations->first()?->name;
    }

    public function getShortDescriptionAttribute(): ?string
    {
        return $this->translations->where('locale', app()->getLocale())->first()?->short_description;
    }

    public function getDescriptionAttribute(): ?string
    {
        return $this->translations->where('locale', app()->getLocale())->first()?->description;
    }

    public function getMetaTitleAttribute(): ?string
    {
        return $this->translations->where('locale', app()->getLocale())->first()?->meta_title
            ?? $this->name;
    }

    public function getMetaDescriptionAttribute(): ?string
    {
        return $this->translations->where('locale', app()->getLocale())->first()?->meta_description;
    }

    public function getPriceRangeAttribute(): array
    {
        $min = $this->variants()->min('price');
        $max = $this->variants()->max('price');

        return [
            'min' => $min,
            'max' => $max,
            'formatted' => $min === $max
                ? currency($min)
                : currency($min).' - '.currency($max),
        ];
    }

    public function getInStockAttribute(): bool
    {
        return $this->variants()->where('stock', '>', 0)->where('is_active', true)->exists();
    }

    public function getTotalStockAttribute(): int
    {
        return $this->variants()->sum('stock');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    public function scopeByBrand($query, $brandId)
    {
        return $query->where('brand_id', $brandId);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->whereHas('categories', fn ($q) => $q->where('category_id', $categoryId));
    }

    public function scopeSearch($query, $search)
    {
        return $query->whereHas('translations', fn ($q) => $q->where('name', 'like', '%'.$search.'%')
            ->orWhere('description', 'like', '%'.$search.'%')
        );
    }
}
