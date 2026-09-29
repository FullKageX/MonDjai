<?php

namespace App\Filament\Resources\TontineResource\RelationManagers;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MembresRelationManager extends RelationManager
{
    protected static string $relationship = 'membres';
    protected static ?string $title = 'Membres et paiements';

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query
                ->with('utilisateur')
                ->withCount([
                    'cotisations as cotisations_payees' => fn ($query) => $query->where('statut_paiement', 'paye'),
                    'cotisations as cotisations_en_attente' => fn ($query) => $query->where('statut_paiement', 'en_attente'),
                    'cotisations as cotisations_en_retard' => fn ($query) => $query->where('statut_paiement', 'retard'),
                ]))
            ->columns([
                TextColumn::make('utilisateur.prenom')->label('Prénom')->searchable(),
                TextColumn::make('utilisateur.nom')->label('Nom')->searchable(),
                TextColumn::make('utilisateur.telephone')->label('Téléphone'),
                TextColumn::make('ordre_tour')->label('Ordre')->sortable(),
                TextColumn::make('statut')->label('Statut du membre')->badge(),
                TextColumn::make('statut_paiement')
                    ->label('Paiement')
                    ->badge()
                    ->state(function ($record): string {
                        if ($record->cotisations_en_retard > 0) {
                            return 'En retard';
                        }

                        if ($record->cotisations_en_attente > 0) {
                            return 'En attente';
                        }

                        return $record->cotisations_payees > 0 ? 'À jour' : 'Aucun paiement';
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'À jour' => 'success',
                        'En retard' => 'danger',
                        'En attente' => 'warning',
                        default => 'gray',
                    }),
            ])
            ->headerActions([
                \Filament\Tables\Actions\CreateAction::make()->label('Ajouter un membre'),
            ])
            ->actions([
                \Filament\Tables\Actions\EditAction::make()->label('Modifier'),
            ]);
    }

    public function form(\Filament\Forms\Form $form): \Filament\Forms\Form
    {
        return $form->schema([
            Select::make('user_id')->label('Utilisateur')->relationship('utilisateur', 'telephone')->searchable()->preload()->required(),
            TextInput::make('ordre_tour')->label('Ordre de passage')->numeric()->required()->minValue(1),
            Select::make('statut')->label('Statut')->options(['actif' => 'Actif', 'en_attente' => 'En attente', 'retire' => 'Retiré'])->required(),
        ]);
    }
}
