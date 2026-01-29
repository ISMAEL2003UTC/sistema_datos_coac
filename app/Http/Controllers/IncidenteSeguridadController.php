<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IncidenteSeguridad;

class IncidenteSeguridadController extends Controller
{
    public function index()
    {
        $incidentes = IncidenteSeguridad::orderBy('id')->get();

        return view('index', [
            'incidentes' => $incidentes,
            'section' => 'incidentes' // 👈 controla la vista
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fecha' => [
                'required',
                function ($attribute, $value, $fail) {
                    $fecha = \Carbon\Carbon::parse($value);

                    $inicio = now()->startOfMonth();      // 1 del mes actual
                    $hoy = now()->startOfDay();           // hoy a las 00:00

                    if ($fecha->lt($inicio) || $fecha->gte($hoy)) {
                        $fail('La fecha del incidente debe estar entre el 1 del mes actual y el día anterior.');
                    }

                }
            ],

            'severidad' => 'required|string|max:30',
            'descripcion' => 'required|string',
            'tipo' => 'required|string|max:50',
            'sujetos_afectados' => 'nullable|integer|min:0',
            'estado' => 'required|string|max:30',
        ]);

        $validated['sujetos_afectados'] = $validated['sujetos_afectados'] ?? 0;

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
                    $fecha = \Carbon\Carbon::parse($value);
                    $inicio = now()->startOfMonth();      // 1 del mes actual
                    $hoy = now()->startOfDay();           // hoy a las 00:00

                    if ($fecha->lt($inicio) || $fecha->gte($hoy)) {
                        $fail('La fecha del incidente debe estar entre el 1 del mes actual y el día anterior.');
                    }
                }
            ],
            'severidad' => 'required|string|max:30',
            'descripcion' => 'required|string',
            'tipo' => 'required|string|max:50',
            'sujetos_afectados' => 'nullable|integer|min:0',
            'estado' => 'required|string|max:30',
        ]);

        $validated['sujetos_afectados'] = $validated['sujetos_afectados'] ?? 0;

        $incidente->update($validated);

        return redirect('/#incidentes')->with('success', 'Incidente registrado correctamente');
    }

    public function destroy(string $id)
    {
        abort(403, 'No está permitido eliminar incidentes de seguridad.');
    }
}
