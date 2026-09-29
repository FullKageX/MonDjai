<?php

namespace App\Filament\Widgets;

use App\Models\Cycle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class ProchainsCyclesWidget extends BaseWidget
{
    protected static ?int $sort = 6;
    protected int|string|array $columnSpan = 'full';
    protected static ?string $heading = 'Prochains cycles';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Cycle::query()
                    ->with(['tontine', 'beneficiaire.utilisateur'])
                    ->whereDate('date_echeance', '>=', today())
                    ->where('statut', 'en_cours')
                    ->orderBy('date_echeance')
                    ->limit(8)
            )
            ->columns([
                TextColumn::make('tontine.nom')->label('Tontine')->searchable(),
                TextColumn::make('numero_cycle')->label('Cycle'),
                TextColumn::make('beneficiaire.utilisateur.telephone')->label('Bénéficiaire'),
                TextColumn::make('date_echeance')->label('Échéance')->date('d/m/Y')->sortable(),
            ]);
    }
}
