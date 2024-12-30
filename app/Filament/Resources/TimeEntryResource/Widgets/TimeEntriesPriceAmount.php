<?php

namespace App\Filament\Resources\TimeEntryResource\Widgets;

use App\Models\TimeEntry;
use App\Traits\hasTimeEntry;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TimeEntriesPriceAmount extends ChartWidget
{
    use hasTimeEntry;

    protected static ?string $heading = 'Pagamentos a receber';

    public ?string $filter = null;

    public function __construct()
    {
        $this->filter = (string) now()->year;
    }

    protected function getFilters(): ?array
    {
        return $this->getYearsFilter('time_entries');
    }

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Valor a receber',
                    'data' => [
                        $this->getPriceAmount(1),
                        $this->getPriceAmount(2),
                        $this->getPriceAmount(3),
                        $this->getPriceAmount(4),
                        $this->getPriceAmount(5),
                        $this->getPriceAmount(6),
                        $this->getPriceAmount(7),
                        $this->getPriceAmount(8),
                        $this->getPriceAmount(9),
                        $this->getPriceAmount(10),
                        $this->getPriceAmount(11),
                        $this->getPriceAmount(12),
                    ],
                    'backgroundColor' => 'rgba(254, 215, 170, 0.2)',
                    'borderColor' => '#f97316',
                ],
            ],
            'labels' => ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getPriceAmount($month)
    {
        $year = $this->filter;

        $carbonInstance = Carbon::create($year, $month, 1);
        $firstDayOfMonth = $carbonInstance->copy()->startOfMonth();
        $lastDayOfMonth = $carbonInstance->copy()->endOfMonth()->addDay();

        $firstDayOfMonthString = $firstDayOfMonth->toDateString(); // "2024-06-01"
        $lastDayOfMonthString = $lastDayOfMonth->toDateString(); // "2024-06-30"

        // Retrieve TimeEntries within the specified date range
        $timeEntries = TimeEntry::whereBetween('start_time', [$firstDayOfMonthString, $lastDayOfMonthString])->get();

        // Calculate the total amount based on worked hours and price_per_hour
        $totalAmount = 0;

        foreach ($timeEntries as $entry) {
            $totalAmount += (float) $entry->calculateTotalWorkedHoursFloat() * $entry->price_per_hour;
        }

        return $totalAmount;
    }
}
