<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TimeEntry extends Model
{
    use HasFactory;

    protected $fillable = ['start_time', 'end_time', 'lunching_time'];

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
      $totalHours = $startTime->diffInHours($endTime);
      
      // Extract the minutes from $lunchingTime
      $lunchingMinutes = $lunchingTime->minute;

      // Adjust the total minutes by subtracting the lunching minutes
      $totalMinutesWithoutLunch = $totalMinutes - $lunchingMinutes;

      // Convert total minutes to hours (as a float)
      $totalHoursWithoutLunch = $totalMinutesWithoutLunch / 60;
      $totalMinutesWithoutLunch = $totalMinutesWithoutLunch % 60;

      // Extract the hours from $lunchingTime
      $lunchingHours = $lunchingTime->hour;
      
      // Adjust the total hours by subtracting the lunching hours
      $totalHoursWithoutLunchInt = $totalHours - $lunchingHours;

      if ($totalMinutesWithoutLunch > 0) {
          return $totalHoursWithoutLunchInt . ':' . $totalMinutesWithoutLunch;
      }

      return $totalHoursWithoutLunchInt;
  }
}
