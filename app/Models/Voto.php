<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voto extends Model
{
    /** @use HasFactory<\Database\Factories\VotoFactory> */
    use HasFactory;

    //Relación un voto pertenece a un estudiante
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class);
    }
    
    //Relación un voto pertenece a un candidato
    public function candidato()
    {
        return $this->belongsTo(Candidato::class);
    }

}
