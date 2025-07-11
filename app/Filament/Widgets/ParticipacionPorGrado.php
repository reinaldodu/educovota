<?php

namespace App\Filament\Widgets;

use App\Models\Estudiante;
use App\Models\Voto;
use Filament\Widgets\ChartWidget;

class ParticipacionPorGrado extends ChartWidget
{
    protected static ?string $heading = 'Participación por grado';

    protected int | string | array $columnSpan = 6;

    protected function getData(): array
    {
        $estudiantesQueVotaron = Voto::pluck('estudiante_id')->unique();

        $estudiantes = Estudiante::with('grado')
            ->whereIn('id', $estudiantesQueVotaron)
            ->get();

        $agrupado = $estudiantes->groupBy(fn ($e) => $e->grado->nombre ?? 'Sin grado');

        $labels = $agrupado->keys()->toArray();
        $data = $agrupado->map->count()->values()->toArray();

        $max = max($data);

        // 🎨 Generar color dinámico (más oscuro si el valor es más alto)
        $backgroundColors = array_map(function ($valor) use ($max) {
            $opacidad = round(($valor / $max) * 0.8, 2); // Máximo 0.8
            return "rgba(59, 130, 246, {$opacidad})"; // azul con opacidad dinámica
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
