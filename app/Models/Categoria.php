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

    protected static function booted(): void
    {
        // Al crear una nueva categoría, asignar el orden automáticamente
        static::creating(function ($categoria) {
            $categoria->orden = static::max('orden') + 1;
        });
    }
}
