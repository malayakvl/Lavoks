<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\HtmlString;

class ProductTranslation extends Model
{
    protected $fillable = [
        'locale',
        'product_id',

        'title',
        'description',

        'meta_title',
        'meta_keywords',
        'product_meta_title',
    ];


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function category(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
