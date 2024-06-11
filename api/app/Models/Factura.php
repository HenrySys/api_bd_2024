<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    use HasFactory;
    protected $table = 'factura';//definimos el nombre de la tabla
    protected $fillable = ['fecha','hora','total','idReserva'];//definimos los campos que se pueden llenar

  
    public function reserva(){
        return $this->belongsTo('App\Models\Reserva','idReserva');//relacion de muchos a uno con la tabla reserva
    }
   
    public function detalleFactura(){
        return $this->hasMany('App\Models\DetalleFactura','idFactura');//relacion de uno a muchos con la tabla detalleFactura
    }
}
