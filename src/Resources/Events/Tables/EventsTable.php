<?php

namespace Matondojk\FilamentCalendar\Resources\Events\Tables;

use Carbon\Carbon;
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
            ->columns([
                TextColumn::make('title')
                    ->label(fn() => __('events.resource.table.title'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('owner.name')
                    ->label(fn() => __('events.resource.table.creator'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('starts_at')
                    ->label(fn() => __('events.resource.table.starts_at'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('ends_at')
                    ->label(fn() => __('events.resource.table.ends_at'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('format')
                    ->label(fn() => __('events.resource.table.format'))
                    ->badge()
                    ->formatStateUsing(fn (string $state) => $state === 'virtual' ? __('events.resource.form.virtual') : __('events.resource.form.in_person'))
                    ->color(fn (string $state): string => match ($state) {
                        'virtual' => 'info',
                        'in_person' => 'success',
                        default => 'gray',
                    }),

                TextColumn::make('location_or_platform')
                    ->label(fn() => __('events.resource.table.where'))
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
                    ->label(fn() => __('events.resource.table.guests'))
                    ->counts('users')
                    ->badge()
                    ->color('primary')
                    ->sortable(),
            ])
            ->defaultSort('starts_at', 'desc')
            ->filters([
                Filter::make('upcoming')
                    ->label(fn() => __('events.resource.table.upcoming'))
                    ->query(fn (Builder $query): Builder => $query->where('starts_at', '>=', Carbon::now()))
                    ->toggle(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
