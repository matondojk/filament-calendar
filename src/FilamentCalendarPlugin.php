<?php

namespace Matondojk\FilamentCalendar;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Matondojk\FilamentCalendar\Resources\Events\EventResource;
use Matondojk\FilamentCalendar\Widgets\CalendarWidget;

class FilamentCalendarPlugin implements Plugin
{
    public static function make(): static
    {
        return app(static::class);
    }

    public function getId(): string
    {
        return 'filament-calendar';
    }

    public function register(Panel $panel): void
    {
        $panel->resources([
            EventResource::class,
        ]);

        $panel->widgets([
            CalendarWidget::class,
        ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
