<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CreateEventRequest;
use App\Http\Requests\V1\ListEventRequest;
use App\Http\Resources\V1\CreateEventResource;
use App\Http\Resources\V1\ListEventResource;
use App\Models\Event;
use App\Models\User;
use ArrayAccess;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Create Event
     *
     * @param CreateEventRequest $request User Payload
     * @return CreateEventResource Eloquent Resources
     */
    public function create(CreateEventRequest $request): CreateEventResource
    {
        $data = $request->safe()->all();

        /** @var User $user */
        $user = Auth::user();
        $data['user_uuid'] = $user->uuid;

        $event = Event::create($data);

        return new CreateEventResource($event);
    }

    /**
     * List events
     *
     * @param ListEventRequest $request User Payload
     * @return ArrayAccess Eloquent Resources Collection
     */
    public function list(ListEventRequest $request): ArrayAccess
    {
        $filters = $request->safe()->all();
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $direction = $filters['direction'] ?? 'desc';
        $status = $filters['status'] ?? '';

        $events = Event::filter($filters)
            ->status($status)
            ->sort($sortBy, $direction)
            ->paginate($request->get('limit'));

        return ListEventResource::collection($events);
    }
}
