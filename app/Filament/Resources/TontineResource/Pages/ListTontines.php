<?php

namespace App\Filament\Resources\TontineResource\Pages;

use App\Filament\Resources\TontineResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTontines extends ListRecords
{
    protected static string $resource = TontineResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()->label('Nouvelle tontine')];
    }
}
