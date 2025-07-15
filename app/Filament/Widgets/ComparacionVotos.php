<?php

namespace App\Filament\Widgets;

use App\Models\Voto;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class ComparacionVotos extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Candidatos vs Voto en blanco';
    protected int | string | array $columnSpan = 4;

    protected function getData(): array
    {
        $categoriaId = $this->filters['categoriaId'] ?? null;

        if ($categoriaId) {
            // Votos por categoría específica
            $votosValidos = Voto::whereNotNull('candidato_id')
                ->where('categoria_id', $categoriaId)
                ->count();

            $votosBlanco = Voto::whereNull('candidato_id')
                ->where('categoria_id', $categoriaId)
                ->count();
        } else {
            // Votos generales (sin filtro)
            $votosValidos = Voto::whereNotNull('candidato_id')->count();
            $votosBlanco = Voto::whereNull('candidato_id')->count();
        }

        return [
            'datasets' => [
                [
                    'data' => [$votosValidos, $votosBlanco],
                    'backgroundColor' => ['#10B981', '#F59E0B'], // Verde y Amarillo
                ],
            ],
            'labels' => ['Votos candidatos', 'Voto en blanco'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): ?array
    {
        return [
            'cutout' => '50%',
            'scales' => [
                'x' => ['display' => false],
                'y' => ['display' => false],
            ],
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                    'labels' => [
                        'color' => '#374151',
                        'font' => ['size' => 12],
                    ],
                ],
            ],
            'animation' => [
                'animateRotate' => true,
                'animateScale' => true,
                'duration' => 1000,
                'easing' => 'easeOutQuart',
            ],
        ];
    }
}
