<?php

namespace App\Concerns;

use Illuminate\Support\Facades\DB;

trait InteractsWithFilter
{
    public function getYearsFilter(string $table)
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