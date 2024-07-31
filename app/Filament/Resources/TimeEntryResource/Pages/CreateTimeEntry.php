<?php

namespace App\Filament\Resources\TimeEntryResource\Pages;

use App\Actions\TimeEntry\GetDateTimesFromDateAndTime;
use App\Filament\Resources\TimeEntryResource;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use App\Actions\TimeEntry\CreateTimeEntry as CreateTimeEntryAction;

class CreateTimeEntry extends CreateRecord
{
    protected static string $resource = TimeEntryResource::class;

    protected static ?string $title = 'Registar hora';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Hora registada com sucesso!';
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return (new GetDateTimesFromDateAndTime())->handle($data);
    }
}
