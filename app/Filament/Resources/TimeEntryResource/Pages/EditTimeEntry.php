<?php

namespace App\Filament\Resources\TimeEntryResource\Pages;

use App\Actions\TimeEntry\GetDateTimesFromDateAndTime;
use App\Filament\Resources\TimeEntryResource;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTimeEntry extends EditRecord
{
    protected static string $resource = TimeEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (isset($data['start_time'])) {
            $startDateTime = new \DateTime($data['start_time']);
            $data['date'] = $startDateTime->format('Y-m-d');
            $data['start'] = $startDateTime->format('H:i:s');
        }

        if (isset($data['end_time'])) {
            $endDateTime = new \DateTime($data['end_time']);
            $data['end'] = $endDateTime->format('H:i:s');
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return (new GetDateTimesFromDateAndTime())->handle($data);
    }
}
