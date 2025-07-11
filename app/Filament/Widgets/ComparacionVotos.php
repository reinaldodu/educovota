<?php

namespace App\Filament\Widgets;

use App\Models\Voto;
use Filament\Widgets\ChartWidget;

class ComparacionVotos extends ChartWidget
{
    protected static ?string $heading = 'Candidatos vs Voto en blanco';

    protected int | string | array $columnSpan = 4;

    protected function getData(): array
    {
        $votosBlanco = Voto::whereNull('candidato_id')->count();
        $votosValidos = Voto::whereNotNull('candidato_id')->count();

        return [
            'datasets' => [
                [
                    'data' => [$votosValidos, $votosBlanco],
                    'backgroundColor' => ['#10B981', '#F59E0B'], // verde y amarillo
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
                        'color' => '#374151', // gris oscuro
                        'font' => [
                            'size' => 12,
                        ],
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
