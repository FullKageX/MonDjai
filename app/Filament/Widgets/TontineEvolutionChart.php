<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Tontine;
use Illuminate\Support\Facades\DB;


class TontineEvolutionChart extends ChartWidget
{
    protected static ?int $sort = 3;
    protected int|string|array $columnSpan = 1;
    protected static ?string $heading = 'Tontines créées (6 derniers mois)';

    protected function getData(): array
    {
        $donnees = Tontine::select(
                DB::raw("strftime('%Y-%m', created_at) as mois"),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('mois')
            ->orderBy('mois')
            ->pluck('total', 'mois');

        return [
            'datasets' => [['label' => 'Tontines créées', 'data' => $donnees->values()]],
            'labels' => $donnees->keys(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
