<?php

namespace App\Filament\Resources\CandidatoResource\Pages;

use App\Filament\Resources\CandidatoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCandidato extends EditRecord
{
    protected static string $resource = CandidatoResource::class;

    //Redirigir a la vista de lista
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
