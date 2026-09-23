<?php

namespace Matondojk\FilamentEventCalendar\Jobs;

use Matondojk\FilamentEventCalendar\Mail\EventInvitationMail;
use Matondojk\FilamentEventCalendar\Models\Event;
use Filament\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;

class SendEventInvitationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Event $event,
        public Collection $users
    ) {}

    public function handle(): void
    {
        foreach ($this->users as $user) {
            // Send Filament Database Notification
            Notification::make()
                ->title(__('filament-event-calendar::events.notifications.new_invitation_title', ['title' => $this->event->title]))
                ->body(__('filament-event-calendar::events.notifications.new_invitation_body', ['date' => $this->event->starts_at->translatedFormat('M j, Y')]))
                ->success()
                ->sendToDatabase($user);

            // Send Email
            Mail::to($user->email)->send(new EventInvitationMail($this->event));
        }
    }
}
