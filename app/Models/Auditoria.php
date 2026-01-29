<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auditoria extends Model
{
    protected $table = 'auditorias';

    protected $fillable = [
        'codigo',  
        'tipo',
        'auditor',
        'fecha_inicio',
        'fecha_fin',
        'estado',
        'alcance',
        'hallazgos'
    ];

    /**
     * Boot method para generar código automático
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($auditoria) {
            // Solo generar código si no se proporcionó uno
            if (empty($auditoria->codigo)) {
                $ultimo = self::orderBy('id', 'desc')->first();
                
                if ($ultimo && preg_match('/AUD-(\d+)/', $ultimo->codigo, $matches)) {
                    // Extraer el número del último código y sumar 1
                    $numero = intval($matches[1]) + 1;
                } else {
                    // Si no hay auditorías o el formato no coincide, empezar desde 1
                    $numero = 1;
                }
                
                $auditoria->codigo = 'AUD-' . str_pad($numero, 3, '0', STR_PAD_LEFT);
            }
        });
    }
}