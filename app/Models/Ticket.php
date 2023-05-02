<?php

namespace App\Models;

use App\Models\Contracts\Listable as ListableContract;
use App\Models\Traits\HasUuid;
use App\Models\Traits\Listable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @implements ListableContract<Ticket>
 */
class Ticket extends Model implements ListableContract
{
    /** @use Listable<Ticket> */
    use HasFactory, HasUuid, Listable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_uuid',
        'event_uuid',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'last_updated_at',
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
        return [];
    }

    /**
     * List of filter like columns
     *
     * @return array<int,string>
     */
    public function getFilterExactColumns(): array
    {
        return [
            'uuid',
            'user_uuid',
            'event_uuid',
        ];
    }

    /**
     * Ticket belongs to Event Relationship
     *
     * @return BelongsTo Eloquent Relationship
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_uuid', 'uuid');
    }

    /**
     * Ticket belongs to a User Relationship
     *
     * @return BelongsTo Eloquent Relationship
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_uuid', 'uuid');
    }
}
