<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        
        // Ejecutar el seeder de configuración
        $this->call(ConfiguracionSeeder::class);
        
        // Ejecutar el seeder de grados
        $this->call(GradoSeeder::class);

        User::factory()->create([
            'name' => 'Administrador del Sistema',
            'email' => 'admin@email.co',
            'password' => bcrypt('admin'),
        ]);
    }
}
