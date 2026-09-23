<?php

namespace Matondojk\FilamentCalendar\Resources\Events;

use Matondojk\FilamentCalendar\Resources\Events\Pages\CreateEvent;
use Matondojk\FilamentCalendar\Resources\Events\Pages\EditEvent;
use Matondojk\FilamentCalendar\Resources\Events\Pages\ListEvents;
use Matondojk\FilamentCalendar\Resources\Events\Schemas\EventForm;
use Matondojk\FilamentCalendar\Resources\Events\Tables\EventsTable;
use Matondojk\FilamentCalendar\Models\Event;
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

    public static function getNavigationLabel(): string
    {
        return __('filament-calendar::events.resource.navigation_label');
    }

    public static function getModelLabel(): string
    {
        return __('filament-calendar::events.resource.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-calendar::events.resource.plural_model_label');
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
