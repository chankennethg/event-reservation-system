<?php

namespace App\Models\Traits;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Str;

trait HasUuid
{
    /**
     * Boot
     */
    protected static function bootHasUuid(): void
    {
        static::creating(function ($model): void {
            if (!$model->uuid) {
                $model->uuid = (string) Str::orderedUuid();
            }
        });
    }

    /**
     * Find or fail
     *
     * @param string $uuid user uuid
     * @return object self
     */
    public static function findByUuidOrFail(string $uuid): object
    {
        return self::whereUuid($uuid)->firstOrFail();
    }

    /**
     * Eloquent scope to look for a given UUID
     *
     * @param  Builder $query builder objecet
     * @param  String  $uuid  The UUID to search for
     * @return Builder Query builder object
     */
    public function scopeUuid(Builder $query, string $uuid): Builder
    {
        return $query->where('uuid', $uuid);
    }

    /**
     * Get the route key for the model.
     *
     * @return string keyname
     */
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }
}
