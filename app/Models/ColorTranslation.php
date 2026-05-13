<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ColorTranslation extends Model
{
    protected $fillable = [
        'locale',
        'color_id',

        'title',
        'slug',

        'description',

        ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class);
    }
}
