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

Optionally, you can publish the views using:

```bash
php artisan vendor:publish --tag="filament-calendar-views"
```

## Usage

Register the plugin in your Filament panel configuration:

```php
use Matondojk\FilamentCalendar\FilamentCalendarPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            FilamentCalendarPlugin::make()
                ->selectable()
                ->editable(),
        ]);
}
```

### Adding the Calendar Widget

You can add the calendar widget to any of your pages or dashboard by returning it in the `getWidgets()` method:

```php
use Matondojk\FilamentCalendar\Widgets\CalendarWidget;

protected function getWidgets(): array
{
    return [
        CalendarWidget::class,
    ];
}
```

### Providing Events

To display events, create an Eloquent model and implement the `HasCalendarEvents` interface, or simply pass a closure to the calendar configuration to fetch your events dynamically.

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
