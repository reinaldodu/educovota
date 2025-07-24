<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Grado;

class GradoSeeder extends Seeder
{
    public function run(): void
    {
        $grados = [
            1  => 'Primero',
            2  => 'Segundo',
            3  => 'Tercero',
            4  => 'Cuarto',
            5  => 'Quinto',
            6  => 'Sexto',
            7  => 'Séptimo',
            8  => 'Octavo',
            9  => 'Noveno',
            10 => 'Décimo',
            11 => 'Undécimo',
        ];

        foreach ($grados as $id => $nombre) {
            $nombreMin = strtolower($nombre);
            Grado::updateOrCreate([
                'id' => $id,
                'nombre' => $nombre,
                'descripcion' => "Estudiantes de grado {$nombreMin}"
            ]);
        }
    }
}
