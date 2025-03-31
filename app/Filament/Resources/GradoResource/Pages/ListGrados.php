<?php

namespace App\Filament\Resources\GradoResource\Pages;

use App\Filament\Resources\GradoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGrados extends ListRecords
{
    protected static string $resource = GradoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
