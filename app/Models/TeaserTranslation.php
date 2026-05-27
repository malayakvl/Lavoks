<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeaserTranslation extends Model
{
    protected $fillable = [
        'teaser_id',
        'locale',
        'promo_text',
    ];

    public function teaser(): BelongsTo
    {
        return $this->belongsTo(Teaser::class);
    }
}
