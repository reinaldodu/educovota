<?php

namespace App\Filament\Widgets;

use App\Models\Estudiante;
use App\Models\Voto;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class ParticipacionPorGrado extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Participación por grado';
    protected int | string | array $columnSpan = 6;

    protected function getData(): array
    {
        $categoriaId = $this->filters['categoriaId'] ?? null;

        $query = Voto::query();

        if ($categoriaId) {
            $query->where('categoria_id', $categoriaId);
        }

        $estudiantesQueVotaron = $query->pluck('estudiante_id')->unique();

        if ($estudiantesQueVotaron->isEmpty()) {
            return [
                'datasets' => [
                    [
                        'label' => 'Votantes por grado',
                        'data' => [],
                        'backgroundColor' => [],
                        'borderColor' => 'rgb(34, 197, 94)',
                        'borderWidth' => 1,
                    ],
                ],
                'labels' => [],
            ];
        }

        $estudiantes = Estudiante::with('grado')
            ->whereIn('id', $estudiantesQueVotaron)
            ->get();

        $agrupado = $estudiantes->groupBy(fn ($e) => $e->grado->nombre ?? 'Sin grado');

        $labels = $agrupado->keys()->toArray();
        $data = $agrupado->map->count()->values()->toArray();

        $max = count($data) > 0 ? max($data) : 1;

        $backgroundColors = array_map(function ($valor) use ($max) {
            $opacidad = round(($valor / $max) * 0.8, 2);
            return "rgba(59, 130, 246, {$opacidad})";
        }, $data);

        return [
            'datasets' => [
                [
                    'label' => 'Votantes por grado',
                    'data' => $data,
                    'backgroundColor' => $backgroundColors,
                    'borderColor' => 'rgb(34, 197, 94)',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'y',
            'scales' => [
                'x' => [
                    'ticks' => [
                        'stepSize' => 1,
                        'precision' => 0,
                    ],
                    'beginAtZero' => true,
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
        ];
    }
}
