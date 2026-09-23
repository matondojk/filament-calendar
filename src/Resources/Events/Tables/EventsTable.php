<?php

namespace Matondojk\FilamentCalendar\Resources\Events\Tables;

use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EventsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordAction('view')
            ->columns([
                TextColumn::make('title')
                    ->label(fn() => __('filament-calendar::events.resource.table.title'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('owner.name')
                    ->label(fn() => __('filament-calendar::events.resource.table.creator'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('starts_at')
                    ->label(fn() => __('filament-calendar::events.resource.table.starts_at'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('ends_at')
                    ->label(fn() => __('filament-calendar::events.resource.table.ends_at'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('format')
                    ->label(fn() => __('filament-calendar::events.resource.table.format'))
                    ->badge()
                    ->formatStateUsing(fn (string $state) => $state === 'virtual' ? __('filament-calendar::events.resource.form.virtual') : __('filament-calendar::events.resource.form.in_person'))
                    ->color(fn (string $state): string => match ($state) {
                        'virtual' => 'info',
                        'in_person' => 'success',
                        default => 'gray',
                    }),

                TextColumn::make('location_or_platform')
                    ->label(fn() => __('filament-calendar::events.resource.table.where'))
                    ->getStateUsing(function ($record) {
                        if ($record->format === 'virtual') {
                            return $record->platform;
                        }

                        return $record->location;
                    })
                    ->description(fn ($record) => $record->format === 'virtual' ? $record->meeting_link : null)
                    ->searchable(['location', 'platform', 'meeting_link'])
                    ->toggleable(),

                TextColumn::make('users_count')
                    ->label(fn() => __('filament-calendar::events.resource.table.guests'))
                    ->counts('users')
                    ->badge()
                    ->color('primary')
                    ->sortable(),
            ])
            ->defaultSort('starts_at', 'desc')
            ->filters([
                Filter::make('upcoming')
                    ->label(fn() => __('filament-calendar::events.resource.table.upcoming'))
                    ->query(fn (Builder $query): Builder => $query->where('starts_at', '>=', Carbon::now()))
                    ->toggle(),
            ])
            ->recordActions([
                Action::make('add_to_calendar')
                    ->icon('heroicon-o-calendar-days')
                    ->label(fn() => __('filament-calendar::events.resource.actions.add_to_calendar'))
                    ->url(function ($record) {
                        $start = $record->starts_at->setTimezone('UTC')->format('Ymd\THis\Z');
                        $end = $record->ends_at->setTimezone('UTC')->format('Ymd\THis\Z');
                        $title = urlencode((string) $record->title);
                        $details = urlencode((string) $record->description);
                        $location = urlencode((string) ($record->format === 'virtual' ? ($record->meeting_link ?? $record->platform) : $record->location));
                        
                        return "https://calendar.google.com/calendar/render?action=TEMPLATE&text={$title}&dates={$start}/{$end}&details={$details}&location={$location}";
                    })
                    ->openUrlInNewTab(),
                \Filament\Actions\ViewAction::make(),
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
