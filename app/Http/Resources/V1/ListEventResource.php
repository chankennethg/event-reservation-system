<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Event
 */
class ListEventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'user_uuid' => $this->user_uuid,
            'title' => $this->title,
            'description' => $this->description,
            'location' => $this->location,
            'price' => $this->price,
            'attendee_limit' => $this->attendee_limit,
            'ticket_count' => $this->whenCounted('tickets'),
            'starts_at' => $this->starts_at,
            'ends_at' => $this->ends_at,
            'reservation_starts_at' => $this->reservation_starts_at,
            'reservation_ends_at' => $this->reservation_ends_at,
        ];
    }
}
