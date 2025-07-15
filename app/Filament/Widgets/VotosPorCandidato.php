<?php

namespace App\Filament\Widgets;

use App\Models\Candidato;
use App\Models\Voto;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class VotosPorCandidato extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Votos por candidato';
    protected int | string | array $columnSpan = 8;

    protected function getData(): array
    {
        $labels = [];
        $votos = [];

        $categoriaId = $this->filters['categoriaId'] ?? null;

        if ($categoriaId) {
            // Solo los candidatos de esa categoría
            $candidatos = Candidato::where('categoria_id', $categoriaId)->get();

            foreach ($candidatos as $candidato) {
                $labels[] = $candidato->nombres . ' ' . $candidato->apellidos;
                $votos[] = Voto::where('candidato_id', $candidato->id)
                               ->where('categoria_id', $categoriaId)
                               ->count();
            }

            // Votos en blanco en esa categoría
            $labels[] = 'Voto en blanco';
            $votos[] = Voto::whereNull('candidato_id')
                           ->where('categoria_id', $categoriaId)
                           ->count();
        } else {
            // Sin filtro → todos los candidatos
            $candidatos = Candidato::all();

            foreach ($candidatos as $candidato) {
                $labels[] = $candidato->nombres . ' ' . $candidato->apellidos;
                $votos[] = Voto::where('candidato_id', $candidato->id)->count();
            }

            $labels[] = 'Voto en blanco';
            $votos[] = Voto::whereNull('candidato_id')->count();
        }

        // Prevenir error si no hay votos
        $max = count($votos) > 0 ? max($votos) : 1;

        $colores = [];
        foreach ($votos as $index => $valor) {
            if ($labels[$index] === 'Voto en blanco') {
                $colores[] = 'rgba(107, 114, 128, 0.4)';
            } else {
                $opacidad = round(($valor / $max) * 0.8, 2);
                $colores[] = "rgba(34, 197, 94, {$opacidad})";
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Cantidad de votos',
                    'data' => $votos,
                    'backgroundColor' => $colores,
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

    protected function getOptions(): ?array
    {
        return [
            'scales' => [
                'y' => [
                    'ticks' => [
                        'stepSize' => 1,
                        'precision' => 0,
                        'beginAtZero' => true,
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'animation' => [
                'duration' => 1000,
                'easing' => 'easeInOutQuart',
            ],
        ];
    }
}
