<?php

namespace App\Http\Controllers;

use App\Models\SolicitudDsar;
use App\Models\SujetoDato;
use Illuminate\Http\Request;

class SolicitudDsarController extends Controller
{
    public function index()
    {
        // ✅ Para el select (cédula + nombre + apellido)
        $sujetos = SujetoDato::orderBy('apellido')->orderBy('nombre')->get();

        // ✅ Para la tabla DSAR
        $dsars = SolicitudDsar::with('sujeto')->orderBy('id','desc')->get();

        return view('index', compact('sujetos', 'dsars'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'numero_solicitud' => ['required', 'string', 'max:255'],
            'cedula'           => ['required', 'string', 'max:20'],
            'tipo'             => ['required', 'in:acceso,rectificacion,cancelacion,oposicion,portabilidad'],
            'descripcion'      => ['required', 'string'],
            // ✅ Solo HOY
            'fecha_solicitud'  => ['required', 'date', 'in:' . now()->toDateString()],
            // ✅ Desde HOY en adelante
            'fecha_limite'     => ['nullable', 'date', 'after_or_equal:today'],
            'estado'           => ['required', 'in:pendiente,proceso,completada,rechazada'],
        ]);

        $sujeto = SujetoDato::where('cedula', $request->cedula)->first();

        if (!$sujeto) {
            return back()->withErrors(['cedula' => 'La cédula no existe'])->withInput();
        }

        SolicitudDsar::create([
            'numero_solicitud' => $request->numero_solicitud,
            'sujeto_id'        => $sujeto->id,
            'tipo'             => $request->tipo,
            'descripcion'      => $request->descripcion,
            'fecha_solicitud'  => $request->fecha_solicitud,
            'fecha_limite'     => $request->fecha_limite,
            'estado'           => $request->estado,
        ]);

        return redirect()->route('index')->with('swal', [
            'icon'  => 'success',
            'title' => 'Solicitud DSAR',
            'text'  => 'La solicitud se guardó correctamente'
        ]);
    }

    public function update(Request $request, $id)
    {
        $dsar = SolicitudDsar::findOrFail($id);

        $request->validate([
            'numero_solicitud' => ['required', 'string', 'max:255'],
            'cedula'           => ['required', 'string', 'max:20'],
            'tipo'             => ['required', 'in:acceso,rectificacion,cancelacion,oposicion,portabilidad'],
            'descripcion'      => ['required', 'string'],
            // ✅ Solo HOY
            'fecha_solicitud'  => ['required', 'date', 'in:' . now()->toDateString()],
            // ✅ Desde HOY en adelante
            'fecha_limite'     => ['nullable', 'date', 'after_or_equal:today'],
            'estado'           => ['required', 'in:pendiente,proceso,completada,rechazada'],
        ]);

        $sujeto = SujetoDato::where('cedula', $request->cedula)->first();
        if (!$sujeto) {
            return back()->withErrors(['cedula' => 'La cédula no existe'])->withInput();
        }

        $dsar->update([
            'numero_solicitud' => $request->numero_solicitud,
            'sujeto_id'        => $sujeto->id,
            'tipo'             => $request->tipo,
            'descripcion'      => $request->descripcion,
            'fecha_solicitud'  => $request->fecha_solicitud,
            'fecha_limite'     => $request->fecha_limite,
            'estado'           => $request->estado,
        ]);

        return redirect()->back()->with('swal', [
            'icon'  => 'success',
            'title' => 'Actualización',
            'text'  => 'La solicitud se actualizó correctamente'
        ]);
    }



    public function cambiarEstado(Request $request, $id)
    {
        $request->validate([
            'estado' => ['required', 'in:pendiente,proceso,completada,rechazada'],
        ]);

        $dsar = SolicitudDsar::findOrFail($id);
        $dsar->estado = $request->estado;
        $dsar->save();

        return redirect()->back()->with('swal', [
            'icon'  => 'success',
            'title' => 'Estado actualizado',
            'text'  => 'El estado se cambió correctamente'
        ]);
    }
}
