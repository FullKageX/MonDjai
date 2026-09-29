<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TontineResource\Pages;
use App\Models\Tontine;
use Filament\Tables\Actions\Action;
use App\Services\TontineCycleService;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TontineResource extends Resource
{
    protected static ?string $model = Tontine::class;
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'Gestion';
    protected static ?string $navigationLabel = 'Tontines';
    protected static ?string $modelLabel = 'tontine';
    protected static ?string $pluralModelLabel = 'tontines';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('nom')->label('Nom')->required()->maxLength(255),
            Textarea::make('description')->label('Description')->columnSpanFull(),
            TextInput::make('montant_cotisation')->label('Montant de cotisation')->numeric()->required()->minValue(0),
            Select::make('frequence')->label('Fréquence')->options(['hebdomadaire' => 'Hebdomadaire', 'mensuelle' => 'Mensuelle', 'autre' => 'Autre'])->required(),
            TextInput::make('intervalle_jours')->label('Intervalle en jours')->numeric()->minValue(1)->visible(fn ($get): bool => $get('frequence') === 'autre'),
            TextInput::make('jour_echeance')->label('Jour d’échéance')->numeric()->minValue(1)->maxValue(31),
            Select::make('ordre_versement')->label('Ordre de versement')->options(['aleatoire' => 'Aléatoire', 'fixe' => 'Fixe'])->required(),
            DatePicker::make('date_debut')->label('Date de début')->required(),
            Select::make('statut')->label('Statut')->options(['en_attente' => 'En attente', 'active' => 'Active', 'terminee' => 'Terminée', 'suspendue' => 'Suspendue'])->required(),
            Select::make('createur_id')->label('Créateur')->relationship('createur', 'email')->searchable()->preload()->required(),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('nom')->label('Nom')->searchable()->sortable(),
            TextColumn::make('createur.email')->label('Créateur')->searchable(),
            TextColumn::make('montant_cotisation')->label('Cotisation')->money('XOF'),
            TextColumn::make('frequence')->label('Fréquence')->badge(),
            TextColumn::make('statut')->label('Statut')->badge(),
            TextColumn::make('date_debut')->label('Début')->date('d/m/Y')->sortable(),
        ])->filters([
            SelectFilter::make('statut')->label('Statut')->options(['en_attente' => 'En attente', 'active' => 'Active', 'terminee' => 'Terminée', 'suspendue' => 'Suspendue']),
        ])->actions([
        \Filament\Tables\Actions\Action::make('activer')
        ->label('Activer')
        ->visible(fn ($record) => $record->statut === 'en_attente')
        ->requiresConfirmation()
        ->action(function ($record) {
            \Illuminate\Support\Facades\DB::transaction(function () use ($record) {
                (new \App\Services\TontineCycleService())->genererCycles($record);
                $record->statut = 'active';
                $record->save();
            });
        }),
        \Filament\Tables\Actions\EditAction::make()->label('Modifier'),
        ])->bulkActions([
            \Filament\Tables\Actions\BulkActionGroup::make([
                \Filament\Tables\Actions\DeleteBulkAction::make()->label('Supprimer'),
            ]),
        ]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListTontines::route('/'), 'create' => Pages\CreateTontine::route('/create'), 'edit' => Pages\EditTontine::route('/{record}/edit')];
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\TontineResource\RelationManagers\MembresRelationManager::class,
            \App\Filament\Resources\TontineResource\RelationManagers\CyclesRelationManager::class,
        ];
    }
}
