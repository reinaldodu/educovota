<?php

namespace App\Filament\Resources\EstudianteResource\Pages;

use App\Filament\Resources\EstudianteResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEstudiante extends CreateRecord
{
    protected static string $resource = EstudianteResource::class;

    //Redirigir a la vista de lista
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}