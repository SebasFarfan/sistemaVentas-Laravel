<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table = 'persona';
    protected $primaryKey = 'idPersona';
    public $timestamps=false;
    protected $fillable = [
        'tipo_persona',
        'nombre',
        'tipo_documento',
        'nro_documento',
        'direccion',
        'telefono',
        'email'
    ];

    protected $guarded = [];
}
