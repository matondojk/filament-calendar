<?php

namespace Matondojk\FilamentEventCalendar\Resources\Events\Pages;

use Matondojk\FilamentEventCalendar\Resources\Events\EventResource;
use Matondojk\FilamentEventCalendar\Jobs\SendEventInvitationJob;
use Filament\Resources\Pages\CreateRecord;

class CreateEvent extends CreateRecord
{
    protected static string $resource = EventResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        return $data;
    }

}
