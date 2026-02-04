<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\Usuario; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuditoriaController extends Controller
{
    // Mostrar todas las auditorías
    public function index()
    {
        $auditorias = Auditoria::orderBy('id', 'desc')->paginate(10);
        return view('auditorias.index', compact('auditorias'));
    }

    // Mostrar una auditoría específica
    public function show($id)
    {
        $auditoria = Auditoria::find($id);

        if (!$auditoria) {
            return redirect()->route('auditorias.index')
                ->with('error', 'Auditoría no encontrada');
        }

        return view('auditorias.show', compact('auditoria'));
    }

    // Formulario de creación: enviar lista de auditores
    public function create()
    {
        $auditores = Usuario::where('rol', 'auditor')  // Solo usuarios con rol auditor
                            ->where('estado', 'activo') // Solo activos
                            ->get();

        return view('auditorias.create', compact('auditores'));
    }

    // Guardar nueva auditoría
    public function store(Request $request)
    {
        $request->validate([
            'tipo_aud'     => 'required',
            'auditor_id'   => 'required|exists:usuarios,id', // <-- validación del select
            'fecha_inicio' => 'required|date',
            'fecha_fin'    => 'nullable|date|after:fecha_inicio',
            'estado_aud'   => 'required',
            'alcance'      => 'nullable|string',
            'hallazgos'    => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {

            // Obtener último código y calcular nuevo
            $ultimoCodigo = Auditoria::lockForUpdate()->max('codigo');
            $nuevoCodigo = $ultimoCodigo ? $ultimoCodigo + 1 : 1;

            // Crear auditoría
            Auditoria::create([
                'codigo'       => $nuevoCodigo,
                'tipo'         => $request->tipo_aud,
                'auditor_id'   => $request->auditor_id, // <-- guardamos el ID del auditor
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin'    => $request->fecha_fin,
                'estado'       => $request->estado_aud,
                'alcance'      => $request->alcance,
                'hallazgos'    => $request->hallazgos,
            ]);
        });

        return redirect()->back()->with('success', 'Auditoría registrada correctamente');
    }
}
