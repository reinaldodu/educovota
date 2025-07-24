<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Auth\Login;

class LoginAdmin extends Login
{
    public function getHeading(): string
    {
        return 'Ingrese a EducoVota';
    }

    // public function getSubheading(): string
    // {
    //     return 'Accede con tus credenciales institucionales';
    // }

    public function getTitle(): string
    {
        return 'EducoVota - Inicio de sesión';
    }
}
