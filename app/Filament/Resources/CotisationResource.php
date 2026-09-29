<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CotisationResource\Pages;
use App\Models\Cotisation;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CotisationResource extends Resource
{
    protected static ?string $model = Cotisation::class;
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationGroup = 'Suivi financier';
    protected static ?string $navigationLabel = 'Cotisations';
    protected static ?string $modelLabel = 'cotisation';
    protected static ?string $pluralModelLabel = 'cotisations';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('cycle_id')->label('Cycle')->relationship('cycle', 'id')->searchable()->preload()->required(),
            Select::make('membre_id')->label('Membre')->relationship('membre', 'id')->searchable()->preload()->required(),
            TextInput::make('montant_attendu')->label('Montant attendu')->numeric()->required()->minValue(0),
            Select::make('statut_paiement')->label('Statut du paiement')->options(['en_attente' => 'En attente', 'paye' => 'Payé', 'retard' => 'En retard'])->required(),
            DateTimePicker::make('date_marquage')->label('Date de marquage'),
            Select::make('marque_par_id')->label('Marqué par')->relationship('marquePar', 'email')->searchable()->preload(),
            Textarea::make('notes')->label('Notes')->maxLength(255)->columnSpanFull(),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('cycle.tontine.nom')->label('Tontine')->searchable(),
            TextColumn::make('cycle.numero_cycle')->label('Cycle'),
            TextColumn::make('membre.utilisateur.nom')
                ->label('Membre')
                ->formatStateUsing(fn ($record) => $record->membre->utilisateur->prenom . ' ' . $record->membre->utilisateur->nom),
            TextColumn::make('montant_attendu')->label('Montant')->money('XOF'),
            TextColumn::make('statut_paiement')->label('Paiement')->badge(),
            TextColumn::make('date_marquage')->label('Marqué le')->dateTime('d/m/Y H:i'),
        ])->filters([
            SelectFilter::make('statut_paiement')->label('Paiement')->options(['en_attente' => 'En attente', 'paye' => 'Payé', 'retard' => 'En retard']),
        ])->actions([\Filament\Tables\Actions\EditAction::make()->label('Modifier')])->bulkActions([
            \Filament\Tables\Actions\BulkActionGroup::make([\Filament\Tables\Actions\DeleteBulkAction::make()->label('Supprimer')]),
        ]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListCotisations::route('/'), 'create' => Pages\CreateCotisation::route('/create'), 'edit' => Pages\EditCotisation::route('/{record}/edit')];
    }
}
