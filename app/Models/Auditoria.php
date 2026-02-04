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
     * Relación con el auditor
     */
    public function auditor()
    {
        return $this->belongsTo(\App\Models\Usuario::class, 'auditor_id');
    }

    /**
     * Boot method para generar código automático
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($auditoria) {
            if (empty($auditoria->codigo)) {
                $ultimo = self::orderBy('id', 'desc')->first();

                if ($ultimo && preg_match('/AUD-(\d+)/', $ultimo->codigo, $matches)) {
                    $numero = intval($matches[1]) + 1;
                } else {
                    $numero = 1;
                }

                $auditoria->codigo = 'AUD-' . str_pad($numero, 3, '0', STR_PAD_LEFT);
            }
        });
    }
}
