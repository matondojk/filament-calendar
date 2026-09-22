<div class="space-y-6">
    @if($event->description)
        <div class="text-sm text-gray-700 dark:text-gray-300">
            {{ $event->description }}
        </div>
    @endif

    <div class="divide-y divide-gray-200 dark:divide-white/10">
        {{-- Date & Time --}}
        <div class="py-3 flex justify-between items-center">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('filament-calendar::calendar.labels.date_time') }}</div>
            <div class="text-sm font-semibold text-gray-900 dark:text-white text-right">
                {{ $event->starts_at->translatedFormat('F j, Y') }}<br>
                <span class="text-xs text-gray-500">{{ $event->starts_at->format('H:i') }} - {{ $event->ends_at->format('H:i') }}</span>
            </div>
        </div>

        {{-- Format --}}
        <div class="py-3 flex justify-between items-center">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('filament-calendar::calendar.labels.format') }}</div>
            <div class="text-sm font-semibold text-gray-900 dark:text-white">
                {{ $event->format === 'virtual' ? __('filament-calendar::calendar.format.virtual') : __('filament-calendar::calendar.format.in_person') }}
            </div>
        </div>

        {{-- Location / Platform --}}
        @if($event->format === 'in_person' && $event->location)
            <div class="py-3 flex justify-between items-center">
                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('filament-calendar::calendar.labels.location') }}</div>
                <div class="text-sm font-semibold text-gray-900 dark:text-white text-right max-w-xs truncate" title="{{ $event->location }}">
                    {{ $event->location }}
                </div>
            </div>
        @endif

        @if($event->format === 'virtual')
            <div class="py-3 flex justify-between items-center">
                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('filament-calendar::calendar.labels.platform') }}</div>
                <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $event->platform }}</div>
            </div>
            
            @if($event->meeting_link)
            <div class="py-3 flex justify-between items-center">
                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('filament-calendar::calendar.labels.meeting_link') }}</div>
                <a href="{{ $event->meeting_link }}" target="_blank" class="text-sm font-semibold text-primary-600 hover:underline">
                    {{ __('filament-calendar::calendar.labels.join_meeting') }} &rarr;
                </a>
            </div>
            @endif
        @endif

        {{-- RSVP Status --}}
        <div class="py-3 flex justify-between items-center border-b border-gray-200 dark:border-white/10">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('filament-calendar::calendar.labels.rsvp_status') }}</div>
            @php
                $status = $event->users->first()?->pivot->rsvp_status ?? 'pending';
                $labels = ['pending' => __('filament-calendar::calendar.status.pending'), 'confirmed' => __('filament-calendar::calendar.status.confirmed'), 'declined' => __('filament-calendar::calendar.status.declined')];
                $colors = [
                    'pending' => 'text-warning-600 dark:text-warning-400',
                    'confirmed' => 'text-success-600 dark:text-success-400',
                    'declined' => 'text-danger-600 dark:text-danger-400',
                ];
            @endphp
            <div class="text-sm font-bold uppercase tracking-wider {{ $colors[$status] ?? $colors['pending'] }}">
                {{ $labels[$status] ?? $labels['pending'] }}
            </div>
        </div>
    </div>
</div>
