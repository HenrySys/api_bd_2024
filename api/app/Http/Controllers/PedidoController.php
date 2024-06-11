<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;

class PedidoController extends Controller
{
    public function index()
    {
        $data = Pedido::all(); //obtenemos todos los registros de la tabla vehiculo y los guardamos en la variable data
        $response = array(
            "status" => 200, //estado de la respuesta
            "message" => "Todos los registros de los Pedidos", //mensaje de la respuesta
            "data" => $data //datos de la respuesta que en este caso son todos los registros de la tabla vehiculo
        );
        return response()->json($response, 200); //retornamos la respuesta en formato json con un estado 200
    }
    /**
     * Metodo POST para crear un registro
     */

    public function store(Request $request)
    { //recibimos un request que contendra los datos a guardar
        $data_imput = $request->input('data', null); //obtenemos los datos del request en formato json  y los guardamos en la variable data_input si no hay datos se guarda un null 
        if ($data_imput) {
            $data = json_decode($data_imput, true); //decodificamos los datos en formato json y los guardamos en la variable data
            if (is_array($data)) {
                $data = array_map('trim', $data); //eliminamos los espacios en blanco de los datos
                $idMesa = $data['idMesa'];
                $rules = [
                    'fecha' => 'required',
                    'hora' => 'required',
                    'estado' => 'required'
                ];
                $isValid = \validator($data, $rules); //validamos los datos con las reglas definidas en la variable rules
                if (!$isValid->fails()) {
                    $pedido = new Pedido(); //creamos un objeto de la clase Vehiculo
                    $pedido->fecha = $data['fecha']; //asignamos el valor del campo placa del objeto vehiculo con el valor del campo marca del array data
                    $pedido->hora = $data['hora']; //asignamos el valor del campo marca del objeto vehiculo con el valor del campo marca del array data
                    $pedido->estado = $data['estado'];
                    $pedido->idMesa = $idMesa;
                    $pedido->save(); //guardamos el objeto reserva en la base de datos
                    $response = array(
                        "status" => 201, //estado de la respuesta
                        "message" => "Pedido creado", //mensaje de la respuesta
                        "pedido" => $pedido //datos de la respuesta que en este caso es el objeto cliente
                    );
                } else {
                    $response = array(
                        "status" => 406, //estado de la respuesta
                        "message" => "Datos invalidos", //mensaje de la respuesta
                        "errors" => $isValid->errors() //datos de la respuesta que en este caso son los errores de validacion
                    );
                }
            } else {
                $response = array(
                    "status" => 400, //estado de la respuesta
                    "message" => "Error en el formato de los datos JSON", //mensaje de la respuesta
                );
            }
        } else {
            $response = array(
                "status" => 400, //estado de la respuesta
                "message" => "No se encontro el objeto data" //mensaje de la respuesta
            );
        }
        return response()->json($response, $response['status']); //retornamos la respuesta en formato json con el estado de la respuesta

    }

    public function show($id)
    {
        $data = Pedido::find($id); //buscamos un registro de la tabla cliente con el id recibido y lo guardamos en la variable data
        if (is_object($data)) { //verificamos si la variable data es un objeto
            $data = $data->load('mesa');
            $response = array(
                "status" => 200, //estado de la respuesta
                "message" => "Datos de Reserva", //mensaje de la respuesta
                "category" => $data //datos de la respuesta que en este caso es el objeto data
            );
        } else {
            $response = array(
                "status" => 404, //estado de la respuesta
                "message" => "Recurso no encontrado" //mensaje de la respuesta
            );
        }
        return response()->json($response, $response['status']); //retornamos la respuesta en formato json con el estado de la respuesta
    }

