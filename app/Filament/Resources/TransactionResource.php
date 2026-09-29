<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Models\Transaction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;
    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';
    protected static ?string $navigationGroup = 'Suivi financier';
    protected static ?string $navigationLabel = 'Transactions';
    protected static ?string $modelLabel = 'transaction';
    protected static ?string $pluralModelLabel = 'transactions';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('cotisation_id')->label('Cotisation')->relationship('cotisation', 'id')->searchable()->preload()->required(),
            Select::make('type')->label('Type')->options(['paiement_manuel' => 'Paiement manuel', 'mobile_money' => 'Mobile Money', 'ajustement' => 'Ajustement'])->required(),
            TextInput::make('montant')->label('Montant')->numeric()->required()->minValue(0),
            TextInput::make('reference_externe')->label('Référence externe')->maxLength(100),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('cotisation.cycle.tontine.nom')->label('Tontine')->searchable(),
            TextColumn::make('cotisation.membre.utilisateur.telephone')->label('Membre'),
            TextColumn::make('type')->label('Type')->badge(),
            TextColumn::make('montant')->label('Montant')->money('XOF'),
            TextColumn::make('reference_externe')->label('Référence')->searchable(),
            TextColumn::make('created_at')->label('Date')->dateTime('d/m/Y H:i')->sortable(),
        ])->filters([
            SelectFilter::make('type')->label('Type')->options(['paiement_manuel' => 'Paiement manuel', 'mobile_money' => 'Mobile Money', 'ajustement' => 'Ajustement']),
        ])->actions([\Filament\Tables\Actions\EditAction::make()->label('Modifier')])->bulkActions([
            \Filament\Tables\Actions\BulkActionGroup::make([\Filament\Tables\Actions\DeleteBulkAction::make()->label('Supprimer')]),
        ]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListTransactions::route('/'), 'create' => Pages\CreateTransaction::route('/create'), 'edit' => Pages\EditTransaction::route('/{record}/edit')];
    }
}
