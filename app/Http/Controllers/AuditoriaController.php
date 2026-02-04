<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuditoriaController extends Controller
{
    /**
     * Mostrar listado de auditorías
     */
    public function index()
    {
        $auditorias = Auditoria::with('auditor')->orderBy('id', 'desc')->paginate(10);
        return view('auditorias.index', compact('auditorias'));
    }

    /**
     * Mostrar formulario para crear nueva auditoría
     */
    public function create()
    {
        // Solo usuarios activos con rol 'auditor'
        $auditores = Usuario::where('rol', 'auditor')
                            ->where('estado', 'activo')
                            ->orderBy('nombre')
                            ->get();

        return view('auditorias.create', compact('auditores'));
    }

    /**
     * Guardar nueva auditoría
     */
    public function store(Request $request)
    {
        $request->validate([
            'tipo_aud'     => 'required|string|max:150',
            'auditor_id'   => 'required|exists:usuarios,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin'    => 'nullable|date|after_or_equal:fecha_inicio',
            'estado_aud'   => 'required|string|max:50',
            'alcance'      => 'nullable|string',
            'hallazgos'    => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {

            // Generar nuevo código incremental
            $ultimoCodigo = Auditoria::lockForUpdate()->max('codigo');
            $nuevoCodigo = $ultimoCodigo ? $ultimoCodigo + 1 : 1;

            Auditoria::create([
                'codigo'       => $nuevoCodigo,
                'tipo'         => $request->tipo_aud,
                'auditor_id'   => $request->auditor_id,
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin'    => $request->fecha_fin,
                'estado'       => $request->estado_aud,
                'alcance'      => $request->alcance,
                'hallazgos'    => $request->hallazgos,
            ]);
        });

        return redirect()->back()->with('success', 'Auditoría registrada correctamente');
    }

    /**
     * Mostrar una auditoría
     */
    public function show($id)
    {
        $auditoria = Auditoria::with('auditor')->find($id);

        if (!$auditoria) {
            return redirect()->route('auditorias.index')
                             ->with('error', 'Auditoría no encontrada');
        }

        return view('auditorias.show', compact('auditoria'));
    }
}
