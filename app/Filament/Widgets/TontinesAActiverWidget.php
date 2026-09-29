<?php

namespace App\Filament\Widgets;

use App\Models\Tontine;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TontinesAActiverWidget extends BaseWidget
{
    protected static ?int $sort = 5;
    protected int|string|array $columnSpan = 'full';
    protected static ?string $heading = 'Tontines à activer';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Tontine::query()
                    ->with('createur')
                    ->where('statut', 'en_attente')
                    ->latest()
                    ->limit(8)
            )
            ->columns([
                TextColumn::make('nom')->label('Tontine')->searchable(),
                TextColumn::make('createur.telephone')->label('Créateur'),
                TextColumn::make('membres_count')->label('Membres')->counts('membres'),
                TextColumn::make('date_debut')->label('Début')->date('d/m/Y'),
            ])
            ->actions([
                EditAction::make()->label('Voir'),
            ]);
    }
}
