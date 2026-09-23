<?php

namespace Matondojk\FilamentCalendar\Mail;

use Matondojk\FilamentCalendar\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EventInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Event $event) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('filament-calendar::events.emails.invitation_subject', ['title' => $this->event->title]),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'filament-calendar::emails.events.invitation',
            with: [
                'event' => $this->event,
            ],
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => $this->generateIcs(), 'invite.ics')
                ->withMime('text/calendar'),
        ];
    }

    protected function generateIcs(): string
    {
        $start = $this->event->starts_at->setTimezone('UTC')->format('Ymd\THis\Z');
        $end = $this->event->ends_at->setTimezone('UTC')->format('Ymd\THis\Z');
        $now = now()->setTimezone('UTC')->format('Ymd\THis\Z');
        $uid = $this->event->id.'-'.time().'@'.request()->getHost();
        
        $location = $this->event->format === 'virtual' ? $this->event->meeting_link : $this->event->location;
        $description = str_replace(["\r", "\n"], [' ', '\n'], (string)$this->event->description);

        $ics = [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'PRODID:-//Laravel Filament App//NONSGML v1.0//EN',
            'CALSCALE:GREGORIAN',
            'METHOD:REQUEST',
            'BEGIN:VEVENT',
            'UID:'.$uid,
            'DTSTAMP:'.$now,
            'DTSTART:'.$start,
            'DTEND:'.$end,
            'SUMMARY:'.$this->event->title,
            'DESCRIPTION:'.$description,
        ];

        if ($location) {
            $ics[] = 'LOCATION:'.$location;
        }

        $ics[] = 'STATUS:CONFIRMED';
        $ics[] = 'SEQUENCE:0';
        $ics[] = 'END:VEVENT';
        $ics[] = 'END:VCALENDAR';

        return implode("\r\n", $ics);
    }
}
