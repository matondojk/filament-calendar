<?php

namespace Matondojk\FilamentEventCalendar\Resources\Events;

use Matondojk\FilamentEventCalendar\Resources\Events\Pages\CreateEvent;
use Matondojk\FilamentEventCalendar\Resources\Events\Pages\EditEvent;
use Matondojk\FilamentEventCalendar\Resources\Events\Pages\ListEvents;
use Matondojk\FilamentEventCalendar\Resources\Events\Schemas\EventForm;
use Matondojk\FilamentEventCalendar\Resources\Events\Tables\EventsTable;
use Matondojk\FilamentEventCalendar\Models\Event;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    public static function shouldRegisterNavigation(): bool
    {
        return config('filament-event-calendar.should_register_navigation', true);
    }

    public static function getNavigationSort(): ?int
    {
        return config('filament-event-calendar.navigation_sort', 1);
    }

    public static function getNavigationLabel(): string
    {
        return __('filament-event-calendar::events.resource.navigation_label');
    }

    public static function getModelLabel(): string
    {
        return __('filament-event-calendar::events.resource.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-event-calendar::events.resource.plural_model_label');
    }

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return EventForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EventsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEvents::route('/'),
            'create' => CreateEvent::route('/create'),
            'edit' => EditEvent::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where(function (Builder $query) {
                $query->where('user_id', auth()->id())
                    ->orWhereHas('users', function (Builder $q) {
                        $q->where('users.id', auth()->id());
                    });
            });
    }
}
