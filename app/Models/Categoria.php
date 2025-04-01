<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    /** @use HasFactory<\Database\Factories\CategoriaFactory> */
    use HasFactory;

    //Relación una categoria tiene muchos candidatos
    public function candidatos()
    {
        return $this->hasMany(Candidato::class);
    }
}
