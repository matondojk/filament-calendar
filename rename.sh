#!/bin/bash

# Rename files
mv src/FilamentCalendarServiceProvider.php src/FilamentEventCalendarServiceProvider.php
mv src/FilamentCalendarPlugin.php src/FilamentEventCalendarPlugin.php
mv config/filament-calendar.php config/filament-event-calendar.php

# Search and replace in all files
find src resources config database -type f -exec perl -pi -e 's/FilamentCalendarServiceProvider/FilamentEventCalendarServiceProvider/g' {} +
find src resources config database -type f -exec perl -pi -e 's/FilamentCalendarPlugin/FilamentEventCalendarPlugin/g' {} +
find src resources config database -type f -exec perl -pi -e 's/Matondojk\\FilamentCalendar/Matondojk\\FilamentEventCalendar/g' {} +
find src resources config database -type f -exec perl -pi -e 's/filament-calendar/filament-event-calendar/g' {} +

# Revert specific strings that might be over-replaced
# Like the class Event in Models (though it should be fine)
# wait, replacing filament-calendar with filament-event-calendar is mostly safe.
# Let's check tailwind config
perl -pi -e 's/filament-calendar/filament-event-calendar/g' tailwind.config.js
perl -pi -e 's/filament-calendar/filament-event-calendar/g' composer.json
perl -pi -e 's/FilamentCalendar/FilamentEventCalendar/g' composer.json

# Update README
perl -pi -e 's/filament-calendar/filament-event-calendar/g' README.md
perl -pi -e 's/FilamentCalendar/FilamentEventCalendar/g' README.md
perl -pi -e 's/Filament Calendar/Filament Event Calendar/g' README.md

# Update calendar.css references (it was renamed from filament-calendar to filament-event-calendar)
# Let's rename the compiled css output
mv resources/dist/calendar.css resources/dist/event-calendar.css
# wait, the tailwind config and package json might have hardcoded paths
perl -pi -e 's/calendar\.css/event-calendar.css/g' package.json
mv resources/css/calendar.css resources/css/event-calendar.css
