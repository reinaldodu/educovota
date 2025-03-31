<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grado extends Model
{
    /** @use HasFactory<\Database\Factories\GradoFactory> */
    use HasFactory;

    //Relación un grado tiene muchos estudiantes
    public function estudiantes()
    {
        return $this->hasMany(Estudiante::class);
    }
}