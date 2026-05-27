<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Leather extends Model
{
    protected $table = 'leathers';

    protected $fillable = [
        'image',
        'old_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */


    public function translations()
    {
        return $this->hasMany(LeatherTranslation::class);
    }

    /*
    |--------------------------------------------------------------------------
    | TRANSLATION CORE
    |--------------------------------------------------------------------------
    */

    public function currentTranslation()
    {
        return $this->hasOne(LeatherTranslation::class)
            ->where('locale', app()->getLocale());
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS (ЕДИНЫЙ ИСТОЧНИК ПРАВДЫ)
    |--------------------------------------------------------------------------
    */

    public function getTitleAttribute(): string
    {
        return $this->currentTranslation->title ?? '—';
    }

    public function getSlugAttribute(): string
    {
        return $this->currentTranslation->slug ?? '';
    }


    /*
    |--------------------------------------------------------------------------
    | QUERY OPTIMIZATION (ВАЖНО: это НЕ модель, а обычно resource)
    |--------------------------------------------------------------------------
    */

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'translations',
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | BOOT LOGIC
    |--------------------------------------------------------------------------
    */

    protected static function booted(): void
    {

    }
}
