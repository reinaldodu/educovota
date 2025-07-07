<?php

namespace App\Actions;

use App\Models\Estudiante;
use App\Models\Grado;
use Illuminate\Support\Facades\Hash;
use Spatie\SimpleExcel\SimpleExcelReader;

class ImportarEstudiantes
{
    public function ejecutar(string $path): array
    {
        $delimiter = $this->detectarSeparador($path);

        $rows = SimpleExcelReader::create($path)
            ->useDelimiter($delimiter)
            ->headersToSnakeCase()
            ->getRows()
            ->collect(); // Convertimos LazyCollection a Collection

        $errores = collect();
        $validos = collect();

        foreach ($rows as $index => $row) {
            $fila = $index + 2; // Para contar desde la fila real (saltando encabezado)
            $errorFila = [];

            // Validación: grado_id requerido y debe existir
            if (empty($row['grado_id']) || !Grado::find($row['grado_id'])) {
                $errorFila[] = 'Grado inválido o inexistente';
            }

            // nombres y apellidos requeridos
            if (empty($row['nombres'])) {
                $errorFila[] = 'Nombres requeridos';
            }

            if (empty($row['apellidos'])) {
                $errorFila[] = 'Apellidos requeridos';
            }

            // documento requerido y único
            if (empty($row['documento'])) {
                $errorFila[] = 'Documento requerido';
            } elseif (Estudiante::where('documento', $row['documento'])->exists()) {
                $errorFila[] = 'Documento duplicado en base de datos';
            }

            if (!empty($errorFila)) {
                $errores->push([
                    'fila' => $fila,
                    'errores' => implode('; ', $errorFila),
                    'datos' => $row,
                ]);
            } else {
                $validos->push($row);
            }
        }

        if ($errores->isNotEmpty()) {
            return [
                'importados' => 0,
                'errores' => $errores,
                'exitosos' => collect(),
            ];
        }

        // Importar si no hay errores
        $importados = 0;
        $exitosos = collect();

        foreach ($validos as $row) {
            $estudiante = Estudiante::create([
                'grado_id'  => $row['grado_id'],
                'nombres'   => $row['nombres'],
                'apellidos' => $row['apellidos'],
                'documento' => $row['documento'],
                'password'  => isset($row['password']) ? Hash::make($row['password']) : null,
            ]);

            $importados++;
            $exitosos->push($estudiante);
        }

        return [
            'importados' => $importados,
            'errores' => collect(),
            'exitosos' => $exitosos,
        ];
    }

    private function detectarSeparador(string $path): string
    {
        $linea = '';
        $fh = fopen($path, 'r');
        if ($fh) {
            $linea = fgets($fh); // leer primera línea
            fclose($fh);
        }

        $comas = substr_count($linea, ',');
        $puntosYComas = substr_count($linea, ';');

        return $comas >= $puntosYComas ? ',' : ';';
    }
}
