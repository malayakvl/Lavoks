<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CategoryTranslation extends Model
{
    protected $fillable = [
        'category_id',
        'locale',

        'title',
        'slug',

        'description',

        'meta_title',
        'meta_keywords',
        'product_meta_title',
        'product_meta_description',
        'meta_description',

        'seo_title',
        'seo_content',

        'product_title',
        'product_description',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}