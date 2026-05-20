<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teaser extends Model
{
    protected $fillable = [
        'images',
        'caption',
        'position',
        'active',
        'youtube_code',
        'page_url',
        'category_id',
    ];

    protected $casts = [
        'active' => 'boolean',
        'position' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    protected static function booted(): void
    {
        static::creating(function ($teaser) {
            $maxPosition = static::max('position') ?? 0;
            $teaser->position = $maxPosition + 1;
        });
    }
}
