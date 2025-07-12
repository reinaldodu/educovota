<?php

namespace App\Filament\App\Pages;

use App\Models\Configuracion;
use App\Models\Voto;
use App\Models\Candidato;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Pages\Auth\Login as BaseLogin;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Auth;

class LoginEstudiante extends BaseLogin
{
    public ?Configuracion $config = null;
    public bool $mostrarFormulario = true;
    public string $motivoBloqueo = '';

    public function mount(): void
    {
        $this->config = Configuracion::getInstance();

        if (! $this->config->votacion_activa) {
            $this->mostrarFormulario = false;
            $this->motivoBloqueo = '⚠️ El sistema no está disponible para votar en este momento.';
            return;
        }

        if (Candidato::count() === 0) {
            $this->mostrarFormulario = false;
            $this->motivoBloqueo = '⚠️ No existen candidatos registrados para votar.';
            return;
        }
    }

    public function getHeading(): string
    {
        return $this->config->nombre_institucion ?? 'Sistema de votación';
    }

    public function getSubheading(): ?string
    {
        return $this->mostrarFormulario ? 'Bienvenido a Educovota' : $this->motivoBloqueo;
    }

    public function getFormActions(): array
    {
        return $this->mostrarFormulario ? parent::getFormActions() : [];
    }

    public function form(Form $form): Form
    {
        if (! $this->mostrarFormulario) {
            return $form->schema([]);
        }

        return $form->schema([
            TextInput::make('documento')
                ->label('Documento')
                ->placeholder('Ingresa tu número de documento')
                ->required()
                ->autocomplete('off')
                ->autofocus()
                ->hint(fn () => session('error') ? '✖ ' . session('error') : '')
                ->hintColor('danger'),

            TextInput::make('password')
                ->label('Contraseña')
                ->password()
                ->autocomplete('off')
                ->hidden(fn () => ! $this->config->requerir_password)
                ->required(fn () => $this->config->requerir_password),
        ]);
    }

    public function authenticate(): ?LoginResponse
    {
        $formState = $this->form->getState();
        $requiresPassword = $this->config->requerir_password;

        if ($requiresPassword) {
            if (!Auth::guard('students')->attempt($formState)) {
                return $this->sendAuthError();
            }
        } else {
            $user = Auth::guard('students')->getProvider()->retrieveByCredentials($formState);

            if (!$user) {
                return $this->sendAuthError();
            }

            Auth::guard('students')->login($user);
        }

        // Validar si ya votó
        if (Voto::where('estudiante_id', Auth::guard('students')->id())->exists()) {
            Auth::guard('students')->logout();
            session()->flash('error', 'Ya registraste tu voto.');
            return null;
        }

        return app(LoginResponse::class);
    }

    protected function sendAuthError()
    {
        session()->flash('error', 'Credenciales incorrectas.');
        return null;
    }
}
