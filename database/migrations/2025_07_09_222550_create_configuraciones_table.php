<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('configuraciones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_institucion');
            $table->string('descripcion_votaciones')->nullable();
            $table->string('logo')->nullable();
            $table->boolean('votacion_activa')->default(false);
            $table->boolean('requerir_password')->default(false);
            // color de fondo del tarjetón
            $table->string('color_fondo_tarjeton')->default('#ffffff');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configuracions');
    }
};
