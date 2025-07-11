<?php

namespace App\Filament\Resources\CategoriaResource\Pages;

use App\Filament\Resources\CategoriaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCategoria extends CreateRecord
{
    protected static string $resource = CategoriaResource::class;

    //Redirigir a la vista de lista
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}