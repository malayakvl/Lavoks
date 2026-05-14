<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gender extends Model
{
    protected $table = 'genders';

    protected $fillable = [
        'code',
        'active',
        'order',
        'slug',
        'emoji',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function translations(): HasMany
    {
        return $this->hasMany(GenderTranslation::class);
    }

    public function getTitleAttribute(): string
    {
        return $this->translations()->where('locale', app()->getLocale())->first()?->title
            ?? $this->translations()->first()?->title
            ?? '';
    }
}
