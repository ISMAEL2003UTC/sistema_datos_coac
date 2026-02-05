<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IncidenteSeguridad;

class IncidenteSeguridadController extends Controller
{
    public function index()
    {
        $incidentes = IncidenteSeguridad::orderBy('id')->get();

        $ultimo = IncidenteSeguridad::orderBy('id', 'desc')->first();

        $numero = 1;

        if ($ultimo) {
            $numero = intval(substr($ultimo->codigo, 4)) + 1;
        }

        $siguienteCodigo = 'INC-' . str_pad($numero, 3, '0', STR_PAD_LEFT);

        return view('index', [
            'incidentes' => $incidentes,
            'siguienteCodigo' => $siguienteCodigo,
            'section' => 'incidentes'
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fecha' => [
                'required',
                function ($attribute, $value, $fail) {
                    try {
                        $fecha = \Carbon\Carbon::parse($value);
                    } catch (\Exception $e) {
                        return $fail('Formato de fecha/hora inválido.');
                    }

                    // Rango permitido: ayer y hoy (2 días). Ej: hoy=2026-02-05 -> 2026-02-04 y 2026-02-05
                    $inicio = now()->subDay()->startOfDay();
                    $limiteSuperior = now()->endOfDay();

                    if ($fecha->lt($inicio) || $fecha->gt($limiteSuperior)) {
                        return $fail('La fecha del incidente debe ser ayer o hoy (' . $inicio->format('d/m/Y') . ' - ' . $limiteSuperior->format('d/m/Y') . ').');
                    }

                    // Validar hora: permitido entre 08:00 y 21:00 (inclusive 08:00 y 21:00 exacto)
                    $hour = (int) $fecha->format('H');
                    $minute = (int) $fecha->format('i');

                    if ($hour < 9) {
                        return $fail('La hora del incidente debe ser a partir de las 09:00.');
                    }

                    if ($hour > 21 || ($hour === 21 && $minute > 0)) {
                        return $fail('La hora del incidente no puede ser posterior a las 21:00.');
                    }
                }
            ],

            'severidad' => 'required|string|max:30',
            'descripcion' => 'required|string',
            'tipo' => 'required|string|max:50',
            'sujetos_afectados' => 'required|integer|min:1',
            'estado' => 'required|string|max:30',
        ]);

        // `sujetos_afectados` es requerido y debe ser >= 1 (validado arriba)

        $ultimo = IncidenteSeguridad::orderBy('id', 'desc')->first();

        $numero = 1;

        if ($ultimo) {
            $numero = intval(substr($ultimo->codigo, 4)) + 1;
        }

        $validated['codigo'] = 'INC-' . str_pad($numero, 3, '0', STR_PAD_LEFT);

        // Guardar
        IncidenteSeguridad::create($validated);

        return redirect('/#incidentes')->with('success', 'Incidente registrado correctamente');

    }

    public function edit(string $id)
    {
        $incidentes = IncidenteSeguridad::orderBy('id')->get();
        $incidenteEditar = IncidenteSeguridad::findOrFail($id);

        return view('index', [
            'incidentes' => $incidentes,
            'incidenteEditar' => $incidenteEditar,
            'section' => 'incidentes'
        ]);
    }

    public function update(Request $request, string $id)
    {
        $incidente = IncidenteSeguridad::findOrFail($id);

        $validated = $request->validate([
            'fecha' => [
                'required',
                function ($attribute, $value, $fail) {
                    try {
                        $fecha = \Carbon\Carbon::parse($value);
                    } catch (\Exception $e) {
                        return $fail('Formato de fecha/hora inválido.');
                    }

                    // Rango permitido: ayer y hoy (2 días)
                    $inicio = now()->subDay()->startOfDay();
                    $limiteSuperior = now()->endOfDay();

                    if ($fecha->lt($inicio) || $fecha->gt($limiteSuperior)) {
                        return $fail('La fecha del incidente debe ser ayer o hoy (' . $inicio->format('d/m/Y') . ' - ' . $limiteSuperior->format('d/m/Y') . ').');
                    }

                    $hour = (int) $fecha->format('H');
                    $minute = (int) $fecha->format('i');

                    if ($hour < 9) {
                        return $fail('La hora del incidente debe ser a partir de las 09:00.');
                    }

                    if ($hour > 21 || ($hour === 21 && $minute > 0)) {
                        return $fail('La hora del incidente no puede ser posterior a las 21:00.');
                    }
                }
            ],
            'severidad' => 'required|string|max:30',
            'descripcion' => 'required|string',
            'tipo' => 'required|string|max:50',
            'sujetos_afectados' => 'required|integer|min:1',
            'estado' => 'required|string|max:30',
        ]);

        // `sujetos_afectados` es requerido y debe ser >= 1 (validado arriba)

        $incidente->update($validated);

        return redirect('/#incidentes')->with('success', 'Incidente registrado correctamente');
    }

    public function destroy(string $id)
    {
        abort(403, 'No está permitido eliminar incidentes de seguridad.');
    }

    
}
