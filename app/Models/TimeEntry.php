<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimeEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'price_per_hour',
        'start_time',
        'end_time',
        'lunching_time',
        'description'
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * @var Datetime start_time
     * @var Datetime lunching_time
     * @var Datetime end_time
     */
    public function calculateTotalWorkedHours(): string
    {
        $startTime = Carbon::parse($this->start_time);
        $endTime = Carbon::parse($this->end_time);
        $lunchingTime = Carbon::parse($this->lunching_time);

        // Calculate the total minutes between $startTime and $endTime
        $totalMinutes = $startTime->diffInMinutes($endTime);
        // $totalHours = $startTime->diffInHours($endTime);

        if ($this->lunching_time) {
            $totalMinutesWithoutLunching = 0;
            if ($lunchingTime->minute && $lunchingTime->hour) {
                $lunchingHourInMinutes = $lunchingTime->hour * 60;
                $totalMinutesLunching = $lunchingHourInMinutes + $lunchingTime->minute;
                $totalMinutesWithoutLunching = $totalMinutes - $totalMinutesLunching;
                return date('H:i', mktime(0, $totalMinutesWithoutLunching));
            }

            if ($lunchingTime->minute) {
                $totalMinutesWithoutLunching = $totalMinutes - $lunchingTime->minute;
                return date('H:i', mktime(0, $totalMinutesWithoutLunching));
            }

            if ($lunchingTime->hour) {
                $lunchingHourInMinutes = $lunchingTime->hour * 60;
                $totalMinutesWithoutLunching = $totalMinutes - $lunchingHourInMinutes;
                return date('H:i', mktime(0, $totalMinutesWithoutLunching));
            }
        }

        // Convert total minutes to hours (as a float)
        $totalHours = $totalMinutes / 60;

        return $totalHours;
    }

    /**
     * @var Datetime start_time
     * @var Datetime lunching_time
     * @var Datetime end_time
     */
    public function calculateTotalWorkedHoursFloat(): float
    {
        $startTime = Carbon::parse($this->start_time);
        $endTime = Carbon::parse($this->end_time);
        $lunchingTime = Carbon::parse($this->lunching_time);

        // Calculate the total minutes between $startTime and $endTime
        $totalMinutes = $startTime->diffInMinutes($endTime);

        if ($this->lunching_time) {
            // Extract the minutes from $lunchingTime
            $lunchingMinutes = $lunchingTime->minute;

            // Adjust the total minutes by subtracting the lunching minutes
            $totalMinutesWithoutLunch = $totalMinutes - $lunchingMinutes;

            // Convert total minutes to hours (as a float)
            $totalHoursWithoutLunch = $totalMinutesWithoutLunch / 60;

            // Extract the hours from $lunchingTime
            $lunchingHours = $lunchingTime->hour;

            // Adjust the total hours by subtracting the lunching hours
            $totalHoursWithoutLunch -= $lunchingHours;

            return $totalHoursWithoutLunch;

        } else {
            // Convert total minutes to hours (as a float)
            $totalHours = $totalMinutes / 60;

            return $totalHours;
        }
    }
}
