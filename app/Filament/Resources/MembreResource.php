<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MembreResource\Pages;
use App\Models\Membre;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MembreResource extends Resource
{
    protected static ?string $model = Membre::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Gestion';
    protected static ?string $navigationLabel = 'Membres';
    protected static ?string $modelLabel = 'membre';
    protected static ?string $pluralModelLabel = 'membres';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('tontine_id')->label('Tontine')->relationship('tontine', 'nom')->searchable()->preload()->required(),
            Select::make('user_id')->label('Utilisateur')->relationship('utilisateur', 'telephone')->searchable()->preload()->required(),
            TextInput::make('ordre_tour')->label('Ordre de passage')->numeric()->required()->minValue(1),
            Select::make('statut')->label('Statut')->options(['actif' => 'Actif', 'en_attente' => 'En attente', 'retire' => 'Retiré'])->required(),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('utilisateur.prenom')->label('Prénom')->searchable(),
            TextColumn::make('utilisateur.nom')->label('Nom')->searchable(),
            TextColumn::make('utilisateur.telephone')->label('Téléphone')->searchable(),
            TextColumn::make('tontine.nom')->label('Tontine')->searchable(),
            TextColumn::make('ordre_tour')->label('Ordre')->sortable(),
            TextColumn::make('statut')->label('Statut')->badge(),
        ])->filters([
            SelectFilter::make('statut')->label('Statut')->options(['actif' => 'Actif', 'en_attente' => 'En attente', 'retire' => 'Retiré']),
        ])->actions([\Filament\Tables\Actions\EditAction::make()->label('Modifier')])->bulkActions([
            \Filament\Tables\Actions\BulkActionGroup::make([\Filament\Tables\Actions\DeleteBulkAction::make()->label('Supprimer')]),
        ]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListMembres::route('/'), 'create' => Pages\CreateMembre::route('/create'), 'edit' => Pages\EditMembre::route('/{record}/edit')];
    }
}
