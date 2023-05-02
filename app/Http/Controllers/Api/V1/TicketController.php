<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CreateTicketRequest;
use App\Http\Requests\V1\ListTicketRequest;
use App\Http\Resources\V1\CreateTicketResource;
use App\Http\Resources\V1\ListTicketResource;
use App\Http\Services\TicketValidationService;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    /**
     * Create/book a ticket for an event
     *
     * @param CreateTicketRequest $request Validation
     * @return CreateTicketResource|JsonResponse Eloquent Resources or Json error
     */
    public function create(
        CreateTicketRequest $request,
        TicketValidationService $ticketValidationService
    ): CreateTicketResource|JsonResponse {
        $data = $request->safe()->all();

        /** @var User $user User Info*/
        $user = Auth::user();
        $data['user_uuid'] = $user->uuid;

        /** @var Event $event Find the event by its UUID */
        $event = Event::where('uuid', $data['event_uuid'])->firstOrFail();

        if ($ticketValidationService->check($event, $user) === false) {
            return response()->json(['message' => $ticketValidationService->errorMessage], 400);
        }

        $ticket = Ticket::create($data);

        return new CreateTicketResource($ticket);
    }

    /**
     * List tickets of authenticated user
     *
     * @return AnonymousResourceCollection Eloquent Resources Collection
     */
    public function list(ListTicketRequest $request): AnonymousResourceCollection
    {
        $filters = $request->safe()->all();
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $direction = $filters['direction'] ?? 'desc';

        /** @var User $user User Info*/
        $user = Auth::user();
        $filters['user_uuid'] = $user->uuid;

        $tickets = Ticket::filter($filters)
            ->sort($sortBy, $direction)
            ->paginate($request->get('limit'));

        return ListTicketResource::collection($tickets);
    }
}
