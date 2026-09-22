<?php

return [
    'notifications' => [
        'new_invitation_title' => 'New Invitation: :title',
        'new_invitation_body' => 'You have been invited to a new event on :date',
        'reminder_title' => 'Reminder: :title',
        'reminder_body' => 'The event will start in 24 hours: :date',
    ],
    'emails' => [
        'invitation_subject' => 'Invitation: :title',
        'reminder_subject' => 'Event Reminder: :title',
        'reminder_body' => 'Reminder: The event \':title\' will start in 24 hours on :date. :location',
        'invitation_header' => 'You have been invited to an event!',
        'details' => 'Details:',
        'footer' => 'You can access the dashboard to confirm or decline your RSVP. Also, an .ics calendar file is attached to this email so you can add this event to your personal calendar.',
    ],
    'resource' => [
        'navigation_label' => 'Events',
        'model_label' => 'Event',
        'plural_model_label' => 'Events',
        'form' => [
            'event_details' => 'Event Details',
            'event_details_desc' => 'Main information about the event.',
            'title' => 'Event Title',
            'description' => 'Description',
            'starts_at' => 'Starts At',
            'ends_at' => 'Ends At',
            'format' => 'Event Format',
            'in_person' => 'In Person',
            'virtual' => 'Virtual',
            'platform' => 'Platform',
            'meeting_link' => 'Meeting Link',
            'open_link' => 'Open Link',
            'location' => 'Physical Location',
            'participants' => 'Participants',
            'participants_desc' => 'Select the users to invite to this event.',
            'guests' => 'Guests',
        ],
        'table' => [
            'title' => 'Title',
            'creator' => 'Creator',
            'starts_at' => 'Starts At',
            'ends_at' => 'Ends At',
            'format' => 'Format',
            'where' => 'Where',
            'guests' => 'Guests',
            'upcoming' => 'Upcoming Events',
        ],
        'actions' => [
            'create_event' => 'New Event',
        ],
        'tabs' => [
            'all' => 'All Events',
            'my_events' => 'My Events',
            'invited' => 'Invited',
        ],
    ],
];
