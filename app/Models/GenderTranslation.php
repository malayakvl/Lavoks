<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GenderTranslation extends Model
{
    protected $table = 'gender_translations';

    public $timestamps = false;

    protected $fillable = [
        'gender_id',
        'locale',
        'title',
    ];

    public function gender(): BelongsTo
    {
        return $this->belongsTo(Gender::class);
    }
}
