<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\Usuario; // Importar modelo Usuario
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuditoriaController extends Controller
{
    public function index()
    {
        // Traemos las auditorías
        $auditorias = Auditoria::orderBy('id', 'desc')->paginate(10);

        // Traemos solo los usuarios que son auditores y están activos
        $auditores = Usuario::where('rol', 'auditor')
                            ->where('estado', 'activo')
                            ->get();

        return view('auditorias.index', compact('auditorias', 'auditores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo_aud'   => 'required',
            'auditor_id' => 'required|exists:usuarios,id', // Validación del auditor
            'fecha_inicio' => 'required|date',
            'fecha_fin'    => 'nullable|date',
            'estado_aud'   => 'required',
            'alcance'      => 'nullable|string',
            'hallazgos'    => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {
            $auditor = Usuario::findOrFail($request->auditor_id);

            // Guardamos el nombre completo del auditor en la auditoría
            Auditoria::create([
                'codigo'       => 'AUD-' . str_pad(Auditoria::max('id') + 1, 3, '0', STR_PAD_LEFT),
                'tipo'         => $request->tipo_aud,
                'auditor'      => $auditor->nombre . ' ' . $auditor->apellido,
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
