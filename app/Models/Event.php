<?php

namespace App\Models;

use App\Models\Contracts\Listable as ListableContract;
use App\Models\Traits\HasUuid;
use App\Models\Traits\Listable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @implements ListableContract<Event>
 */
class Event extends Model implements ListableContract
{
    /** @use Listable<Event> */
    use HasUuid, HasFactory, Listable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_uuid',
        'title',
        'description',
        'location',
        'price',
        'attendee_limit',
        'starts_at',
        'ends_at',
        'reservation_starts_at',
        'reservation_ends_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [];

    /**
     * List of sortable columns
     *
     * @return array<int,string>
     */
    public function getSortableColumns(): array
    {
        return [
            'id',
            'title',
            'description',
            'location',
            'price',
            'attendee_limit',
            'schedule',
            'phone_number',
            'reservation_starts_at',
            'reservation_ends_at',
            'created_at',
        ];
    }

    /**
     * List of filter like columns
     *
     * @return array<int,string>
     */
    public function getFilterLikeColumns(): array
    {
        return [
            'title',
            'description',
            'location',
        ];
    }

    /**
     * List of filter like columns
     *
     * @return array<int,string>
     */
    public function getFilterExactColumns(): array
    {
        return [];
    }

    /**
     * Event belongs to User relationship
     *
     * @return BelongsTo Eloquent Relationship
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query for event status
     *
     * @param Builder<Event> $query
     * @param string $status inactive|reserve
     * @return void void
     */
    public function scopeStatus(Builder $query, string $status): void
    {
        if ($status === 'inactive') {
            $query->inactive();
        }

        if ($status === 'reserve') {
            $query->reserve();
        }
    }

    /**
     * Scope a query for inactive events
     *
     * @param Builder<Event> $query
     * @return void void
     */
    public function scopeInactive(Builder $query): void
    {
        $query->where('starts_at', '>', date('Y-m-d H:i:s'));
    }

    /**
     * Scope a query for reservation events
     *
     * @param Builder<Event> $query
     * @return void void
     */
    public function scopeReserve(Builder $query): void
    {
        $query->where('reservation_starts_at', '<=', date('Y-m-d H:i:s'));
        $query->where('reservation_ends_at', '>=', date('Y-m-d H:i:s'));
    }
}
