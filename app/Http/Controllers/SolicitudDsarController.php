<?php

namespace App\Http\Controllers;

use App\Models\SolicitudDsar;
use App\Models\SujetoDato;
use Illuminate\Http\Request;

class SolicitudDsarController extends Controller
{
    public function index()
    {
        $dsars   = SolicitudDsar::with('sujeto')->orderBy('id','desc')->get();
        $sujetos = SujetoDato::orderBy('nombre')->orderBy('apellido')->get();

        return view('index', compact('dsars', 'sujetos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cedula'          => 'required',
            'tipo'            => 'required|in:acceso,rectificacion,cancelacion,oposicion,portabilidad',
            'descripcion'     => 'required',
            'fecha_solicitud' => 'required|date|in:' . now()->toDateString(),
            'fecha_limite'    => 'nullable|date|after_or_equal:' . now()->addDays(2)->toDateString(),
            'estado'          => 'required|in:pendiente,proceso,completada,rechazada',
        ]);

        $sujeto = SujetoDato::where('cedula', $request->cedula)->firstOrFail();

        // 🔢 GENERAR NÚMERO AUTOMÁTICO S001, S002, S003...
        $ultimo = SolicitudDsar::orderBy('id', 'desc')->first();

        if ($ultimo && preg_match('/S(\d+)/', $ultimo->numero_solicitud, $matches)) {
            $numero = intval($matches[1]) + 1;
        } else {
            $numero = 1;
        }

        $numeroSolicitud = 'S' . str_pad($numero, 3, '0', STR_PAD_LEFT);

        SolicitudDsar::create([
            'numero_solicitud' => $numeroSolicitud,
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
            'text'  => 'Solicitud registrada correctamente'
        ]);
    }

    public function update(Request $request, $id)
    {
        $dsar = SolicitudDsar::findOrFail($id);

        $request->validate([
            'cedula'          => 'required',
            'tipo'            => 'required|in:acceso,rectificacion,cancelacion,oposicion,portabilidad',
            'descripcion'     => 'required',
            'fecha_solicitud' => 'required|date|in:' . now()->toDateString(),
            'fecha_limite'    => 'nullable|date|after_or_equal:' . now()->addDays(2)->toDateString(),
            'estado'          => 'required|in:pendiente,proceso,completada,rechazada',
        ]);

        $sujeto = SujetoDato::where('cedula', $request->cedula)->firstOrFail();

        $dsar->update([
            'sujeto_id'        => $sujeto->id,
            'tipo'             => $request->tipo,
            'descripcion'      => $request->descripcion,
            'fecha_solicitud'  => $request->fecha_solicitud,
            'fecha_limite'     => $request->fecha_limite,
            'estado'           => $request->estado,
        ]);

        return redirect()->back();
    }
}
