<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Configuracion;

class ConfiguracionSeeder extends Seeder
{
    public function run(): void
    {
        Configuracion::firstOrCreate([
            'id' => 1
        ], [
            'nombre_institucion' => 'Mi Institución',
            'votacion_activa' => false,
            'requerir_password' => false,
        ]);
    }
}
