<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'apellido',
        'cedula',
        'provincia',
        'ciudad',
        'direccion',
        'email',
        'password',
        'rol',
        'estado',
        'email_verificado',
        'email_verificacion_token'
    ];

    protected $hidden = [
        'password'
    ];

    /**
     * Retorna el nombre del rol en texto legible
     */
    public function getRolTextoAttribute()
    {
        return match ($this->rol) {
            'admin'                   => 'Administrador',
            'dpo'                     => 'Oficial de Protección de Datos',
            'auditor'                 => 'Auditor',
            'operador'                => 'Operador',
            'auditor_interno'         => 'Auditor Interno',
            'gestor_consentimientos'  => 'Gestor de Consentimientos',
            'gestor_incidentes'       => 'Gestor de Incidentes',
            'titular'                 => 'Titular',
            default                   => ucfirst($this->rol),
        };
    }
}