    public function destroy($id)
    {
        if (isset($id)) { //isset = verifica si una variable esta definida, en este caso si el id esta definido

            $reserva = Pedido::where('id', $id)->first(); // Busca la categoría por ID y la guarda en la variable category
            if (!$reserva) { //verifica si la variable category es falsa
                $response = [
                    "status" => 404,
                    "message" => "Reserva no encontrada"
                ];
                return response()->json($response, $response['status']);
            }

            // Verifica si hay posts relacionados
            if ($reserva->mesa()->count() > 0) { //verifica si la cantidad de clientes relacionados con la categoria es mayor a 0 
                $response = [
                    "status" => 400,
                    "message" => "No se puede eliminar la reserva, tiene mesas relacionadas"
                ];
                return response()->json($response, $response['status']);
            }

        

            $delete = Pedido::where('id', $id)->delete(); //buscamos un registro de la tabla category con el id recibido y lo eliminamos y guardamos el resultado en la variable delete
            //$delete=Category::destroy($id); //otra forma de eliminar un registro
            if ($delete) { //verificamos si la variable delete es verdadera
                $response = array(
                    "status" => 200, //estado de la respuesta
                    "message" => "Pedido eliminada" //mensaje de la respuesta
                );
                return response()->json($response, $response['status']); //retornamos la respuesta en formato json con el estado de la respuesta



            } else {
                $response = array(
                    "status" => 400, //estado de la respuesta
                    "message" => "No se pudo eliminar el recurso, compruebe que exista" //mensaje de la respuesta
                );
            }
        } else {
            $response = array(
                "status" => 406, //estado de la respuesta
                "message" => "Falta el identificador del recurso a eliminar" //mensaje de la respuesta
            );
        }
        return response()->json($response, $response['status']); //retornamos la respuesta en formato json con el estado de la respuesta
    }

    public function update(Request $request, $id)
    {
        $data_imput = $request->input('data', null); //obtenemos los datos del request en formato json  y los guardamos en la variable data_input si no hay datos se guarda un null
        if ($data_imput) {
            $data = json_decode($data_imput, true); //decodificamos los datos en formato json y los guardamos en la variable data
            if (is_array($data)) {
                $data = array_map('trim', $data); //eliminamos los espacios en blanco de los datos
                $idMesa = $data['idMesa'];
                $rules = [
                    'fecha' => 'required',
                    'hora' => 'required',
                    'estado' => 'required'
                ];
                $isValid = \validator($data, $rules); //validamos los datos con las reglas definidas en la variable rules
                if (!$isValid->fails()) {
                    $pedido = Pedido::find($id); // Busca el servicio que deseas actualizar

                    if (!$pedido) {
                        // Si no se encuentra el servicio, retorna un mensaje de error
                        $response = [
                            "status" => 404,
                            "message" => "Pedido no encontrado"
                        ];
                        return response()->json($response, 404);
                    }

                    // Actualiza los campos del servicio con los nuevos datos
                    $pedido = new Pedido(); //creamos un objeto de la clase Vehiculo
                    $pedido->fecha = $data['fecha']; //asignamos el valor del campo placa del objeto vehiculo con el valor del campo marca del array data
                    $pedido->hora = $data['hora']; //asignamos el valor del campo marca del objeto vehiculo con el valor del campo marca del array data
                    $pedido->estado = $data['estado'];
                    $pedido->idMesa = $idMesa;
                    $pedido->save(); //

                    // Retorna una respuesta exitosa
                    $response = [
                        "status" => 200,
                        "message" => "Servicio actualizado correctamente",
                        "pedido" => $pedido
                    ];
                    return response()->json($response, 200);
                } else {
                    $response = array(
                        "status" => 406, //estado de la respuesta
                        "message" => "Datos invalidos", //mensaje de la respuesta
                        "errors" => $isValid->errors() //datos de la respuesta que en este caso son los errores de validacion
                    );
                }
            } else {
                $response = array(
                    "status" => 400, //estado de la respuesta
                    "message" => "Error en el formato de los datos JSON", //mensaje de la respuesta
                );
            }
        } else {
            $response = array(
                "status" => 400, //estado de la respuesta
                "message" => "No se encontro el objeto data" //mensaje de la respuesta
            );
        }
        return response()->json($response, $response['status']); //retornamos la respuesta en formato json con el estado de la respuesta
    }
    //
}
