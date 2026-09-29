<?php

namespace App\Filament\Widgets;

use App\Models\Cotisation;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class CotisationsPaymentChart extends ChartWidget
{
    protected static ?int $sort = 4;
    protected int|string|array $columnSpan = 1;
    protected static ?string $heading = 'État des cotisations';

    public static function canView(): bool
    {
        return Auth::guard('admin')->user()?->role === 'SuperAdmin';
    }

    protected function getData(): array
    {
        $statuts = [
            'paye' => 'Payées',
            'en_attente' => 'En attente',
            'retard' => 'En retard',
        ];

        $totaux = Cotisation::query()
            ->selectRaw('statut_paiement, COUNT(*) as total')
            ->groupBy('statut_paiement')
            ->pluck('total', 'statut_paiement');

        return [
            'labels' => array_values($statuts),
            'datasets' => [[
                'label' => 'Cotisations',
                'data' => array_map(fn (string $statut): int => (int) ($totaux[$statut] ?? 0), array_keys($statuts)),
                'backgroundColor' => ['#16a34a', '#f59e0b', '#dc2626'],
            ]],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
