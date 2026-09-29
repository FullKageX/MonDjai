<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Auth;

class TopMembresWidget extends BaseWidget
{
    protected static ?int $sort = 8;
    protected int|string|array $columnSpan = 'full';
    protected static ?string $heading = 'Top membres';

    public static function canView(): bool
    {
        return Auth::guard('admin')->user()?->role === 'SuperAdmin';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                User::withCount('membreDe as nombre_tontines')
                    ->withCount(['cotisations as cotisations_payees' => fn ($q) => $q->where('statut_paiement', 'paye')])
                    ->withCount(['cotisations as cotisations_retard' => fn ($q) => $q->where('statut_paiement', 'retard')])
                    ->orderByDesc('cotisations_payees')
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('nom')->label('Nom'),
                TextColumn::make('prenom')->label('Prénom'),
                TextColumn::make('nombre_tontines')->label('Tontines rejointes'),
                TextColumn::make('cotisations_payees')->label('Payées'),
                TextColumn::make('cotisations_retard')->label('Retards'),
            ]);
    }
}