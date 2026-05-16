<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $table = 'sizes';

    protected $fillable = [
        'original_value',
        'normalized_value',

        'length',
        'width',
        'height',
        'depth',

        'format',

        'is_structured',

        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'is_structured' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function categories()
    {
        return $this->hasMany(Category::class, 'size_id');
    }
}
