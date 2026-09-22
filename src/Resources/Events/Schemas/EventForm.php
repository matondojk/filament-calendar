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
            ->columns(['default' => 1, 'md' => 3])
            ->components([
                \Filament\Schemas\Components\Group::make()
                    ->columnSpan(['default' => 1, 'md' => 2])
                    ->schema([
                        Section::make(fn() => __('events.resource.form.event_details'))
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                TextInput::make('title')
                                    ->label(fn() => __('events.resource.form.title'))
                                    ->required()
                                    ->maxLength(255),

                                Textarea::make('description')
                                    ->label(fn() => __('events.resource.form.description'))
                                    ->rows(3),

                                Grid::make(2)->schema([
                                    DateTimePicker::make('starts_at')
                                        ->label(fn() => __('events.resource.form.starts_at'))
                                        ->required()
                                        ->native(false),

                                    DateTimePicker::make('ends_at')
                                        ->label(fn() => __('events.resource.form.ends_at'))
                                        ->required()
                                        ->after('starts_at')
                                        ->native(false),
                                ]),
                            ]),
                    ]),

                \Filament\Schemas\Components\Group::make()
                    ->columnSpan(['default' => 1, 'md' => 1])
                    ->schema([
                        Section::make(fn() => __('events.resource.form.format'))
                            ->icon('heroicon-o-map-pin')
                            ->schema([
                                Radio::make('format')
                                    ->label('')
                                    ->options(fn() => [
                                        'in_person' => __('events.resource.form.in_person'),
                                        'virtual' => __('events.resource.form.virtual'),
                                    ])
                                    ->default('in_person')
                                    ->inline()
                                    ->live()
                                    ->required(),

                                Select::make('platform')
                                    ->label(fn() => __('events.resource.form.platform'))
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
                                    ->label(fn() => __('events.resource.form.meeting_link'))
                                    ->url()
                                    ->visible(fn ($get) => $get('format') === 'virtual')
                                    ->required(fn ($get) => $get('format') === 'virtual')
                                    ->maxLength(255)
                                    ->suffixAction(
                                        Action::make('open')
                                            ->icon('heroicon-o-arrow-top-right-on-square')
                                            ->tooltip(fn() => __('events.resource.form.open_link'))
                                            ->url(fn ($state) => $state)
                                            ->openUrlInNewTab()
                                            ->visible(fn ($state) => filled($state))
                                    ),

                                TextInput::make('location')
                                    ->label(fn() => __('events.resource.form.location'))
                                    ->visible(fn ($get) => $get('format') === 'in_person')
                                    ->required(fn ($get) => $get('format') === 'in_person')
                                    ->maxLength(255),
                            ]),

                        Section::make(fn() => __('events.resource.form.participants'))
                            ->icon('heroicon-o-users')
                            ->schema([
                                Select::make('users')
                                    ->label(fn() => __('events.resource.form.guests'))
                                    ->relationship('users', 'name')
                                    ->multiple()
                                    ->searchable()
                                    ->preload(),
                            ]),
                    ]),
            ]);
    }
}
