<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'old_id',

        'category_id',
        'product_family_id',

        'code',
        'gtin',
        'mpn',

        'price',
        'base_price',

        'active',
        'popular',
        'is_new',
        'to_order',
        'is_absent',

        'rating',
        'review_count',
        'sort_order',

        'old_id',
        'main_image',
        'slug'
    ];

    protected $casts = [
        'active' => 'boolean',
        'popular' => 'boolean',
        'is_new' => 'boolean',
        'to_order' => 'boolean',
        'is_absent' => 'boolean',
        'price' => 'float',
        'old_price' => 'float',
        'rating' => 'float',
    ];

    protected static function booted(): void
    {
        static::creating(function ($product) {

            if (empty($product->base_price)) {
                $product->base_price = $product->price;
            }

        });
    }

    public function translations(): HasMany
    {
        return $this->hasMany(ProductTranslation::class);
    }

    public function title()
    {
        return $this->translations()->where('locale', app()->getLocale())->first()?->title
            ?? $this->translations()->first()?->title
            ?? '';
    }

    public function colors(): BelongsToMany
    {
        return $this->belongsToMany(Color::class, 'product_colors');
    }

    public function leathers(): BelongsToMany
    {
        return $this->belongsToMany(Leather::class, 'product_leathers');
    }

    public function genders(): BelongsToMany
    {
        return $this->belongsToMany(Gender::class, 'product_genders');
    }

    public function sizes(): BelongsToMany
    {
        return $this->belongsToMany(Size::class, 'product_sizes');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function productFamily()
    {
        return $this->belongsTo(ProductFamily::class);
    }
}
