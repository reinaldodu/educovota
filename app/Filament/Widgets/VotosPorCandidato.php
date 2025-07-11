<?php

namespace App\Filament\Widgets;

use App\Models\Candidato;
use App\Models\Voto;
use Filament\Widgets\ChartWidget;

class VotosPorCandidato extends ChartWidget
{
    protected static ?string $heading = 'Votos por candidato';
    protected int | string | array $columnSpan = 8;

    protected function getData(): array
    {
        $candidatos = Candidato::all();

        $labels = [];
        $votos = [];

        // Obtener los votos por candidato
        foreach ($candidatos as $candidato) {
            $labels[] = $candidato->nombres . ' ' . $candidato->apellidos;
            $votos[] = Voto::where('candidato_id', $candidato->id)->count();
        }

        // Agregar voto en blanco
        $labels[] = 'Voto en blanco';
        $votosEnBlanco = Voto::whereNull('candidato_id')->count();
        $votos[] = $votosEnBlanco;

        // Calcular color dinámico (verde más intenso con más votos)
        $max = max($votos);
        $colores = [];

        foreach ($votos as $index => $valor) {
            if ($labels[$index] === 'Voto en blanco') {
                // Gris para voto en blanco
                $colores[] = 'rgba(107, 114, 128, 0.4)';
            } else {
                $opacidad = round(($valor / $max) * 0.8, 2); // máximo 0.8
                $colores[] = "rgba(34, 197, 94, {$opacidad})"; // verde dinámico
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Cantidad de votos',
                    'data' => $votos,
                    'backgroundColor' => $colores,
                    'borderColor' => 'rgb(34, 197, 94)', // borde verde
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
                'duration' => 1000, // 1 segundo
                'easing' => 'easeInOutQuart', // Animación elegante y fluida
            ],
        ];
    }
}
