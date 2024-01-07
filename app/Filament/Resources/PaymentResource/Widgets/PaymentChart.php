<?php

namespace App\Filament\Resources\PaymentResource\Widgets;

use App\Models\Payment;
use Filament\Widgets\ChartWidget;

class PaymentChart extends ChartWidget
{
    protected static ?string $heading = 'Pagamentos recebidos';

    // protected int | string | array $columnSpan = 2;

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
        $firstDayOfMonth = now()->setMonth($month)->startOfMonth();
        $lastDayOfMonth = now()->setMonth($month)->endOfMonth();

        $amount = Payment::whereBetween('payment_date', [$firstDayOfMonth, $lastDayOfMonth])
            ->sum('amount');

        return $amount / 100;
    }
}
