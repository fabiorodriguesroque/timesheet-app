<?php

namespace App\Filament\Resources;

use App\Traits\hasTimeEntry;
use App\Models\TimeEntry;
use App\Models\Project;
use App\Filament\Resources\TimeEntryResource\Pages;
use App\Filament\Resources\TimeEntryResource\RelationManagers;
use Filament\Resources\Resource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Closure;
use Filament\Forms\Set;
use Filament\Tables\Filters\SelectFilter;

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
                Section::make('Cliente')
                    ->description('Escolha o cliente para quem trabalhou e o preço por hora.')
                    ->aside()
                    ->schema([
                        Select::make('project_id')
                            ->label('Cliente')
                            ->required()
                            ->options(fn() => Project::all()->pluck('name', 'id')->toArray())
                            ->live()
                            ->afterStateUpdated(function (Set $set, Get $get) {
                                $project = Project::find($get('project_id'));

                                return $project
                                    ? $set('price_per_hour', $project->price_per_hour)
                                    : $set('price_per_hour', null);
                            }),
                        TextInput::make('price_per_hour')
                            ->label('Preço por hora')
                            ->required()
                            ->numeric()
                            ->suffix('€')

                    ]),
                Section::make('Registo de horas')
                    ->description('Registe a hora e o dia em que começou e terminou.')
                    ->aside()
                    ->schema([
                        DatePicker::make('date')
                            ->label('Data')
                            ->native(false)
                            ->maxDate(now())
                            ->closeOnDateSelection()
                            ->required(),
                        TimePicker::make('start')
                            ->label('Hora de inicio')
                            ->native(false)
                            ->minutesStep(15)
                            ->seconds(false)
                            ->required(),
                        TimePicker::make('end')
                            ->label('Hora de fim')
                            ->native(false)
                            ->minutesStep(15)
                            ->seconds(false)
                            ->required(),
                        TimePicker::make('lunching_time')
                            ->label('Paragem para almoço')
                            ->minutesStep(15)
                            ->seconds(false)
                            ->native(false)
                    ]),
                Section::make('Observações')
                    ->description('Escreva uma ligeira descrição sobre o que foi feito durante as horas de trabalho.')
                    ->aside()
                    ->schema([
                        Textarea::make('description')
                            ->rows(10)
                            ->label('Observação')
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('start_time')
                    ->label('Data')
                    ->sortable()
                    ->getStateUsing(function (Model $record): string {
                        $date = carbon::parse($record->start_time);
                        return $date->translatedFormat('j F, l');
                    }),
                TextColumn::make('project.name')
                    ->label('Cliente')
                    ->sortable(),
                TextColumn::make('start')
                    ->label('Início')
                    ->getStateUsing(function (Model $record): string {
                        $date = carbon::parse($record->start_time);
                        return $date->translatedFormat('H:i');
                    }),
                TextColumn::make('lunching_time')
                    ->label('Paragem')
                    ->getStateUsing(function (Model $record): string {
                        if ($record->lunching_time) {
                            $date = carbon::parse($record->lunching_time);
                            return $date->translatedFormat('H:i');
                        }

                        return '--:--';
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
            ])->defaultSort('start_time', 'desc')
            ->filters([
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('start_time')
                            ->label('Data de Início')
                            ->native(false),
                        DatePicker::make('end_time')
                            ->label('Data de Fim')
                            ->native(false)
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['start_time'],
                                fn (Builder $query, $date): Builder => $query->whereDate('start_time', '>=', $date),
                            )
                            ->when(
                                $data['end_time'],
                                fn (Builder $query, $date): Builder => $query->whereDate('end_time', '<=', $date),
                            );
                    }),
                SelectFilter::make('project_id')
                    ->label('Cliente')
                    ->options(fn () => Project::all()->pluck('name', 'id')->toArray())
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
