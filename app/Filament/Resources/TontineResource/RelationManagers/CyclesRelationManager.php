<?php

namespace App\Filament\Resources\TontineResource\RelationManagers;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CyclesRelationManager extends RelationManager
{
    protected static string $relationship = 'cycles';
    protected static ?string $title = 'Calendrier des cycles';

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with('beneficiaire.utilisateur')->orderBy('date_debut'))
            ->columns([
                TextColumn::make('numero_cycle')->label('Tour')->sortable(),
                TextColumn::make('beneficiaire.utilisateur.prenom')->label('Prénom'),
                TextColumn::make('beneficiaire.utilisateur.nom')->label('Nom'),
                TextColumn::make('date_debut')->label('Début')->date('d/m/Y')->sortable(),
                TextColumn::make('date_echeance')->label('Échéance')->date('d/m/Y')->sortable(),
                TextColumn::make('statut')->label('Statut')->badge(),
            ])
            ->headerActions([
                \Filament\Tables\Actions\CreateAction::make()->label('Ajouter un cycle'),
            ])
            ->actions([
                \Filament\Tables\Actions\EditAction::make()->label('Modifier'),
            ]);
    }

    public function form(\Filament\Forms\Form $form): \Filament\Forms\Form
    {
        return $form->schema([
            TextInput::make('numero_cycle')->label('Numéro du cycle')->numeric()->required()->minValue(1),
            Select::make('beneficiaire_id')->label('Bénéficiaire')->relationship('beneficiaire', 'id')->searchable()->preload()->required(),
            DatePicker::make('date_debut')->label('Date de début')->required(),
            DatePicker::make('date_echeance')->label('Date d’échéance')->required(),
            Select::make('statut')->label('Statut')->options(['en_cours' => 'En cours', 'cloture' => 'Clôturé', 'annule' => 'Annulé'])->required(),
        ]);
    }
}
