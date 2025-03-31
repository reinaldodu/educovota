<?php

namespace App\Filament\App\Pages;

use Filament\Http\Responses\Auth\Contracts\LoginResponse;

use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Support\Facades\Auth;

use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;

class LoginEstudiante extends BaseLogin
{

    // Método para establecer el encabezado de la página
    public function getHeading(): string
    {
        return 'Sistema de votación';
    }

    // Método para crear el formulario de inicio de sesión de estudiantes
    public function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('documento')
                ->label('Documento')
                ->required()
                ->autocomplete('off')
                ->hint(fn () => session('error') ? '✖ ' . session('error') : '')
                ->hintColor('danger'),

            TextInput::make('password')
                ->label('Contraseña')
                ->password()
                ->autocomplete('off')
                ->hidden(fn () => !env('PASSWORD_VOTACION', false))
                ->required(fn () => env('PASSWORD_VOTACION', false)),
        ]);
    }

    // Método para manejar la autenticación del estudiante
    public function authenticate(): ?LoginResponse
    {
        $formState = $this->form->getState();
        $requiresPassword = env('PASSWORD_VOTACION', false); 
    
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
    
        return app(LoginResponse::class);
    }
    
    // Método auxiliar para manejar el error de autenticación
    protected function sendAuthError()
    {
        session()->flash('error', 'Credenciales incorrectas.');
        return null;
    }
}
