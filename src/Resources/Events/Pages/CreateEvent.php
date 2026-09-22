<?php

namespace Matondojk\FilamentCalendar\Resources\Events\Pages;

use Matondojk\FilamentCalendar\Resources\Events\EventResource;
use Matondojk\FilamentCalendar\Jobs\SendEventInvitationJob;
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
