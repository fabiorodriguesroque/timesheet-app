<?php

namespace App\Filament\Resources\TimeEntryResource\Widgets;

use App\Models\TimeEntry;
use Filament\Widgets\ChartWidget;

class TimeEntriesPriceAmount extends ChartWidget
{
    protected static ?string $heading = 'Pagamentos a receber';

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
        $firstDayOfMonth = now()->setMonth($month)->startOfMonth();
        $lastDayOfMonth = now()->setMonth($month)->endOfMonth();
        
        // Retrieve TimeEntries within the specified date range
        $timeEntries = TimeEntry::whereBetween('start_time', [$firstDayOfMonth, $lastDayOfMonth])->get();
    
        // Calculate the total amount based on worked hours and price_per_hour
        $totalAmount = 0;
    
        foreach ($timeEntries as $entry) {
            $totalAmount += (float) $entry->calculateTotalWorkedHoursFloat() * $entry->price_per_hour;
        }
    
        return $totalAmount;
    }
}
