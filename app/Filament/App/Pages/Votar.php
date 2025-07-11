<?php

namespace App\Filament\App\Pages;

use App\Models\Candidato;
use App\Models\Voto;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class Votar extends Page
{
    protected static ?string $title = 'Elige tu candidato';
    protected static string $view = 'filament.app.pages.votar';

    public $candidatos;
    public $candidato_id;

    public function mount(): void
    {
        $this->candidatos = Candidato::with('categoria')->get()->toArray();

        // Agregar "voto en blanco" como opción
        $this->candidatos[] = [
            'id' => 'blanco',
            'nombres' => 'Voto en blanco',
            'apellidos' => '',
            'foto' => null,
            'categoria' => ['nombre' => null],
        ];
    }

    public function votar()
    {
        // Validar si el estudiante ya votó
        if (Voto::where('estudiante_id', auth()->id())->exists()) {
            Notification::make()
                ->title('Ya has votado')
                ->danger()
                ->body('Tu voto ya fue registrado anteriormente.')
                ->send();
            return;
        }

        // Validar que se haya seleccionado un candidato
        if (empty($this->candidato_id)) {
            Notification::make()
                ->title('Votación incompleta')
                ->warning()
                ->body("Por favor seleccione un candidato para registrar su voto.")
                ->send();
            return;
        }

        // Registrar el voto
        Voto::create([
            'estudiante_id' => auth()->id(),
            'candidato_id' => $this->candidato_id !== 'blanco' ? $this->candidato_id : null,
        ]);

        

        // Cerrar sesión y redirigir
        Auth::guard('students')->logout();
        session()->invalidate();
        session()->regenerateToken();

        Notification::make()
            ->title('¡Voto registrado con éxito!')
            ->info()
            ->icon('heroicon-o-face-smile')
            ->body('Gracias por participar en la elección.')
            ->send();

        return redirect('/');
    }
}
