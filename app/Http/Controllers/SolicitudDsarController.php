<?php

namespace App\Http\Controllers;

use App\Models\SolicitudDsar;
use App\Models\SujetoDato;
use Illuminate\Http\Request;

class SolicitudDsarController extends Controller
{
    public function index()
    {
        $dsars = SolicitudDsar::with('sujeto')->orderBy('id','desc')->get();
        $sujetos = SujetoDato::orderBy('id','desc')->get();

        return view('index', compact('dsars','sujetos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'numero_solicitud' => 'required',
            'cedula' => 'required',
            'tipo' => 'required',
            'descripcion' => 'required',
            'fecha_solicitud' => 'required',
            'estado' => 'required'
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

        return redirect()->route('index');
    }

    public function update(Request $request, $id)
    {
        $dsar = SolicitudDsar::findOrFail($id);

        $sujeto = SujetoDato::where('cedula', $request->cedula)->first();
        if (!$sujeto) {
            return back()->withErrors(['cedula' => 'La cédula no existe']);
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

        return redirect()->route('index');
    }
}
