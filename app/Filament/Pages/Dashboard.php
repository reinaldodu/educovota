<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Widgets\VotacionesTotales;
use App\Filament\Widgets\VotosPorCandidato;
use App\Filament\Widgets\CandidatoMasVotado;
use App\Filament\Widgets\ComparacionVotos;
use App\Filament\Widgets\ParticipacionPorCategoria;
use App\Filament\Widgets\ParticipacionPorGrado;
use App\Filament\Widgets\PorcentajeParticipacion;
use App\Filament\Widgets\RankingCandidatos;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationLabel = 'Panel de votación';
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $title = 'Panel de Votación';

    protected function getHeaderWidgets(): array
    {
        return [
            VotacionesTotales::class,
        ];
    }

    public function getWidgets(): array
    {
        return [
            VotosPorCandidato::class,
            ComparacionVotos::class,
            RankingCandidatos::class,
            ParticipacionPorGrado::class,
        ];
    }

    public function getColumns(): int | string | array
    {
        return 12; // Usa una rejilla de 12 columnas
    }
}
