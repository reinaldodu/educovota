<?php

namespace App\Filament\App\Pages;

use App\Models\Candidato;
use App\Models\Voto;
use App\Models\Configuracion;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class Votar extends Page
{
    protected static string $view = 'filament.app.pages.votar';

    public $candidatos;
    public $candidato_id;
    public $logo;
    public $nombre_institucion;
    public $descripcion_votaciones;
    public $user;

    public function mount(): void
    {
        // Obtener lista de candidatos con su categoría
        $this->candidatos = Candidato::with('categoria')->get()->toArray();

        // Agregar opción de voto en blanco
        $this->candidatos[] = [
            'id' => 'blanco',
            'nombres' => 'Voto en blanco',
            'apellidos' => '',
            'foto' => null,
            'categoria' => ['nombre' => null],
        ];

        // Cargar logo y nombre_institucion desde la configuración
        $config = Configuracion::first();

        $this->logo = $config && $config->logo
            ? asset('storage/' . $config->logo)
            : asset('images/default-logo.png');

        $this->nombre_institucion = $config->nombre_institucion ?? 'Educovota';
        $this->descripcion_votaciones = $config->descripcion_votaciones ?? '';
        $this->user = Auth::guard('students')->user();
    }

    public function votar()
    {
        // Verificar si el estudiante ya votó
        if (Voto::where('estudiante_id', auth()->id())->exists()) {
            Notification::make()
                ->title('Ya has votado')
                ->danger()
                ->body('Tu voto ya fue registrado anteriormente.')
                ->send();
            return;
        }

        // Verificar si se seleccionó un candidato
        // if (empty($this->candidato_id)) {
        //     Notification::make()
        //         ->title('Votación incompleta')
        //         ->warning()
        //         ->body("Por favor seleccione un candidato para registrar su voto.")
        //         ->send();
        //     return;
        // }

        // Registrar el voto
        Voto::create([
            'estudiante_id' => auth()->id(),
            'candidato_id' => $this->candidato_id !== 'blanco' ? $this->candidato_id : null,
        ]);

        // Cerrar sesión del estudiante
        Auth::guard('students')->logout();
        session()->invalidate();
        session()->regenerateToken();

        // Notificar éxito
        Notification::make()
            ->title('¡Voto registrado con éxito!')
            ->info()
            ->icon('heroicon-o-face-smile')
            ->body('Gracias por participar en la elección.')
            ->send();

        return redirect('/');
    }
}
