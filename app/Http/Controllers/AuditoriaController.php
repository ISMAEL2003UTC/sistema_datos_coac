<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
use App\Models\Auditoria;
=======
>>>>>>> 05746bfd95eace9d7017846e0319a85396f8541b
use Illuminate\Http\Request;

class AuditoriaController extends Controller
{
<<<<<<< HEAD
    // LISTAR auditorías
    public function index()
    {
        $auditorias = Auditoria::orderBy('id', 'desc')->get();
        return view('auditorias.index', compact('auditorias'));
    }

    // GUARDAR auditoría
    public function store(Request $request)
    {
        Auditoria::create([
            'codigo'        => $request->codigo,
            'tipo'          => $request->tipo,
            'auditor'       => $request->auditor,
            'fecha_inicio'  => $request->fecha_inicio,
            'fecha_fin'     => $request->fecha_fin,
            'estado'        => $request->estado,
            'alcance'       => $request->alcance,
            'hallazgos'     => $request->hallazgos,
        ]);

        return redirect()->back()->with('success', 'Auditoría registrada correctamente');
    }

    // VER auditoría
    public function ver($id)
    {
        $auditoria = Auditoria::findOrFail($id);
        return view('auditorias.ver', compact('auditoria'));
=======
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        //
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
>>>>>>> 05746bfd95eace9d7017846e0319a85396f8541b
    }
}
