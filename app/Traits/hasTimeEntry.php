<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait hasTimeEntry {

  /**
   * @var Datetime start_time
   * @var Datetime lunching_time
   * @var Datetime end_time
   */
  public function calculateTotalWorkedHours($start_time, $lunching_time, $end_time): string
  {
      $startTime = Carbon::parse($start_time);
      $endTime = Carbon::parse($lunching_time);
      $lunchingTime = Carbon::parse($end_time);

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

  public function getYearsFilter(string $table): array
  {
      $currentYear = now()->year;
          
      $earliestYear = DB::table($table)
          ->orderBy('created_at', 'asc')
          ->value('created_at');

      $startYear = $earliestYear ? (int) \Carbon\Carbon::parse($earliestYear)->year : $currentYear;

      $years = range($startYear, $currentYear);

      return collect($years)->mapWithKeys(fn($year) => [$year => (string) $year])->toArray();
  }

}