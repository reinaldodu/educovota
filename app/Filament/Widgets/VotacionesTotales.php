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
        $estudiantesQueVotaron = Voto::distinct('estudiante_id')->count('estudiante_id');
        $totalEstudiantes = Estudiante::count();
        $faltantes = $totalEstudiantes - $estudiantesQueVotaron;
        $porcentaje = $totalEstudiantes > 0
            ? round(($estudiantesQueVotaron / $totalEstudiantes) * 100, 2)
            : 0;

        return [
            Stat::make('Registrados', $estudiantesQueVotaron)
                ->description('Estudiantes que ya votaron')
                ->color('success')
                ->url($estudiantesQueVotaron > 0 ? route('filament.admin.resources.estudiantes.index', ['tableFilters[voto][value]' => 1]) : null),

            Stat::make('Sin votar', $faltantes)
                ->description('Estudiantes que faltan por votar')
                ->color('danger')
                ->url($faltantes > 0 ? route('filament.admin.resources.estudiantes.index', ['tableFilters[voto][value]' => 0]) : null),

            Stat::make('Participación', "{$porcentaje}%")
                ->description('Porcentaje de participación')
                ->color('info'),
        ];
    }
}
