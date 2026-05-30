<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class CarouselItem extends Model
{
    protected $fillable = [
        'slidable_type',
        'slidable_id',
        'position',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'position' => 'integer',
    ];

    public function slidable(): MorphTo
    {
        return $this->morphTo('slidable', 'slidable_type', 'slidable_id');
    }

    protected static function booted(): void
    {
        static::creating(function ($carouselItem) {
            $maxPosition = static::max('position') ?? 0;
            $carouselItem->position = $maxPosition + 1;
        });
    }
}
