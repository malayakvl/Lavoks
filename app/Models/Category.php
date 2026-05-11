<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Category extends Model
{
    protected $fillable = [
        'parent_id',
        'active',
        'position',
        'in_bottom_menu',
        'level',
        'image',


        'emoji',
        'percent_change',
        'fix_price',
        'discount',

        'old_id',
        'parent_old_id'
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')
            ->orderBy('position');
    }

    public function translations()
    {
        return $this->hasMany(CategoryTranslation::class);
    }

    /*
    |--------------------------------------------------------------------------
    | TRANSLATION CORE
    |--------------------------------------------------------------------------
    */

    public function currentTranslation()
    {
        return $this->hasOne(CategoryTranslation::class)
            ->where('locale', app()->getLocale());
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS (ЕДИНЫЙ ИСТОЧНИК ПРАВДЫ)
    |--------------------------------------------------------------------------
    */

    public function getTitleAttribute(): string
    {
        return $this->currentTranslation->title ?? '—';
    }

    public function getSlugAttribute(): string
    {
        return $this->currentTranslation->slug ?? '';
    }

    public function getDescriptionAttribute(): ?string
    {
        return $this->currentTranslation->description;
    }

    public function getMetaTitleAttribute(): ?string
    {
        return $this->currentTranslation->meta_title;
    }

    public function getMetaDescriptionAttribute(): ?string
    {
        return $this->currentTranslation->meta_description;
    }

    /*
    |--------------------------------------------------------------------------
    | TREE DISPLAY
    |--------------------------------------------------------------------------
    */

    public function getTreeTitleAttribute(): string
    {
        if ($this->parent_id && $this->parent) {
            return $this->parent->currentTranslation->title . " '{$this->title}'";
        }

        return $this->title;
    }

    /*
    |--------------------------------------------------------------------------
    | QUERY OPTIMIZATION (ВАЖНО: это НЕ модель, а обычно resource)
    |--------------------------------------------------------------------------
    */

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'translations',
                'parent.translations',
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | BOOT LOGIC
    |--------------------------------------------------------------------------
    */

//    protected static function booted(): void
//    {
//        static::creating(function ($category) {
//            $maxOrder = static::where('parent_id', $category->parent_id)
//                ->max('position') ?? 0;
//
//            $category->position = $maxOrder + 1;
//        });
//    }
}
