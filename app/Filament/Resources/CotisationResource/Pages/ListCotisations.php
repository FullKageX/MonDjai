<?php

namespace App\Filament\Resources\CotisationResource\Pages;

use App\Filament\Resources\CotisationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCotisations extends ListRecords
{
    protected static string $resource = CotisationResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()->label('Nouvelle cotisation')];
    }
}
