<x-filament-widgets::widget>
    <x-filament::card>
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-primary-50 dark:bg-primary-500/10 rounded-xl shadow-sm border border-primary-100 dark:border-primary-500/20">
                    <x-filament::icon icon="heroicon-o-calendar-days" class="w-6 h-6 text-primary-600 dark:text-primary-400" />
                </div>
                <div>
                    <h2 class="text-xl font-extrabold tracking-tight text-gray-900 dark:text-white capitalize leading-none">
                        {{ Str::ucfirst(\Carbon\Carbon::create($currentYear, $currentMonth, 1)->translatedFormat('F Y')) }}
                    </h2>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mt-1">
                        {{ __('filament-calendar::calendar.widget_subtitle') }}
                    </p>
                </div>
            </div>
            
            <div class="flex space-x-3 items-center" x-data>
                <select wire:model.live="filter" class="text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="all">{{ __('filament-calendar::events.resource.tabs.all') }}</option>
                    <option value="my_events">{{ __('filament-calendar::events.resource.tabs.my_events') }}</option>
                    <option value="invited">{{ __('filament-calendar::events.resource.tabs.invited') }}</option>
                </select>

                {{ $this->createEventAction }}
                
                <div class="flex space-x-1 border-l border-gray-200 dark:border-gray-700 pl-3">
                    <x-filament::button x-on:click="$wire.previousMonth()" color="secondary" size="sm" icon="heroicon-m-chevron-left" class="!px-2 shadow-sm hover:bg-gray-50">
                        <span class="sr-only">{{ __('filament-calendar::calendar.previous') }}</span>
                    </x-filament::button>
                    <x-filament::button x-on:click="$wire.nextMonth()" color="secondary" size="sm" icon="heroicon-m-chevron-right" icon-position="after" class="!px-2 shadow-sm hover:bg-gray-50">
                        <span class="sr-only">{{ __('filament-calendar::calendar.next') }}</span>
                    </x-filament::button>
                </div>
            </div>
        </div>

        <div x-data class="grid grid-cols-7 gap-px bg-gray-200 dark:bg-white/5 border border-gray-200 dark:border-white/5 rounded-xl overflow-hidden shadow-sm">
            @foreach ([__('filament-calendar::calendar.days.sun'), __('filament-calendar::calendar.days.mon'), __('filament-calendar::calendar.days.tue'), __('filament-calendar::calendar.days.wed'), __('filament-calendar::calendar.days.thu'), __('filament-calendar::calendar.days.fri'), __('filament-calendar::calendar.days.sat')] as $dayName)
                <div class="bg-gray-50 dark:bg-gray-900/40 p-2 text-center text-[10px] font-bold uppercase tracking-widest text-gray-500 dark:text-gray-400">
                    {{ $dayName }}
                </div>
            @endforeach

            @foreach ($this->days as $day)
                <div class="bg-white dark:bg-gray-900 min-h-[85px] p-1.5 {{ $day['isCurrentMonth'] ? '' : 'text-gray-400 dark:text-gray-600 bg-gray-50 dark:bg-gray-900/40' }}">
                    <div class="font-bold text-xs mb-1.5 {{ $day['date']->isToday() ? 'flex items-center justify-center w-6 h-6 rounded-full bg-primary-600 text-white shadow-md mx-auto sm:mx-0 sm:ml-1' : 'text-center sm:text-left sm:ml-1' }}">
                        {{ $day['date']->format('j') }}
                    </div>
                    
                    <div class="space-y-1">
                        @foreach ($this->events->filter(fn($e) => $e->starts_at->isSameDay($day['date']) || $e->ends_at->isSameDay($day['date']) || $day['date']->between($e->starts_at->copy()->startOfDay(), $e->ends_at->copy()->endOfDay())) as $event)
                            @php
                                $status = $event->users->first()?->pivot->rsvp_status ?? 'pending';
                                $colors = [
                                    'confirmed' => 'bg-success-50 text-success-700 border-success-200 dark:bg-success-500/10 dark:text-success-400 dark:border-success-500/20',
                                    'declined' => 'bg-danger-50 text-danger-700 border-danger-200 dark:bg-danger-500/10 dark:text-danger-400 dark:border-danger-500/20 opacity-60',
                                    'pending' => 'bg-warning-50 text-warning-700 border-warning-200 dark:bg-warning-500/10 dark:text-warning-400 dark:border-warning-500/20',
                                ];
                            @endphp
                            <div 
                                x-on:click="$wire.mountAction('viewEvent', { event_id: {{ $event->id }} })"
                                class="text-[10px] p-1 rounded-md cursor-pointer border shadow-sm transition-all hover:scale-[1.02] flex items-center gap-1 {{ $colors[$status] ?? $colors['pending'] }} truncate leading-tight"
                                title="{{ $event->title }}"
                            >
                                @if($event->format === 'virtual')
                                    <x-filament::icon icon="heroicon-m-video-camera" class="w-3 h-3 shrink-0 opacity-75" />
                                @else
                                    <x-filament::icon icon="heroicon-m-map-pin" class="w-3 h-3 shrink-0 opacity-75" />
                                @endif
                                <div class="truncate">
                                    <span class="font-bold">{{ $event->starts_at->format('H:i') }}</span>
                                    <span class="ml-0.5">{{ $event->title }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <x-filament-actions::modals />
    </x-filament::card>
</x-filament-widgets::widget>
