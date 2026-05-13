<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeatherTranslation extends Model
{
    protected $fillable = [
        'locale',
        'leather_id',

        'title',
        'slug',

        'description',

        ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function leather(): BelongsTo
    {
        return $this->belongsTo(Leather::class);
    }
}
