# Filament Calendar

A robust and customizable calendar plugin designed specifically for **Filament v5**.

## Requirements

- Laravel
- Filament v5

## Installation

You can install the package via composer:

```bash
composer require matondojk/filament-calendar
```

You must run the migrations for the calendar events to work properly. You can publish and run them with:

```bash
php artisan vendor:publish --tag="filament-calendar-migrations"
php artisan migrate
```

Optionally, you can publish the views and translations using:

```bash
php artisan vendor:publish --tag="filament-calendar-views"
php artisan vendor:publish --tag="filament-calendar-translations"
```

You can also publish the configuration file to customize the plugin's behavior:

```bash
php artisan vendor:publish --tag="filament-calendar-config"
```

## Usage

To use the calendar, register the `FilamentCalendarPlugin` in your Filament Panel Provider (usually `AdminPanelProvider.php`). This will automatically register both the `CalendarWidget` and the `EventResource`.

```php
use Matondojk\FilamentCalendar\FilamentCalendarPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->plugins([
            FilamentCalendarPlugin::make(),
        ]);
}
```

If you prefer to register the widget manually in a specific page without registering the plugin globally:

```php
use Matondojk\FilamentCalendar\Widgets\CalendarWidget;

protected function getWidgets(): array
{
    return [
        CalendarWidget::class,
    ];
}
```

## Configuration

In `config/filament-calendar.php`, you can customize how the package behaves:

- `should_register_navigation`: Show or hide the Event Resource from the main sidebar.
- `navigation_sort`: Control where the Event Resource appears in the sidebar.

## Customizing the Event Resource

If you need to customize the table columns, form fields, or logic of the Event Resource, you can publish the complete resource directly into your application's `app/Filament/Resources` directory by running:

```bash
php artisan filament-calendar:publish-resource
```

This command will copy the `EventResource` (along with its Pages, Schemas, and Tables) and automatically update all the namespaces to match your App namespace. After running this, you'll find the customizable resource at `app/Filament/Resources/EventResource/EventResource.php`!

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

If you discover any security related issues, please email instead of using the issue tracker.

## Credits

- [matondojk](https://github.com/matondojk)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
