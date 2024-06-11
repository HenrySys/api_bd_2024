<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;
    protected $table = 'reserva';//definimos el nombre de la tabla
    protected $fillable = ['fecha','hora','estado','idCliente','idMesa'];//definimos los campos que se pueden llenar

    public function cliente(){
        return $this->belongsTo('App\Models\Cliente','idCliente');//relacion de muchos a uno con la tabla cliente
    }

    public function mesa(){
        return $this->belongsTo('App\Models\Mesa','idMesa');//relacion de muchos a uno con la tabla mesa
    }
    
}
