<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Estudiante extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\EstudianteFactory> */
    use HasFactory, Notifiable;

    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        return $panel->getId() === 'app';
    }

    // Crear el campo virtual (accesor) name para mostrar el nombre completo al autenticarse
    public function getNameAttribute(): string
    {
        return trim("{$this->nombres} {$this->apellidos}") ?: 'Estudiante';
    }

    //Relación un estudiante pertenece a un grado
    public function grado()
    {
        return $this->belongsTo(Grado::class);
    }
}