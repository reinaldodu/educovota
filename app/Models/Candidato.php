<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Storage;

class Candidato extends Model
{
    /** @use HasFactory<\Database\Factories\CandidatoFactory> */
    use HasFactory;

    //Relación un canditato pertenece a una categoria
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    //Relación un candidato tiene muchos votos
    public function votos()
    {
        return $this->hasMany(Voto::class);
    }

    //Eliminar la foto del candidato al eliminar el registro
    protected static function booted()
    {
        // Al crear un candidato, asignar el orden automáticamente
        static::creating(function ($candidato) {
            // Si ya hay candidatos, el siguiente orden será el mayor + 1
            $maxOrden = static::max('orden') ?? 0;
            $candidato->orden = $maxOrden + 1;
        });

        // Eliminar foto del candidato al eliminar el registro
        static::deleting(function ($candidato) {
            if ($candidato->foto && Storage::disk('public')->exists($candidato->foto)) {
                Storage::disk('public')->delete($candidato->foto);
            }
        });

         // Eliminar foto anterior al actualizar la foto de un candidato
        static::updating(function ($candidato) {
            // Solo si la foto ha cambiado
            if ($candidato->isDirty('foto')) {
                $original = $candidato->getOriginal('foto');

                if ($original && Storage::disk('public')->exists($original)) {
                    Storage::disk('public')->delete($original);
                }
            }
        });
    }
}
