<?php

namespace Matondojk\FilamentEventCalendar;

use Illuminate\Support\ServiceProvider;

class FilamentEventCalendarServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'filament-event-calendar');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'filament-event-calendar');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/filament-event-calendar'),
            ], 'filament-event-calendar-views');

            $this->publishes([
                __DIR__ . '/../resources/lang' => resource_path('lang/vendor/filament-event-calendar'),
            ], 'filament-event-calendar-translations');

            $this->publishes([
                __DIR__ . '/../database/migrations' => database_path('migrations'),
            ], 'filament-event-calendar-migrations');

            $this->publishes([
                __DIR__ . '/../src/Resources/Events' => app_path('Filament/Resources/EventResource'),
            ], 'filament-event-calendar-resource');

            $this->publishes([
                __DIR__ . '/../config/filament-event-calendar.php' => config_path('filament-event-calendar.php'),
            ], 'filament-event-calendar-config');

            $this->commands([
                \Matondojk\FilamentEventCalendar\Commands\PublishResourceCommand::class,
            ]);
        }

        \Filament\Support\Facades\FilamentAsset::register([
            \Filament\Support\Assets\Css::make('filament-event-calendar-styles', __DIR__ . '/../resources/dist/event-calendar.css'),
        ], 'matondojk/filament-event-calendar');
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/filament-event-calendar.php', 'filament-event-calendar'
        );
    }
}
