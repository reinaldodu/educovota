<?php

namespace App\Filament\App\Pages;

use App\Models\Candidato;
use App\Models\Categoria;
use App\Models\Voto;
use App\Models\Configuracion;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class Votar extends Page
{
    protected static string $view = 'filament.app.pages.votar';

    public $candidatos = []; // [nombre_categoria => [candidatos]]
    public $selecciones = []; // [categoria_id => candidato_id o 'blanco_{id}']
    public $config;
    public $user;

    public function mount(): void
    {
        $this->user = Auth::guard('students')->user();
        $this->config = Configuracion::first();

        // Obtener todas las categorías con sus candidatos sin ordenar
        $categorias = Categoria::with(['candidatos'])->get();

        // Agrupar candidatos por nombre de la categoría
        foreach ($categorias as $categoria) {
            $candidatos = $categoria->candidatos->map(function ($candidato) {
                return $candidato->toArray();
            })->all();

            // Agregar manualmente opción de voto en blanco
            $candidatos[] = [
                'id' => 'blanco_' . $categoria->id,
                'nombres' => 'Voto en blanco',
                'apellidos' => '',
                'foto' => null,
                'categoria_id' => $categoria->id,
                'categoria' => ['nombre' => $categoria->nombre],
            ];

            $this->candidatos[$categoria->nombre] = $candidatos;
        }
    }

    public function votar()
    {
        $estudianteId = auth()->id();

        // Verificar si ya votó
        if (Voto::where('estudiante_id', $estudianteId)->exists()) {
            Notification::make()
                ->title('Ya has votado')
                ->danger()
                ->body('Tu voto ya fue registrado anteriormente.')
                ->send();
            return;
        }

        // Guardar los votos por categoría
        foreach ($this->selecciones as $categoriaId => $valor) {
            $candidatoId = str_starts_with($valor, 'blanco_') ? null : $valor;

            Voto::create([
                'estudiante_id' => $estudianteId,
                'categoria_id' => $categoriaId,
                'candidato_id' => $candidatoId,
            ]);
        }

        // Cerrar sesión del estudiante
        Auth::guard('students')->logout();
        session()->invalidate();
        session()->regenerateToken();

        // Notificar éxito
        Notification::make()
            ->title('¡Votación registrada con éxito!')
            ->info()
            ->icon('heroicon-o-face-smile')
            ->body('Gracias por participar en la elección.')
            ->send();

        return redirect('/');
    }
}
