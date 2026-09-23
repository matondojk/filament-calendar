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

## Usage

To use the calendar, you simply need to register the `CalendarWidget` in your Filament Panel Provider (usually `AdminPanelProvider.php`), or in a specific page.

Registering globally in your Panel Provider:

```php
use Matondojk\FilamentCalendar\Widgets\CalendarWidget;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->widgets([
            Widgets\AccountWidget::class,
            Widgets\FilamentInfoWidget::class,
            CalendarWidget::class,
        ]);
}
```

Or registering in a specific Page (e.g. `Dashboard.php`):

```php
use Matondojk\FilamentCalendar\Widgets\CalendarWidget;

protected function getWidgets(): array
{
    return [
        CalendarWidget::class,
    ];
}
```

The calendar will automatically pull events using the default `Event` model provided by the package.

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
