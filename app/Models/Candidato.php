<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidato extends Model
{
    /** @use HasFactory<\Database\Factories\CandidatoFactory> */
    use HasFactory;

    //Relación un estudiante pertenece a una categoria
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
}
