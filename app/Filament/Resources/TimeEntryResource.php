<?php

namespace App\Filament\Resources;

use App\Traits\hasTimeEntry;
use App\Filament\Resources\TimeEntryResource\Pages;
use App\Filament\Resources\TimeEntryResource\RelationManagers;
use App\Models\TimeEntry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TimePicker;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TimeEntryResource extends Resource
{
    use hasTimeEntry;

    protected static ?string $model = TimeEntry::class;

    protected static ?string $modelLabel = 'Hora';

    protected static ?string $pluralModelLabel = 'Horas';

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Registo de horas')
                    ->description('Registe a hora e o dia em que começou e terminou.')
                    ->aside()
                    ->schema([
                        DateTimePicker::make('start_time')
                            ->label('Hora de início')
                            ->seconds(false)
                            ->native(false)
                            ->minutesStep(15),
                        DateTimePicker::make('end_time')
                            ->label('Hora de fim')
                            ->seconds(false)
                            ->native(false)
                            ->minutesStep(15)
                    ]),
                Section::make('Paragem para almoço')
                    ->description('Registe o número de horas que parou para almoçar.')
                    ->aside()
                    ->schema([
                        TimePicker::make('lunching_time')
                            ->label('Paragem para almoço')
                            ->minutesStep(15)
                            ->seconds(false)
                            ->native(false)
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('month')
                    ->label('Mês')
                    ->getStateUsing(function (Model $record): string {
                        $date = carbon::parse($record->start_time);
                        return $date->translatedFormat('j F');
                    }),
                TextColumn::make('day')
                    ->label('Dia da semana')
                        ->getStateUsing(function (Model $record): string {
                            $date = carbon::parse($record->start_time);
                            return $date->translatedFormat('l');
                        }),
                TextColumn::make('start_time')
                    ->label('Início')
                    ->getStateUsing(function (Model $record): string {
                        $date = carbon::parse($record->start_time);
                        return $date->translatedFormat('H:i');
                    }),
                TextColumn::make('lunching_time')
                    ->label('Paragem')
                    ->getStateUsing(function (Model $record): string {
                        $date = carbon::parse($record->lunching_time);
                        return $date->translatedFormat('H:i');
                    }),
                TextColumn::make('end_time')
                    ->label('Fim')
                    ->getStateUsing(function (Model $record): string {
                        $date = carbon::parse($record->end_time);
                        return $date->translatedFormat('H:i');
                    }),
                TextColumn::make('worked_hours')
                    ->label('Total')
                    ->getStateUsing(function (Model $record): string {
                        return $record->calculateTotalWorkedHours();
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTimeEntries::route('/'),
            'create' => Pages\CreateTimeEntry::route('/create'),
            'edit' => Pages\EditTimeEntry::route('/{record}/edit'),
        ];
    }
}
