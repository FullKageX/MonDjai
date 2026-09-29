<?php

namespace App\Filament\Widgets;

use App\Models\Cotisation;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class FinancialStatsOverview extends BaseWidget
{
    protected static ?int $sort = 2;

    public static function canView(): bool
    {
        return Auth::guard('admin')->user()?->role === 'SuperAdmin';
    }

    protected function getStats(): array
    {
        $montantCollecte = Transaction::sum('montant');
        $montantEnAttente = Cotisation::where('statut_paiement', 'en_attente')->sum('montant_attendu');

        return [
            Stat::make('Montant collecté', number_format($montantCollecte, 0, ',', ' ') . ' FCFA')
                ->descriptionIcon('heroicon-m-banknotes'),
            Stat::make('Montant en attente', number_format($montantEnAttente, 0, ',', ' ') . ' FCFA')
                ->descriptionIcon('heroicon-m-exclamation-circle'),
            Stat::make('Utilisateurs inscrits', User::count())
                ->descriptionIcon('heroicon-m-users'),
            Stat::make('Transactions', Transaction::count())
                ->description('Enregistrées')
                ->descriptionIcon('heroicon-m-arrows-right-left'),
        ];
    }
}