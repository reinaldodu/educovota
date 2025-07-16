<?php

namespace App\Filament\Pages;

use App\Models\Categoria;
use App\Models\Voto;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersAction;
use Filament\Pages\Dashboard\Actions\FilterAction;
use Filament\Forms\Components\Select;
use App\Filament\Widgets\VotacionesTotales;
use App\Filament\Widgets\VotosPorCandidato;
use App\Filament\Widgets\ComparacionVotos;
use App\Filament\Widgets\ParticipacionPorGrado;
use App\Filament\Widgets\RankingCandidatos;
use App\Filament\Widgets\SinVotosRegistrados;

class Dashboard extends BaseDashboard
{
    use HasFiltersAction;

    protected static ?string $navigationLabel = 'Panel de votación';
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    /**
     * Título dinámico del dashboard (según la categoría seleccionada)
     */
    public function getTitle(): string
    {
        $categoriaId = $this->filters['categoriaId'] ?? null;

        if ($categoriaId) {
            $categoria = Categoria::find($categoriaId);
            if ($categoria) {
                return 'Panel de Votación - ' . $categoria->nombre;
            }
        }

        return 'Panel de Votación';
    }

    /**
     * Widget principal (no se ve afectado por el filtro)
     */
    protected function getHeaderWidgets(): array
    {
        return [
            VotacionesTotales::class,
        ];
    }

    /**
     * Acción de filtro (usa FilterAction con Select),
     * solo visible si hay más de una categoría.
     */
    protected function getHeaderActions(): array
    {
        $totalCategorias = Categoria::count();

        if ($totalCategorias <= 1) {
            return [];
        }

        return [
            FilterAction::make()
                ->label('Filtrar por categoría')
                ->form([
                    Select::make('categoriaId')
                        ->label('Categoría')
                        ->options(Categoria::pluck('nombre', 'id')->toArray())
                        ->searchable()
                        ->placeholder('Todas las categorías'),
                ]),
        ];
    }

    /**
     * Lista de widgets afectados por el filtro
     */
    public function getWidgets(): array
    {
        if (Voto::count() === 0) {
            return [
                SinVotosRegistrados::class,
            ];
        }

        $categoriaId = $this->filters['categoriaId'] ?? null;

        return [
            VotosPorCandidato::make(['categoriaId' => $categoriaId]),
            ComparacionVotos::make(['categoriaId' => $categoriaId]),
            RankingCandidatos::make(['categoriaId' => $categoriaId]),
            ParticipacionPorGrado::make(['categoriaId' => $categoriaId]),
        ];
    }

    public function getColumns(): int | string | array
    {
        return 12;
    }
}
