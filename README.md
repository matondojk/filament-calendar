# Filament Calendar

A robust and customizable calendar plugin designed specifically for **Filament v5**.

## Requirements

- PHP 8.2+
- Laravel 11.0+
- Filament v5.0+

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
            FilamentCalendarPlugin::make(),
        ]);
}
```

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
