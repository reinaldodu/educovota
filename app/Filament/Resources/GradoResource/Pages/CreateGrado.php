<?php

namespace App\Filament\Resources\GradoResource\Pages;

use App\Filament\Resources\GradoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateGrado extends CreateRecord
{
    protected static string $resource = GradoResource::class;

    //Despues de crear el grado, redirigir a la página de grados
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
