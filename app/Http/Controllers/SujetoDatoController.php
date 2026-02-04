<?php

namespace App\Http\Controllers;

use App\Models\SujetoDato;
use Illuminate\Http\Request;

class SujetoDatoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sujetos = SujetoDato::orderBy('id')->get();
        return view('index', compact('sujetos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'cedula' => 'required|unique:sujetos_datos,cedula',
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'tipo' => 'required|string|max:50',
            'email' => 'required|email|unique:sujetos_datos,email',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:200',
            'ciudad' => 'nullable|string|max:100',
        ], [
            'cedula.unique' => 'La cédula ya existe en el sistema',
            'email.unique' => 'El email ya existe en el sistema'
        ]);

        SujetoDato::create([
            'cedula' => $request->cedula,
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'ciudad' => $request->ciudad,
            'tipo' => $request->tipo,
        ]);

        return redirect('/')->with('success', 'Sujeto registrado correctamente');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $sujeto = SujetoDato::findOrFail($id);

        $request->validate([
            'cedula' => "required|unique:sujetos_datos,cedula,$id",
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'tipo' => 'required|string|max:50',
            'email' => "required|email|unique:sujetos_datos,email,$id",
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:200',
            'ciudad' => 'nullable|string|max:100',
        ]);

        $sujeto->update([
            'cedula' => $request->cedula,
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'ciudad' => $request->ciudad,
            'tipo' => $request->tipo,
        ]);

        return redirect()->back()->with('success', 'Sujeto actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        SujetoDato::findOrFail($id)->delete();
        return redirect('/')->with('success', 'Sujeto eliminado correctamente');
    }

    /**
     * Verificar si la cédula existe.
     */
    public function verificarCedula(Request $request)
    {
        $cedula = $request->cedula;
        $id = $request->sujeto_id;

        $existe = SujetoDato::where('cedula', $cedula)
            ->when($id, fn($query) => $query->where('id', '!=', $id))
            ->exists();

        return response()->json(!$existe);
    }

    /**
     * Verificar si el email existe.
     */
    public function verificarEmail(Request $request)
{
    $email = $request->email;
    $id = $request->sujeto_id;

    $existe = SujetoDato::where('email', $email)
        ->when($id, function ($query) use ($id) {
            $query->where('id', '!=', $id);
        })
        ->exists();

    return response()->json(!$existe);
}

}
