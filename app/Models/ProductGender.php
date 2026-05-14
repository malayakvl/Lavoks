<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductGender extends Model
{
    protected $table = 'product_genders';

    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'product_id',
        'gender_id',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function gender(): BelongsTo
    {
        return $this->belongsTo(Gender::class);
    }
}
