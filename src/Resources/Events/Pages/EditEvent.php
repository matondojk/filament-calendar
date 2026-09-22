<?php

namespace Matondojk\FilamentCalendar\Resources\Events\Pages;

use Matondojk\FilamentCalendar\Resources\Events\EventResource;
use Matondojk\FilamentCalendar\Jobs\SendEventInvitationJob;
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
