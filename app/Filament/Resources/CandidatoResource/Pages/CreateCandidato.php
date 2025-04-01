<?php

namespace App\Filament\Resources\CandidatoResource\Pages;

use App\Filament\Resources\CandidatoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCandidato extends CreateRecord
{
    protected static string $resource = CandidatoResource::class;

    //Redirigir a la vista de lista
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
