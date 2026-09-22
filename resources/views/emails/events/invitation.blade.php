<x-mail::message>
# {{ __('events.emails.invitation_header') }}

## {{ $event->title }}

**{{ __('calendar.labels.date_time') }}:** {{ $event->starts_at->translatedFormat('M j, Y H:i') }} - {{ $event->ends_at->translatedFormat('M j, Y H:i') }}

**{{ __('calendar.labels.format') }}:** {{ $event->format === 'virtual' ? __('calendar.format.virtual') : __('calendar.format.in_person') }}

@if($event->format === 'in_person' && $event->location)
**{{ __('calendar.labels.location') }}:** {{ $event->location }}
@endif

@if($event->format === 'virtual')
**{{ __('calendar.labels.platform') }}:** {{ $event->platform }}

@if($event->meeting_link)
<x-mail::button :url="$event->meeting_link">
{{ __('calendar.labels.join_meeting') }}
</x-mail::button>
@endif
@endif

@if($event->description)
**{{ __('events.emails.details') }}**
{{ $event->description }}
@endif

{{ __('events.emails.footer') }}
</x-mail::message>
