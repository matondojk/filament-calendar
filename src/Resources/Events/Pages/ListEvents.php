<?php

namespace Matondojk\FilamentCalendar\Resources\Events\Pages;

use Matondojk\FilamentCalendar\Resources\Events\EventResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;

class ListEvents extends ListRecords
{
    protected static string $resource = EventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(fn() => __('events.resource.tabs.all'))
                ->icon(Heroicon::OutlinedGlobeAlt),
            'my_events' => Tab::make(fn() => __('events.resource.tabs.my_events'))
                ->icon(Heroicon::OutlinedUser)
                ->modifyQueryUsing(fn (Builder $query) => $query->where('user_id', auth()->id())),
            'invited' => Tab::make(fn() => __('events.resource.tabs.invited'))
                ->icon(Heroicon::OutlinedEnvelope)
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('users', fn ($q) => $q->where('users.id', auth()->id()))),
        ];
    }
}
