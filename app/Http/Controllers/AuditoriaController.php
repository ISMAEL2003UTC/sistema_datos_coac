<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\Usuario; // Importamos el modelo Usuario
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuditoriaController extends Controller
{
    public function index()
    {
        // Traemos todas las auditorías paginadas
        $auditorias = Auditoria::orderBy('id', 'desc')->paginate(10);

        // Traemos solo los usuarios con rol 'auditor' y estado 'activo'
        $auditores = Usuario::where('rol', 'auditor')
                            ->where('estado', 'activo')
                            ->get();

        return view('auditorias.index', compact('auditorias', 'auditores'));
    }

    public function show($id)
    {
        $auditoria = Auditoria::find($id);

        if (!$auditoria) {
            return redirect()->route('auditorias.index')
                             ->with('error', 'Auditoría no encontrada');
        }

        return view('auditorias.show', compact('auditoria'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo_aud'     => 'required',
            'auditor_id'   => 'required|exists:usuarios,id', // Validamos que exista el auditor
            'fecha_inicio' => 'required|date',
            'fecha_fin'    => 'nullable|date',
            'estado_aud'   => 'required',
            'alcance'      => 'nullable|string',
            'hallazgos'    => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {

            $ultimoCodigo = Auditoria::lockForUpdate()->max('codigo');
            $nuevoCodigo = $ultimoCodigo ? $ultimoCodigo + 1 : 1;

            Auditoria::create([
                'codigo'        => $nuevoCodigo,
                'tipo'          => $request->tipo_aud,
                'auditor_id'    => $request->auditor_id, // Guardamos el id del auditor
                'fecha_inicio'  => $request->fecha_inicio,
                'fecha_fin'     => $request->fecha_fin,
                'estado'        => $request->estado_aud,
                'alcance'       => $request->alcance,
                'hallazgos'     => $request->hallazgos,
            ]);
        });

        return redirect()->back()->with('success', 'Auditoría registrada correctamente');
    }
}
