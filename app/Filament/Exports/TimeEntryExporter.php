<?php

namespace App\Filament\Exports;

use App\Models\TimeEntry;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class TimeEntryExporter extends Exporter
{
    protected static ?string $model = TimeEntry::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('project.name')
                ->label('Cliente'),
            ExportColumn::make('price_per_hour')
                ->label('Preço por Hora'),
            ExportColumn::make('start_time')
                ->label('Início'),
            ExportColumn::make('end_time')
                ->label('Fim'),
            ExportColumn::make('lunching_time')
                ->label('Descanso / Almoço'),
            ExportColumn::make('description')
                ->label('Descrição')
            // ExportColumn::make('created_at'),
            // ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'A exportação dos seus registos de tempo foi concluída e ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' foram exportadas com sucesso.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('linha')->plural($failedRowsCount) . ' falharam na exportação.';
        }

        return $body;
    }
}
