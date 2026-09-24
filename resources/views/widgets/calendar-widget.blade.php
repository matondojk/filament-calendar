<x-filament-widgets::widget>
    <x-filament::card>
        <div class="flex flex-col sm:flex-row items-center justify-between mb-4 gap-4">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-primary-500/10 dark:bg-primary-500/10 rounded-full flex items-center justify-center">
                    <x-filament::icon icon="heroicon-m-calendar-days" class="w-7 h-7 text-primary-600 dark:text-primary-400" />
                </div>
                <div>
                    <h2 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white capitalize leading-none">
                        {{ Str::ucfirst(\Carbon\Carbon::create($currentYear, $currentMonth, 1)->translatedFormat('F Y')) }}
                    </h2>
                    <p class="text-sm font-medium text-gray-500 dark:text-zinc-400 mt-1">
                        {{ __('filament-event-calendar::calendar.widget_subtitle') }}
                    </p>
                </div>
            </div>
            
            <div class="flex flex-wrap space-x-3 items-center">
                <div class="w-40">
                    <x-filament::input.wrapper>
                        <x-filament::input.select wire:model.live="filter">
                            <option value="all">{{ __('filament-event-calendar::events.resource.tabs.all') }}</option>
                            <option value="my_events">{{ __('filament-event-calendar::events.resource.tabs.my_events') }}</option>
                            <option value="invited">{{ __('filament-event-calendar::events.resource.tabs.invited') }}</option>
                        </x-filament::input.select>
                    </x-filament::input.wrapper>
                </div>

                {{ $this->createEventAction }}
                
                <x-filament::button.group>
                    <x-filament::button wire:click="previousMonth" color="gray" size="sm" icon="heroicon-m-chevron-left" tooltip="{{ __('filament-event-calendar::calendar.previous') }}">
                    </x-filament::button>
                    <x-filament::button wire:click="nextMonth" color="gray" size="sm" icon="heroicon-m-chevron-right" tooltip="{{ __('filament-event-calendar::calendar.next') }}">
                    </x-filament::button>
                </x-filament::button.group>
            </div>
        </div>

        <div class="grid gap-px bg-gray-200 dark:bg-zinc-800 rounded-xl overflow-hidden shadow-sm ring-1 ring-gray-200 dark:ring-zinc-800" style="grid-template-columns: repeat(7, minmax(0, 1fr));">
            @foreach ([__('filament-event-calendar::calendar.days.sun'), __('filament-event-calendar::calendar.days.mon'), __('filament-event-calendar::calendar.days.tue'), __('filament-event-calendar::calendar.days.wed'), __('filament-event-calendar::calendar.days.thu'), __('filament-event-calendar::calendar.days.fri'), __('filament-event-calendar::calendar.days.sat')] as $dayName)
                <div class="bg-gray-50 dark:bg-zinc-800/50 p-2 text-center text-xs font-bold uppercase tracking-widest text-gray-500 dark:text-zinc-400">
                    {{ Str::substr($dayName, 0, 3) }}
                </div>
            @endforeach

            @foreach ($this->days as $day)
                <div class="bg-white dark:bg-zinc-900 p-1.5 flex flex-col gap-1 {{ $day['isCurrentMonth'] ? '' : 'opacity-50 bg-gray-50 dark:bg-zinc-800/50' }}" style="min-height: 100px;">
                    <div class="font-bold text-xs mb-1 {{ $day['date']->isToday() ? 'flex items-center justify-center w-7 h-7 rounded-full bg-orange-500 text-white shadow-md mx-auto sm:mx-0 sm:ml-0.5' : 'text-center sm:text-left sm:ml-1 text-gray-700 dark:text-zinc-300' }}">
                        {{ $day['date']->format('j') }}
                    </div>
                    
                    <div class="space-y-1 flex flex-col w-full">
                        @foreach ($this->events->filter(fn($e) => $e->starts_at->isSameDay($day['date']) || $e->ends_at->isSameDay($day['date']) || $day['date']->between($e->starts_at->copy()->startOfDay(), $e->ends_at->copy()->endOfDay())) as $event)
                            @php
                                $status = $event->users->first()?->pivot->rsvp_status ?? 'pending';
                                $color = $status === 'confirmed' ? 'success' : ($status === 'declined' ? 'danger' : 'warning');
                                $icon = $event->format === 'virtual' ? 'heroicon-m-video-camera' : 'heroicon-m-map-pin';
                            @endphp
                            
                            <div wire:click="mountAction('viewEvent', { event_id: {{ $event->id }} })" class="cursor-pointer hover:opacity-80 transition block">
                                <x-filament::badge :color="$color" size="sm" class="w-full justify-start overflow-hidden !p-1 !gap-1" style="max-width: 100%;">
                                    <x-slot name="icon">
                                        <x-filament::icon :icon="$icon" class="w-3 h-3 shrink-0" />
                                    </x-slot>
                                    <span class="truncate block w-full text-left" style="font-size: 0.65rem; line-height: 1rem;">
                                        <strong class="font-bold mr-0.5">{{ $event->starts_at->format('H:i') }}</strong>
                                        {{ $event->title }}
                                    </span>
                                </x-filament::badge>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <x-filament-actions::modals />
    </x-filament::card>
</x-filament-widgets::widget>
