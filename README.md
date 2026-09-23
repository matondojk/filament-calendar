# Filament Event Calendar

A professional, fully customizable, and responsive event management plugin designed specifically for Filament v5.

## Screenshots

**Calendar Dashboard Widget:**
![Calendar Widget](screenshots/calendar-widget.jpeg)

**Events Table & Views:**
![All Events View](screenshots/all-events-view.jpeg)

**Google Calendar Integration:**
![Adding Event to Google Calendar](screenshots/adding-event-to-google-calendar.jpeg)

## Features

- **In-Person and Virtual Events:** Create physical events with locations, or virtual meetings with direct links (Google Meet, Zoom, Teams, etc.).
- **Smart Visibility & Invitations:** Users only see events they created or events they have been explicitly invited to.
- **RSVP Tracking:** Invited users can confirm or decline their presence directly through the calendar.
- **Automated Email Notifications:** The system sends an email invitation automatically when users are added to an event.
- **Daily Reminders:** Configurable job to send warnings 24 hours before an event starts.
- **Google Calendar Integration:** Allows attendees to add the event (including dates, description, and link/location) directly to their personal Google Calendar with a single click.
- **Perfect UI Integration:** Matches the native Filament v5 Zinc theme seamlessly in both Light and Dark modes.

## Requirements

- PHP 8.2+
- Laravel 10.0+ / 11.0+
- Filament v5

## Installation

You can install the package via Composer:

```bash
composer require matondojk/filament-event-calendar
```

Publish and run the migrations to create the required database tables (`events` and `event_user`):

```bash
php artisan vendor:publish --tag="filament-event-calendar-migrations"
php artisan migrate
```

Optionally, you can publish the configuration file to customize the resource visibility and menu sorting:

```bash
php artisan vendor:publish --tag="filament-event-calendar-config"
```

## Plugin Registration

To use the calendar widget and the event resource, you must register the plugin in your Filament panel configuration.

Open your `app/Providers/Filament/AdminPanelProvider.php` and add the plugin:

```php
use Matondojk\FilamentEventCalendar\FilamentEventCalendarPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->plugins([
            FilamentEventCalendarPlugin::make(),
        ]);
}
```

## Scheduling Reminders

The package includes a job (`EventReminderJob`) that checks for upcoming events exactly 24 hours before they start. It sends both database notifications and email reminders to all confirmed or pending guests.

To enable this feature, you must schedule the job to run hourly. 

If you are using Laravel 11, add the following to your `routes/console.php`:

```php
use Matondojk\FilamentEventCalendar\Jobs\EventReminderJob;
use Illuminate\Support\Facades\Schedule;

Schedule::job(new EventReminderJob)->hourly();
```

If you are using Laravel 10, add it to the `schedule` method in `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule): void
{
    $schedule->job(new \Matondojk\FilamentEventCalendar\Jobs\EventReminderJob)->hourly();
}
```

*Note: Ensure your server has the Laravel scheduler configured (cron).*

## Configuration

If you published the configuration file, it will be located at `config/filament-event-calendar.php`. 

You can define if the `EventResource` should appear in the left sidebar and in which position:

```php
return [
    // Determine if the Events link should appear in the navigation menu.
    'should_register_navigation' => true,

    // Set the navigation sort order for the Events link.
    'navigation_sort' => 1,
];
```

## Usage Concepts

### Visibility and Privacy
Privacy is built-in by default. When an event is created, it belongs to the creator. The event will only appear on the calendar and table of the creator and the guests selected in the "Participants" field. The filtering tabs allow users to quickly switch between "All Events", "My Events", and "Invited".

### Google Calendar Integration
When an event is viewed, a button to "Add to Google Calendar" is presented. This button dynamically generates a URL populated with the event's title, description, start/end dates, and location (or virtual meeting link). Clicking it opens the Google Calendar creation page pre-filled.

## License

The MIT License (MIT).
