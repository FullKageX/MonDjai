<?php

namespace App\Filament\Widgets;

use App\Models\Cotisation;
use App\Models\Membre;
use App\Models\Tontine;
use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TontineStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Tontines', Tontine::count())
                ->description(Tontine::where('statut', 'active')->count() . ' actives')
                ->descriptionIcon('heroicon-m-check-circle'),
            Stat::make('Membres', Membre::count())
                ->description(Membre::where('statut', 'actif')->count() . ' actifs')
                ->descriptionIcon('heroicon-m-user-group'),
            Stat::make('Cotisations en attente', Cotisation::where('statut_paiement', 'en_attente')->count())
                ->description('À suivre')
                ->descriptionIcon('heroicon-m-clock'),
        ];
    }
}
