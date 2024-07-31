<?php

namespace App\Actions\TimeEntry;

use Carbon\Carbon;

class GetDateTimesFromDateAndTime
{
    /**
     * Get start_time and end_time from a single date and time.
     *
     */
    public function handle(array $data): array
    {
        if ($data['date']) {
            if ($data['start']) {
                $data['start_time'] = Carbon::createFromFormat('Y-m-d H:i', $data['date'] . ' ' . $data['start']);
            }

            if ($data['end']) {
                $data['end_time'] = Carbon::createFromFormat('Y-m-d H:i', $data['date'] . ' ' . $data['end']);
            }
        }

        return $data;
    }

}
