<?php

namespace App\Filament\Resources\CotisationResource\Pages;

use App\Filament\Resources\CotisationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCotisation extends EditRecord
{
    protected static string $resource = CotisationResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()->label('Supprimer')];
    }
}
