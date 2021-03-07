<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    protected $table = 'detalle_venta';
    protected $primaryKey = 'idDetalle_venta';
    public $timestamps=false;
    protected $fillable = [
        'idVenta',
        'idArticulo',
        'cantidad',
        'precio_venta',
        'descuento'
    ];

    protected $guarded =[];
}
