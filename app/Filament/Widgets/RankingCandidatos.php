<?php

namespace App\Filament\Widgets;

use App\Models\Candidato;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Builder;

class RankingCandidatos extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = '🏆 Ranking de candidatos por votos';
    protected int | string | array $columnSpan = 6;

    protected function getTableQuery(): Builder
    {
        $categoriaId = $this->filters['categoriaId'] ?? null;

        return Candidato::with(['categoria'])
            ->withCount([
                'votos' => function ($query) use ($categoriaId) {
                    if ($categoriaId) {
                        $query->where('categoria_id', $categoriaId);
                    }
                },
            ])
            ->when($categoriaId, function ($query) use ($categoriaId) {
                // Si hay filtro, solo mostrar esa categoría y ordenar por votos
                return $query->where('categoria_id', $categoriaId)
                             ->orderByDesc('votos_count');
            }, function ($query) {
                // Si no hay filtro, ordenar por el orden natural de categoría_id y luego por votos
                return $query->orderBy('categoria_id')
                             ->orderByDesc('votos_count');
            });
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

            Tables\Columns\TextColumn::make('nombre_completo')
                ->label('Candidato')
                ->getStateUsing(fn ($record) => $record->nombres . ' ' . $record->apellidos),

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

    protected function getTablePollingInterval(): ?string
    {
        return '10s';
    }
}
