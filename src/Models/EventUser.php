<?php

namespace Matondojk\FilamentCalendar\Models;

use Matondojk\FilamentCalendar\Jobs\SendEventInvitationJob;
use Matondojk\FilamentCalendar\Models\Event;
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
                \Illuminate\Support\Facades\Log::info("Pivot created for Event {$event->id}, User {$user->id}. Dispatching invitation.");
                SendEventInvitationJob::dispatch($event, collect([$user]));
            }
        });
    }
}
