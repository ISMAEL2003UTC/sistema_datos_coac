<?php

namespace App\Http\Controllers;

use App\Models\ActividadProcesamiento;
use Illuminate\Http\Request;

class ActividadProcesamientoController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(
            [
                'codigo' => 'required|string|max:50',
                'nombre' => 'required|string|max:150',
                'responsable' => 'required|string|max:150',
                'finalidad' => 'required|string',
                'base_legal' => 'required|string',
            ],
            [
                'codigo.required' => 'El código de la actividad es obligatorio.',
                'codigo.max' => 'El código no puede tener más de 50 caracteres.',

                'nombre.required' => 'El nombre de la actividad es obligatorio.',
                'nombre.max' => 'El nombre no puede tener más de 150 caracteres.',

                'responsable.required' => 'El responsable es obligatorio.',
                'responsable.max' => 'El responsable no puede tener más de 150 caracteres.',

                'finalidad.required' => 'La finalidad del tratamiento es obligatoria.',

                'base_legal.required' => 'Debe seleccionar una base legal.',
            ]
        );

        ActividadProcesamiento::create([
            'codigo' => $request->codigo,
            'nombre' => $request->nombre,
            'responsable' => $request->responsable,
            'finalidad' => $request->finalidad,
            'base_legal' => $request->base_legal,
            'categorias_datos' => $request->categorias_datos,
            'plazo_conservacion' => $request->plazo_conservacion,
            'medidas_seguridad' => $request->medidas_seguridad,
            'estado' => 'activo',
        ]);

        return redirect()->back()
            ->with('success', 'Actividad registrada correctamente.');
    }
}
