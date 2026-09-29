<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CycleResource\Pages;
use App\Models\Cycle;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CycleResource extends Resource
{
    protected static ?string $model = Cycle::class;
    protected static ?string $navigationIcon = 'heroicon-o-arrow-path-rounded-square';
    protected static ?string $navigationGroup = 'Suivi';
    protected static ?string $navigationLabel = 'Cycles';
    protected static ?string $modelLabel = 'cycle';
    protected static ?string $pluralModelLabel = 'cycles';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('tontine_id')->label('Tontine')->relationship('tontine', 'nom')->searchable()->preload()->required(),
            Select::make('beneficiaire_id')->label('Bénéficiaire')->relationship('beneficiaire', 'id')->searchable()->preload()->required(),
            TextInput::make('numero_cycle')->label('Numéro du cycle')->numeric()->required()->minValue(1),
            DatePicker::make('date_debut')->label('Date de début')->required(),
            DatePicker::make('date_echeance')->label('Date d’échéance')->required(),
            Select::make('statut')->label('Statut')->options(['en_cours' => 'En cours', 'cloture' => 'Clôturé', 'annule' => 'Annulé'])->required(),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('tontine.nom')->label('Tontine')->searchable(),
            TextColumn::make('beneficiaire.utilisateur.nom')
                ->label('Bénéficiaire')
                ->formatStateUsing(fn ($record) => $record->beneficiaire->utilisateur->prenom . ' ' . $record->beneficiaire->utilisateur->nom),            TextColumn::make('beneficiaire.utilisateur.telephone')->label('Téléphone'),
            TextColumn::make('date_debut')->label('Début')->date('d/m/Y'),
            TextColumn::make('date_echeance')->label('Échéance')->date('d/m/Y')->sortable(),
            TextColumn::make('statut')->label('Statut')->badge(),
        ])->filters([
            SelectFilter::make('statut')->label('Statut')->options(['en_cours' => 'En cours', 'cloture' => 'Clôturé', 'annule' => 'Annulé']),
        ])->actions([\Filament\Tables\Actions\EditAction::make()->label('Modifier')])->bulkActions([
            \Filament\Tables\Actions\BulkActionGroup::make([\Filament\Tables\Actions\DeleteBulkAction::make()->label('Supprimer')]),
        ]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListCycles::route('/'), 'create' => Pages\CreateCycle::route('/create'), 'edit' => Pages\EditCycle::route('/{record}/edit')];
    }
}
