<?php

namespace Matondojk\FilamentEventCalendar\Models;

use Matondojk\FilamentEventCalendar\Jobs\SendEventInvitationJob;
use Matondojk\FilamentEventCalendar\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\Pivot;

class EventUser extends Pivot
{
    protected $table = 'event_user';

    protected static function booted(): void
    {
        static::created(function (EventUser $pivot) {
            $event = Event::find($pivot->event_id);
            $user = User::find($pivot->user_id);

            if ($event && $user) {
                if (config('filament-event-calendar.send_invitation_emails', true)) {
                    \Illuminate\Support\Facades\Log::info("Pivot created for Event {$event->id}, User {$user->id}. Dispatching invitation.");
                    SendEventInvitationJob::dispatch($event, collect([$user]));
                }
            }
        });
    }
}
