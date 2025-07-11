<?php

namespace App\Filament\Widgets;

use App\Models\Candidato;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class RankingCandidatos extends BaseWidget
{
    protected static ?string $heading = '🏆 Ranking de candidatos por votos';

    protected int | string | array $columnSpan = 6;

    protected function getTableQuery(): Builder
    {
        return Candidato::withCount('votos')->orderByDesc('votos_count');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\ImageColumn::make('foto')
                ->label('')
                ->circular()
                ->height(40)
                ->width(40)
                ->disk('public'),

            // ✅ Columna combinada: Nombre completo
            Tables\Columns\TextColumn::make('nombre_completo')
                ->label('Candidato')
                ->getStateUsing(function ($record) {
                    return $record->nombres . ' ' . $record->apellidos;
                }),

            Tables\Columns\TextColumn::make('categoria.nombre')
                ->label('Categoría')
                ->badge()
                ->color('gray'),

            Tables\Columns\TextColumn::make('votos_count')
                ->label('Votos')
                ->numeric()
                ->color('success'),
        ];
    }
}
