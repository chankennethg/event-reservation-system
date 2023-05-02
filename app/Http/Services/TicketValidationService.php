<?php

namespace App\Http\Services;

use App\Models\Event;
use App\Models\Ticket;
use App\Models\User;

/**
 * @mixin \App\Models\Event
 */
class TicketValidationService
{
    /**
     * @var string Error message
     */
    public string $errorMessage = '';

    /**
     * @var Event Event to be checked
     */
    protected Event $event;

    /**
     * @var User User to be checked
     */
    protected User $user;

    /**
     * @param Event $event Event Model
     * @param User $user User Model
     * @return bool true|false
     */
    public function check(Event $event, User $user): bool
    {
        $this->event = $event;
        $this->user = $user;
        return $this->notOwnEvent()
            && $this->isForReservation()
            && $this->isSlotAvailable()
            && $this->isNotDuplicate();
    }

    /**
     * Check if the user tries to book his own event
     *
     * @return bool true|false
     */
    private function notOwnEvent(): bool
    {
        if ($this->event->user_uuid !== $this->user->uuid) {
            return true;
        }

        $this->errorMessage = 'Cannot create ticket on own event.';
        return false;
    }

    /**
     * Check if there is a slot available
     *
     * @return bool true|false
     */
    private function isSlotAvailable(): bool
    {
        if ($this->event->tickets()->count() <= $this->event->attendee_limit
            || $this->event->attendee_limit === null) {
            return true;
        }
        $this->errorMessage = 'No available slots.';
        return false;
    }

    /**
     * Check if event is open for reservation
     *
     * @return bool true|false
     */
    private function isForReservation(): bool
    {
        $today = date('Y-m-d H:i:s');
        if ($this->event->reservation_starts_at <= $today && $this->event->reservation_ends_at >= $today) {
            return true;
        }

        $this->errorMessage = 'Not open for reservation.';
        return false;
    }

    /**
     * Check if ticket is not a duplicate in database
     * Currently one user = one ticket only
     *
     * @return bool true|false
     */
    private function isNotDuplicate(): bool
    {
        $ticketCount = Ticket::where('event_uuid', $this->event->uuid)
            ->where('user_uuid', $this->user->uuid)
            ->count();

        if ($ticketCount === 0) {
            return true;
        }

        $this->errorMessage = 'You are already reserved in this event.';
        return false;
    }
}
