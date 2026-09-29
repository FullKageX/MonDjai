<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class DernieresTransactionsWidget extends BaseWidget
{
    protected static ?int $sort = 7;
    protected int|string|array $columnSpan = 'full';
    protected static ?string $heading = 'Dernières transactions';

    public static function canView(): bool
    {
        return Auth::guard('admin')->user()?->role === 'SuperAdmin';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Transaction::query()
                    ->with(['cotisation.cycle.tontine', 'cotisation.membre.utilisateur'])
                    ->latest('created_at')
                    ->limit(8)
            )
            ->columns([
                TextColumn::make('cotisation.cycle.tontine.nom')->label('Tontine')->searchable(),
                TextColumn::make('cotisation.membre.utilisateur.telephone')->label('Membre'),
                TextColumn::make('montant')->label('Montant')->money('XOF'),
                TextColumn::make('type')->label('Type')->badge(),
                TextColumn::make('created_at')->label('Date')->dateTime('d/m/Y H:i'),
            ]);
    }
}
