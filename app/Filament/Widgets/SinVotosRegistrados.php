<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class SinVotosRegistrados extends Widget
{
    protected static string $view = 'filament.widgets.sin-votos-registrados';

    protected int | string | array $columnSpan = 12;
}
