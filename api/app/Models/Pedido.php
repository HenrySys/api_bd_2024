<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;
    protected $table = 'pedido';//definimos el nombre de la tabla
    protected $fillable = ['fecha','hora','estado','idMesa'];//definimos los campos que se pueden llenar

  
    public function mesa(){
        return $this->belongsTo('App\Models\Mesa','idMesa');//relacion de muchos a uno con la tabla mesa
    }

    public function pedidoProducto(){
        return $this->hasMany('App\Models\PedidoProducto','idPedido');//relacion de uno a muchos con la tabla pedidoProducto
    }

    public function cliente(){
        return $this->belongsTo('App\Models\Producto','idProducto');//relacion de muchos a uno con la tabla cliente
    }
}
