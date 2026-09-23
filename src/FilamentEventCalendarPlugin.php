<?php

namespace Matondojk\FilamentEventCalendar;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Matondojk\FilamentEventCalendar\Resources\Events\EventResource;
use Matondojk\FilamentEventCalendar\Widgets\CalendarWidget;

class FilamentEventCalendarPlugin implements Plugin
{
    public static function make(): static
    {
        return app(static::class);
    }

    public function getId(): string
    {
        return 'filament-event-calendar';
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
