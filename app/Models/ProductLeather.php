<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductLeather extends Model
{
    protected $table = 'product_leathers';

    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'product_id',
        'leather_id',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function leather(): BelongsTo
    {
        return $this->belongsTo(Leather::class);
    }
}
