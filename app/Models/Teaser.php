<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Teaser extends Model
{
    protected $fillable = [
        'image',
        'caption',
        'position',
        'active',
        'youtube_code',
        'page_url',
        'category_id',
        'carousel_type',
        'product_id',
    ];

    protected $casts = [
        'active' => 'boolean',
        'position' => 'integer',
    ];

    public function translations(): HasMany
    {
        return $this->hasMany(TeaserTranslation::class);
    }

    public function currentTranslation()
    {
        return $this->hasOne(TeaserTranslation::class)
            ->where('locale', app()->getLocale());
    }

    public function getPromoTextAttribute(): ?string
    {
        return $this->currentTranslation?->promo_text;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    protected static function booted(): void
    {
        static::creating(function ($teaser) {
            $maxPosition = static::max('position') ?? 0;
            $teaser->position = $maxPosition + 1;
        });
    }
}
