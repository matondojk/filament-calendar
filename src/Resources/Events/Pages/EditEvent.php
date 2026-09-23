<?php

namespace Matondojk\FilamentEventCalendar\Resources\Events\Pages;

use Matondojk\FilamentEventCalendar\Resources\Events\EventResource;
use Matondojk\FilamentEventCalendar\Jobs\SendEventInvitationJob;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEvent extends EditRecord
{
    protected static string $resource = EventResource::class;

    public $oldUserIds = [];

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

}
