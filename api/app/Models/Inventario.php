<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    use HasFactory;
    protected $table = 'inventario';//definimos el nombre de la tabla
    protected $fillable = ['stock','idProducto'];//definimos los campos que se pueden llenar

  
    public function productos(){
        return $this->belongsTo('App\Models\Producto','idProducto');//relacion de muchos a uno con la tabla cliente
    }

}
