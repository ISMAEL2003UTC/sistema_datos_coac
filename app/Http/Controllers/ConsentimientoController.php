<?php

namespace App\Http\Controllers;

use App\Models\Consentimiento;
use App\Models\SujetoDato;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ConsentimientoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $consentimientos = Consentimiento::orderBy('id')->get();
        return view('index', compact('consentimientos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Obtener la fecha de hoy
        $fechaHoy = Carbon::now()->format('Y-m-d');
        
        // Validaciones requeridas
        $request->validate([
            'sujeto_id' => 'required|exists:sujetos_datos,id',
            'proposito' => 'required|string',
            'estado' => 'required|string|in:otorgado,revocado,pendiente',
            'metodo' => 'required|string',
            'fecha_otorgamiento' => 'required|date|date_equals:' . $fechaHoy,
            'fecha_expiracion' => 'required|date|after_or_equal:' . $fechaHoy
        ], [
            'sujeto_id.required' => 'El sujeto de datos es requerido',
            'proposito.required' => 'El propósito del tratamiento es requerido',
            'estado.required' => 'El estado es requerido',
            'metodo.required' => 'El método de obtención es requerido',
            'fecha_otorgamiento.required' => 'La fecha de otorgamiento es requerida',
            'fecha_otorgamiento.date_equals' => 'La fecha de otorgamiento debe ser hoy',
            'fecha_expiracion.required' => 'La fecha de expiración es requerida',
            'fecha_expiracion.after_or_equal' => 'La fecha de expiración no puede ser anterior a hoy'
        ]);

        Consentimiento::create([
            'sujeto_id' => $request->sujeto_id,
            'proposito' => $request->proposito,
            'estado' => $request->estado,
            'fecha_otorgamiento' => $fechaHoy,
            'metodo' => $request->metodo,
            'fecha_expiracion' => $request->fecha_expiracion,
            'activo' => true
        ]);

        return redirect()->back()->with('success', 'Consentimiento registrado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $consentimiento = Consentimiento::findOrFail($id);
        $fechaOtorgamientoOriginal = $consentimiento->fecha_otorgamiento;

        // Validaciones
        $request->validate([
            'sujeto_id' => 'required|exists:sujetos_datos,id',
            'proposito' => 'required|string',
            'estado' => 'required|string|in:otorgado,revocado,pendiente',
            'metodo' => 'required|string',
            'fecha_expiracion' => 'required|date|after_or_equal:' . $fechaOtorgamientoOriginal
        ], [
            'sujeto_id.required' => 'El sujeto de datos es requerido',
            'proposito.required' => 'El propósito del tratamiento es requerido',
            'estado.required' => 'El estado es requerido',
            'metodo.required' => 'El método de obtención es requerido',
            'fecha_expiracion.required' => 'La fecha de expiración es requerida',
            'fecha_expiracion.after_or_equal' => 'La fecha de expiración no puede ser anterior a la fecha de otorgamiento'
        ]);

        $consentimiento->update([
            'sujeto_id' => $request->sujeto_id,
            'proposito' => $request->proposito,
            'estado' => $request->estado,
            'metodo' => $request->metodo,
            'fecha_expiracion' => $request->fecha_expiracion
        ]);

        return redirect()->back()->with('success', 'Consentimiento actualizado correctamente');
    }

    /**
     * Toggle the active status of the consentimiento
     */
    public function toggleActivo($id)
    {
        $consentimiento = Consentimiento::findOrFail($id);
        $consentimiento->activo = !$consentimiento->activo;
        $consentimiento->save();

        $mensaje = $consentimiento->activo ? 'Consentimiento activado correctamente' : 'Consentimiento desactivado correctamente';
        return redirect()->back()->with('success', $mensaje);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $consentimiento = Consentimiento::findOrFail($id);
        $consentimiento->delete();

        return redirect()->back()->with('success', 'Consentimiento eliminado correctamente');
    }
}
