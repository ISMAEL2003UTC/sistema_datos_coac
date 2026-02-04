<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AuditoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    // Solo usuarios con rol auditor y estado activo
    $usuarios = Usuario::where('rol', 'auditor')
                        ->where('estado', 'activo')
                        ->orderBy('nombre', 'asc')
                        ->get();

    // Traer auditorías si las necesitas en la vista
    $auditorias = Auditoria::orderBy('created_at', 'desc')->get();

    // Pasamos ambas variables a la vista
    return view('tu_vista', compact('auditorias', 'usuarios'));
}



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validación de datos
            $validated = $request->validate([
                'tipo_aud' => 'required|in:interna,externa',
                'auditor_id' => 'required|exists:usuarios,id',
                'fecha_inicio' => 'required|date|date_format:Y-m-d',
                'hora_inicio' => 'required|date_format:H:i',
                'fecha_fin' => 'required|date|date_format:Y-m-d|after:fecha_inicio',
                'hora_fin' => 'required|date_format:H:i',
                'estado_aud' => 'required|in:planificada,proceso,completada,revisada,cancelada',
                'alcance' => 'nullable|string|max:1000',
                'hallazgos' => 'nullable|string|max:2000',
            ], [
                'tipo_aud.required' => 'El tipo de auditoría es obligatorio.',
                'tipo_aud.in' => 'El tipo de auditoría seleccionado no es válido.',
                'auditor_id.required' => 'El auditor responsable es obligatorio.',
                'auditor_id.exists' => 'El auditor seleccionado no existe en el sistema.',
                'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
                'fecha_inicio.date' => 'La fecha de inicio no tiene un formato válido.',
                'fecha_inicio.date_format' => 'El formato de fecha de inicio es incorrecto.',
                'hora_inicio.required' => 'La hora de inicio es obligatoria.',
                'hora_inicio.date_format' => 'El formato de hora de inicio es incorrecto (HH:MM).',
                'fecha_fin.required' => 'La fecha de finalización es obligatoria.',
                'fecha_fin.date' => 'La fecha de finalización no tiene un formato válido.',
                'fecha_fin.date_format' => 'El formato de fecha de finalización es incorrecto.',
                'fecha_fin.after' => 'La fecha de finalización debe ser posterior a la fecha de inicio.',
                'hora_fin.required' => 'La hora de finalización es obligatoria.',
                'hora_fin.date_format' => 'El formato de hora de finalización es incorrecto (HH:MM).',
                'estado_aud.required' => 'El estado de la auditoría es obligatorio.',
                'estado_aud.in' => 'El estado seleccionado no es válido.',
                'alcance.max' => 'El alcance no debe exceder los 1000 caracteres.',
                'hallazgos.max' => 'Los hallazgos no deben exceder los 2000 caracteres.',
            ]);
            
            // Validar que el usuario tenga rol de auditor
            $auditor = Usuario::find($validated['auditor_id']);
            if ($auditor->rol !== 'auditor') {
                throw ValidationException::withMessages([
                    'auditor_id' => 'El usuario seleccionado no tiene rol de auditor.',
                ]);
            }
            
            DB::beginTransaction();
            
            try {
                // Generar código único para la auditoría
                $codigo = $this->generarCodigoAuditoria($validated['tipo_aud']);
                
                // Crear la auditoría
                $auditoria = Auditoria::create([
                    'codigo' => $codigo,
                    'tipo' => $validated['tipo_aud'],
                    'auditor_id' => $validated['auditor_id'],
                    'fecha_inicio' => $validated['fecha_inicio'],
                    'hora_inicio' => $validated['hora_inicio'],
                    'fecha_fin' => $validated['fecha_fin'],
                    'hora_fin' => $validated['hora_fin'],
                    'estado' => $validated['estado_aud'],
                    'alcance' => $validated['alcance'] ?? null,
                    'hallazgos' => $validated['hallazgos'] ?? null,
                    'creado_por' => auth()->id(),
                ]);
                
                DB::commit();
                
                return redirect()->route('auditorias.index')
                    ->with('success', 'Auditoría registrada exitosamente. Código: ' . $codigo);
                    
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
            
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Por favor corrija los errores en el formulario.');
                
        } catch (\Exception $e) {
            Log::error('Error al crear auditoría: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al registrar la auditoría.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            // Cargar auditoría con relaciones
            $auditoria = Auditoria::with(['usuarioAuditor', 'creadoPor'])
                ->findOrFail($id);
            
            return view('auditorias.show', compact('auditoria'));
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('auditorias.index')
                ->with('error', 'La auditoría solicitada no existe.');
                
        } catch (\Exception $e) {
            Log::error('Error al mostrar auditoría: ' . $e->getMessage());
            return redirect()->route('auditorias.index')
                ->with('error', 'Error al cargar los detalles de la auditoría.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $auditoria = Auditoria::findOrFail($id);
            
            // Validación de datos
            $validated = $request->validate([
                'estado' => 'required|in:planificada,proceso,completada,revisada,cancelada',
                'alcance' => 'nullable|string|max:1000',
                'hallazgos' => 'nullable|string|max:2000',
                'observaciones' => 'nullable|string|max:2000',
                'fecha_fin' => 'nullable|date|date_format:Y-m-d|after:fecha_inicio',
                'hora_fin' => 'nullable|date_format:H:i',
            ], [
                'estado.required' => 'El estado es obligatorio.',
                'estado.in' => 'El estado seleccionado no es válido.',
                'alcance.max' => 'El alcance no debe exceder los 1000 caracteres.',
                'hallazgos.max' => 'Los hallazgos no deben exceder los 2000 caracteres.',
                'observaciones.max' => 'Las observaciones no deben exceder los 2000 caracteres.',
                'fecha_fin.date' => 'La fecha de finalización no tiene un formato válido.',
                'fecha_fin.date_format' => 'El formato de fecha de finalización es incorrecto.',
                'fecha_fin.after' => 'La fecha de finalización debe ser posterior a la fecha de inicio.',
                'hora_fin.date_format' => 'El formato de hora de finalización es incorrecto.',
            ]);
            
            DB::beginTransaction();
            
            try {
                // Actualizar auditoría
                $auditoria->update([
                    'estado' => $validated['estado'],
                    'alcance' => $validated['alcance'] ?? $auditoria->alcance,
                    'hallazgos' => $validated['hallazgos'] ?? $auditoria->hallazgos,
                    'observaciones' => $validated['observaciones'] ?? $auditoria->observaciones,
                    'fecha_fin' => $validated['fecha_fin'] ?? $auditoria->fecha_fin,
                    'hora_fin' => $validated['hora_fin'] ?? $auditoria->hora_fin,
                    'actualizado_por' => auth()->id(),
                ]);
                
                DB::commit();
                
                return redirect()->route('auditorias.show', $auditoria->id)
                    ->with('success', 'Auditoría actualizada exitosamente.');
                    
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
            
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Por favor corrija los errores en el formulario.');
                
        } catch (\Exception $e) {
            Log::error('Error al actualizar auditoría: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error al actualizar la auditoría.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $auditoria = Auditoria::findOrFail($id);
            
            // Verificar si se puede eliminar (solo si está planificada)
            if ($auditoria->estado !== 'planificada') {
                return redirect()->back()
                    ->with('error', 'Solo se pueden eliminar auditorías en estado "Planificada".');
            }
            
            DB::beginTransaction();
            
            try {
                $auditoria->delete();
                
                DB::commit();
                
                return redirect()->route('auditorias.index')
                    ->with('success', 'Auditoría eliminada exitosamente.');
                    
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
            
        } catch (\Exception $e) {
            Log::error('Error al eliminar auditoría: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error al eliminar la auditoría.');
        }
    }


    private function generarCodigoAuditoria($tipo)
    {
        $prefijo = strtoupper(substr($tipo, 0, 1)); // I para interna, E para externa
        $anio = date('Y');
        $mes = date('m');
        
        $contador = Auditoria::whereYear('created_at', $anio)
            ->whereMonth('created_at', $mes)
            ->count() + 1;
        
        return sprintf('AUD-%s%s%s-%04d', $prefijo, $anio, $mes, $contador);
    }
}