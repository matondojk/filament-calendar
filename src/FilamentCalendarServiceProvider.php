<?php

namespace Matondojk\FilamentCalendar;

use Illuminate\Support\ServiceProvider;

class FilamentCalendarServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'filament-calendar');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'filament-calendar');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/filament-calendar'),
            ], 'filament-calendar-views');

            $this->publishes([
                __DIR__ . '/../resources/lang' => resource_path('lang/vendor/filament-calendar'),
            ], 'filament-calendar-translations');

            $this->publishes([
                __DIR__ . '/../database/migrations' => database_path('migrations'),
            ], 'filament-calendar-migrations');

            $this->publishes([
                __DIR__ . '/../src/Resources/Events' => app_path('Filament/Resources/EventResource'),
            ], 'filament-calendar-resource');

            $this->commands([
                \Matondojk\FilamentCalendar\Commands\PublishResourceCommand::class,
            ]);
        }

        \Filament\Support\Facades\FilamentAsset::register([
            \Filament\Support\Assets\Css::make('filament-calendar-styles', __DIR__ . '/../resources/dist/calendar.css'),
        ], 'matondojk/filament-calendar');
    }

    public function register(): void
    {
        //
    }
}
