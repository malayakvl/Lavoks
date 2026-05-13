<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'old_id',

        'category_id',

        'code',
        'gtin',
        'mpn',

        'price',
        'old_price',

        'active',
        'popular',
        'is_new',
        'to_order',
        'is_absent',

        'rating',
        'review_count',
        'sort_order',

        'old_id'
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
}
