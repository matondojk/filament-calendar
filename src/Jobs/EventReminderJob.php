<?php

namespace Matondojk\FilamentEventCalendar\Jobs;

use Matondojk\FilamentEventCalendar\Models\Event;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

// Reusing or create a reminder mail, let's create a reminder mail content

class EventReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $start = Carbon::now()->addHours(24)->startOfHour();
        $end = Carbon::now()->addHours(24)->endOfHour();

        $events = Event::with(['users' => function ($query) {
            $query->whereIn('event_user.rsvp_status', ['pending', 'confirmed']);
        }])->whereBetween('starts_at', [$start, $end])->get();

        foreach ($events as $event) {
            foreach ($event->users as $user) {
                // Database notification
                Notification::make()
                    ->title(__('filament-event-calendar::events.notifications.reminder_title', ['title' => $event->title]))
                    ->body(__('filament-event-calendar::events.notifications.reminder_body', ['date' => $event->starts_at->translatedFormat('M j, Y H:i')]))
                    ->warning()
                    ->sendToDatabase($user);

                // Email notification (simple raw email for reminder)
                $locationStr = $event->format === 'virtual' 
                    ? __('filament-event-calendar::calendar.labels.meeting_link') . ': ' . $event->meeting_link 
                    : __('filament-event-calendar::calendar.labels.location') . ': ' . $event->location;
                    
                Mail::raw(__('filament-event-calendar::events.emails.reminder_body', [
                    'title' => $event->title,
                    'date' => $event->starts_at->translatedFormat('M j, Y H:i'),
                    'location' => $locationStr
                ]), function ($message) use ($user, $event) {
                    $message->to($user->email)
                        ->subject(__('filament-event-calendar::events.emails.reminder_subject', ['title' => $event->title]));
                });
            }
        }
    }
}
