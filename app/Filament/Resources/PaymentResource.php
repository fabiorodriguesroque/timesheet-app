<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Filament\Resources\PaymentResource\RelationManagers;
use App\Models\Payment;
use App\Models\Project;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-euro';

    protected static ?string $modelLabel = 'Pagamento';

    protected static ?string $pluralModelLabel = 'Pagamentos';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Cliente')
                ->description('Selecione o cliente que efetuou o pagamento.')
                ->aside()
                ->schema([
                    Select::make('project_id')
                        ->label('Cliente')
                        ->required()
                        ->options(fn() => Project::all()->pluck('name', 'id')->toArray())
                ]),
                Section::make('Pagamento')
                    ->description('Preencha a data em que foi feito o pagamento e o valor que recebeu.')
                    ->aside()
                    ->schema([
                        DatePicker::make('payment_date')
                            ->label('Data do pagamento')
                            ->native(false),
                        TextInput::make('amount')
                            ->label('Valor')
                            ->numeric()
                            ->suffixIcon('heroicon-o-currency-euro')
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('project.name')
                    ->label('Cliente'),
                TextColumn::make('month')
                    ->label('Data')
                    ->getStateUsing(function (Model $record): string {
                        $date = Carbon::parse($record->payment_date);
                        return $date->translatedFormat('j F');
                    }),
                TextColumn::make('day')
                    ->label('Dia da semana')
                    ->getStateUsing(function (Model $record): string {
                        $date = carbon::parse($record->start_time);
                        return $date->translatedFormat('l');
                    }),
                TextColumn::make('amount')
                        ->label('Valor recebido')
                        ->money('eur')
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
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            PaymentResource\Widgets\PaymentChart::class,
        ];
    }
}
