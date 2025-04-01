<?php

namespace App\Filament\Resources\GradoResource\Pages;

use App\Filament\Resources\GradoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGrado extends EditRecord
{
    protected static string $resource = GradoResource::class;

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
