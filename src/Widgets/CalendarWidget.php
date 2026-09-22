<?php

namespace Matondojk\FilamentCalendar\Widgets;

use Matondojk\FilamentCalendar\Models\Event;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Widgets\Widget;
use Matondojk\FilamentCalendar\Resources\Events\Schemas\EventForm;
use Filament\Actions\CreateAction;
use Filament\Schemas\Schema;
// Actually we'll pivot via relation

use Illuminate\Support\Collection;
use Livewire\Attributes\On;

class CalendarWidget extends Widget implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    protected int | string | array $columnSpan = 'full';

    protected string $view = 'filament-calendar::widgets.calendar-widget';

    public $currentMonth;

    public $currentYear;

    public $filter = 'all';

    public function createEventAction(): Action
    {
        return CreateAction::make('createEvent')
            ->label(__('filament-calendar::events.resource.actions.create_event'))
            ->modalHeading(__('filament-calendar::events.resource.actions.create_event'))
            ->modalWidth('4xl')
            ->icon('heroicon-m-plus')
            ->model(Event::class)
            ->schema(fn (Schema $schema) => EventForm::configure($schema))
            ->mutateFormDataUsing(function (array $data): array {
                $data['user_id'] = auth()->id();
                return $data;
            })
            ->after(function () {
                // The widget will re-render automatically, computing the getEventsProperty again
            });
    }

    public function mount()
    {
        $this->currentMonth = now()->month;
        $this->currentYear = now()->year;
    }

    public function previousMonth()
    {
        $date = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1)->subMonth();
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
    }

    public function nextMonth()
    {
        $date = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1)->addMonth();
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
    }

    public function getEventsProperty(): Collection
    {
        $startOfMonth = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1)->startOfMonth()->startOfWeek(\Carbon\CarbonInterface::SUNDAY);
        $endOfMonth = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1)->endOfMonth()->endOfWeek(\Carbon\CarbonInterface::SATURDAY);

        return Event::where(function ($query) {
            if ($this->filter === 'my_events') {
                $query->where('user_id', auth()->id());
            } elseif ($this->filter === 'invited') {
                $query->whereHas('users', function ($q) {
                    $q->where('users.id', auth()->id());
                });
            } else {
                // all
                $query->where('user_id', auth()->id())
                    ->orWhereHas('users', function ($q) {
                        $q->where('users.id', auth()->id());
                    });
            }
        })
            ->where(function ($query) use ($startOfMonth, $endOfMonth) {
                $query->whereBetween('starts_at', [$startOfMonth, $endOfMonth])
                    ->orWhereBetween('ends_at', [$startOfMonth, $endOfMonth]);
            })
            ->with(['users' => function ($query) {
                $query->where('users.id', auth()->id());
            }])
            ->get();
    }

    #[On('echo-private:events,.EventUpdated')]
    #[On('echo-private:events,.EventDeleted')]
    #[On('echo-private:events,.EventCreated')]
    public function refreshEvents()
    {
        // Livewire automatically re-renders
    }

    public function getDaysProperty()
    {
        $start = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1)->startOfMonth()->startOfWeek(\Carbon\CarbonInterface::SUNDAY);
        $end = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1)->endOfMonth()->endOfWeek(\Carbon\CarbonInterface::SATURDAY);

        $days = [];
        $current = $start->copy();

        while ($current <= $end) {
            $days[] = [
                'date' => $current->copy(),
                'isCurrentMonth' => $current->month === (int) $this->currentMonth,
            ];
            $current->addDay();
        }

        return $days;
    }

    public function viewEventAction(): Action
    {
        return Action::make('viewEvent')
            ->hiddenLabel()
            ->modalIcon('heroicon-o-calendar-days')
            ->modalHeading(fn (array $arguments) => Event::find($arguments['event_id'])?->title)
            ->modalContent(fn (array $arguments) => view('filament-calendar::widgets.event-details-modal', [
                'event' => Event::with(['users' => fn ($q) => $q->where('users.id', auth()->id())])->find($arguments['event_id']),
            ]))
            ->modalSubmitAction(false)
            ->modalCancelAction(false)
            ->extraModalFooterActions(fn (array $arguments) => [
                Action::make('googleCalendar')
                    ->label(fn () => __('filament-calendar::calendar.actions.add_to_google'))
                    ->color('gray')
                    ->icon('heroicon-o-calendar-days')
                    ->url(function () use ($arguments) {
                        $event = Event::find($arguments['event_id']);
                        if (! $event) {
                            return '#';
                        }

                        $start = $event->starts_at->setTimezone('UTC')->format('Ymd\THis\Z');
                        $end = $event->ends_at->setTimezone('UTC')->format('Ymd\THis\Z');
                        $location = $event->format === 'virtual' ? $event->meeting_link : $event->location;

                        $url = 'https://calendar.google.com/calendar/render?action=TEMPLATE';
                        $url .= '&text='.urlencode($event->title);
                        $url .= '&dates='.$start.'/'.$end;
                        if ($event->description) {
                            $url .= '&details='.urlencode($event->description);
                        }
                        if ($location) {
                            $url .= '&location='.urlencode($location);
                        }

                        return $url;
                    })
                    ->openUrlInNewTab(),
                Action::make('confirm')
                    ->label(fn () => __('filament-calendar::calendar.actions.confirm_rsvp'))
                    ->color('success')
                    ->visible(function () use ($arguments) {
                        $event = Event::with(['users' => fn ($q) => $q->where('users.id', auth()->id())])->find($arguments['event_id']);

                        return $event && $event->users->first()?->pivot->rsvp_status !== 'confirmed';
                    })
                    ->action(function () use ($arguments) {
                        $event = Event::find($arguments['event_id']);
                        if ($event) {
                            $event->users()->updateExistingPivot(auth()->id(), ['rsvp_status' => 'confirmed']);
                            Notification::make()->title(__('filament-calendar::calendar.notifications.confirmed'))->success()->send();
                        }
                    }),
                Action::make('decline')
                    ->label(fn () => __('filament-calendar::calendar.actions.decline'))
                    ->color('danger')
                    ->visible(function () use ($arguments) {
                        $event = Event::with(['users' => fn ($q) => $q->where('users.id', auth()->id())])->find($arguments['event_id']);

                        return $event && $event->users->first()?->pivot->rsvp_status !== 'declined';
                    })
                    ->action(function () use ($arguments) {
                        $event = Event::find($arguments['event_id']);
                        if ($event) {
                            $event->users()->updateExistingPivot(auth()->id(), ['rsvp_status' => 'declined']);
                            Notification::make()->title(__('filament-calendar::calendar.notifications.declined'))->danger()->send();
                        }
                    }),
            ]);
    }
}
