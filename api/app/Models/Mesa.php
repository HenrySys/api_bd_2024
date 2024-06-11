<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mesa extends Model
{
    use HasFactory;
    protected $table = 'mesa';//definimos el nombre de la tabla
    protected $fillable = ['estado','caducidad',];//definimos los campos que se pueden llenar

    public function factura(){
        return $this->belongsTo('App\Models\Factura','idFactura');//relacion de muchos a uno con la tabla factura
    }

}
