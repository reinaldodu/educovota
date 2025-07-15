<?php

namespace App\Filament\Widgets;

use App\Models\Estudiante;
use App\Models\Voto;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class VotacionesTotales extends StatsOverviewWidget
{
    protected int | string | array $columnSpan = 12;

    protected function getStats(): array
    {
        // Estudiantes que ya votaron (únicos)
        $estudiantesQueVotaron = Voto::distinct('estudiante_id')->count('estudiante_id');

        // Total estudiantes
        $totalEstudiantes = Estudiante::count();

        // Faltantes
        $faltantes = $totalEstudiantes - $estudiantesQueVotaron;

        // Porcentaje de participación
        $porcentaje = $totalEstudiantes > 0
            ? round(($estudiantesQueVotaron / $totalEstudiantes) * 100, 2)
            : 0;

        return [
            Stat::make('Registrados', $estudiantesQueVotaron)
                ->description('Estudiantes que ya votaron')
                ->color('success'),

            Stat::make('Sin votar', $faltantes)
                ->description('Estudiantes que faltan por votar')
                ->color('danger'),

            Stat::make('Participación', "{$porcentaje}%")
                ->description('Porcentaje de participación')
                ->color('info'),
        ];
    }
}
