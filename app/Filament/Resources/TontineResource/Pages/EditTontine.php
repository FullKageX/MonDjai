<?php

namespace App\Filament\Resources\TontineResource\Pages;

use App\Filament\Resources\TontineResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTontine extends EditRecord
{
    protected static string $resource = TontineResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()->label('Supprimer')];
    }
}
