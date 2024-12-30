<?php

namespace App\Filament\Resources\PaymentResource\Widgets;

use App\Concerns\InteractsWithFilter;
use App\Models\Payment;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class PaymentChart extends ChartWidget
{
    use InteractsWithFilter;

    protected static ?string $heading = 'Pagamentos recebidos';

    // protected int | string | array $columnSpan = 2;

    public ?string $filter = null;

    public function __construct()
    {
        $this->filter = (string) now()->year;
    }

    protected function getFilters(): ?array
    {
        return $this->getYearsFilter('payments');
    }

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Valor recebido',
                    'data' => [
                        $this->getPaymentsData(1),
                        $this->getPaymentsData(2),
                        $this->getPaymentsData(3),
                        $this->getPaymentsData(4),
                        $this->getPaymentsData(5),
                        $this->getPaymentsData(6),
                        $this->getPaymentsData(7),
                        $this->getPaymentsData(8),
                        $this->getPaymentsData(9),
                        $this->getPaymentsData(10),
                        $this->getPaymentsData(11),
                        $this->getPaymentsData(12),
                    ],
                ],
            ],
            'labels' => ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getPaymentsData(int $month)
    {
        $year = $this->filter;

        $carbonInstance = Carbon::create($year, $month, 1);
        $firstDayOfMonth = $carbonInstance->copy()->startOfMonth();
        $lastDayOfMonth = $carbonInstance->copy()->endOfMonth();

        $firstDayOfMonthString = $firstDayOfMonth->toDateString(); // "2024-06-01"
        $lastDayOfMonthString = $lastDayOfMonth->toDateString(); // "2024-06-30"

        $amount = Payment::whereBetween('payment_date', [$firstDayOfMonthString, $lastDayOfMonthString])
            ->sum('amount');

        return $amount / 100;
    }
}
