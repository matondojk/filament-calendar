<?php

namespace Matondojk\FilamentCalendar\Resources\Events\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Actions\Action;

class EventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                \Filament\Schemas\Components\Group::make()
                    ->columnSpan(2)
                    ->schema([
                        Section::make(fn() => __('filament-calendar::events.resource.form.event_details'))
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                TextInput::make('title')
                                    ->label(fn() => __('filament-calendar::events.resource.form.title'))
                                    ->required()
                                    ->maxLength(255),

                                Textarea::make('description')
                                    ->label(fn() => __('filament-calendar::events.resource.form.description'))
                                    ->rows(3),

                                Grid::make(2)->schema([
                                    DateTimePicker::make('starts_at')
                                        ->label(fn() => __('filament-calendar::events.resource.form.starts_at'))
                                        ->required()
                                        ->native(false),

                                    DateTimePicker::make('ends_at')
                                        ->label(fn() => __('filament-calendar::events.resource.form.ends_at'))
                                        ->required()
                                        ->after('starts_at')
                                        ->native(false),
                                ]),
                            ]),

                        Section::make(fn() => __('filament-calendar::events.resource.form.participants'))
                            ->icon('heroicon-o-users')
                            ->schema([
                                Select::make('users')
                                    ->label(fn() => __('filament-calendar::events.resource.form.guests'))
                                    ->relationship('users', 'name')
                                    ->multiple()
                                    ->searchable()
                                    ->preload(),
                            ]),
                    ]),

                \Filament\Schemas\Components\Group::make()
                    ->columnSpan(1)
                    ->schema([
                        Section::make(fn() => __('filament-calendar::events.resource.form.format'))
                            ->icon('heroicon-o-map-pin')
                            ->schema([
                                Radio::make('format')
                                    ->label('')
                                    ->options(fn() => [
                                        'in_person' => __('filament-calendar::events.resource.form.in_person'),
                                        'virtual' => __('filament-calendar::events.resource.form.virtual'),
                                    ])
                                    ->default('in_person')
                                    ->inline()
                                    ->live()
                                    ->required(),

                                Select::make('platform')
                                    ->label(fn() => __('filament-calendar::events.resource.form.platform'))
                                    ->options([
                                        'Google Meet' => 'Google Meet',
                                        'Zoom' => 'Zoom',
                                        'Microsoft Teams' => 'Microsoft Teams',
                                        'Whatsapp' => 'Whatsapp',
                                        'Eventbrite' => 'Eventbrite',
                                        'Sympla' => 'Sympla',
                                        'Discord' => 'Discord',
                                        'Twitch' => 'Twitch',
                                        'Outra' => 'Outra',
                                    ])
                                    ->visible(fn ($get) => $get('format') === 'virtual')
                                    ->required(fn ($get) => $get('format') === 'virtual')
                                    ->searchable(),

                                TextInput::make('meeting_link')
                                    ->label(fn() => __('filament-calendar::events.resource.form.meeting_link'))
                                    ->url()
                                    ->visible(fn ($get) => $get('format') === 'virtual')
                                    ->required(fn ($get) => $get('format') === 'virtual')
                                    ->maxLength(255)
                                    ->suffixAction(
                                        Action::make('open')
                                            ->icon('heroicon-o-arrow-top-right-on-square')
                                            ->tooltip(fn() => __('filament-calendar::events.resource.form.open_link'))
                                            ->url(fn ($state) => $state)
                                            ->openUrlInNewTab()
                                            ->visible(fn ($state) => filled($state))
                                    ),

                                TextInput::make('location')
                                    ->label(fn() => __('filament-calendar::events.resource.form.location'))
                                    ->visible(fn ($get) => $get('format') === 'in_person')
                                    ->required(fn ($get) => $get('format') === 'in_person')
                                    ->maxLength(255),
                            ]),
                    ]),
            ]);
    }
}
