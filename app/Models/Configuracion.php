<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    /** @use HasFactory<\Database\Factories\ConfiguracionFactory> */
    use HasFactory;
    protected $table = 'configuraciones';

    protected $fillable = [
        'nombre_institucion',
        'logo',
        'votacion_activa',
        'requerir_password',
    ];

    // Se utiliza para evitar que se creen múltiples instancias de configuración
    public static function getInstance(): self
    {
        return static::first() ?? static::create([
            'nombre_institucion' => '',
            'votacion_activa' => false,
            'requerir_password' => false,
        ]);
    }
}
