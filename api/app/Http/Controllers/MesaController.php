<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mesa;

class MesaController extends Controller
{
    public function index()
    {
        $data = Mesa::all(); //obtenemos todos los registros de la tabla vehiculo y los guardamos en la variable data
        $response = array(
            "status" => 200, //estado de la respuesta
            "message" => "Todos los registros de las mesas", //mensaje de la respuesta
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
                $rules = [
                    'estado' => 'required',
                    'caducidad' => 'required'
                ];
                $isValid = \validator($data, $rules); //validamos los datos con las reglas definidas en la variable rules
                if (!$isValid->fails()) {
                    $mesa = new Mesa(); //creamos un objeto de la clase Vehiculo
                    $mesa->estado = $data['estado']; //asignamos el valor del campo placa del objeto vehiculo con el valor del campo marca del array data
                    $mesa->caducidad = $data['caducidad']; //asignamos el valor del campo marca del objeto vehiculo con el valor del campo marca del array data
                    $mesa->save(); //guardamos el objeto reserva en la base de datos
                    $response = array(
                        "status" => 201, //estado de la respuesta
                        "message" => "mesa creada", //mensaje de la respuesta
                        "mesa" => $mesa //datos de la respuesta que en este caso es el objeto cliente
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
        $data = Mesa::find($id); //buscamos un registro de la tabla cliente con el id recibido y lo guardamos en la variable data
        if (is_object($data)) { //verificamos si la variable data es un objeto
            $data = $data->load('factura');
            $response = array(
                "status" => 200, //estado de la respuesta
                "message" => "Datos de mesa", //mensaje de la respuesta
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

            $mesa = Mesa::where('id', $id)->first(); // Busca la categoría por ID y la guarda en la variable category
            if (!$mesa) { //verifica si la variable category es falsa
                $response = [
                    "status" => 404,
                    "message" => "mesa no encontrada"
                ];
                return response()->json($response, $response['status']);
            }

            // Verifica si hay posts relacionados
            if ($mesa->factura()->count() > 0) { //verifica si la cantidad de clientes relacionados con la categoria es mayor a 0 
                $response = [
                    "status" => 400,
                    "message" => "No se puede eliminar la mesa, tiene facturas relacionadas"
                ];
                return response()->json($response, $response['status']);
            }

        

            $delete = Mesa::where('id', $id)->delete(); //buscamos un registro de la tabla category con el id recibido y lo eliminamos y guardamos el resultado en la variable delete
            //$delete=Category::destroy($id); //otra forma de eliminar un registro
            if ($delete) { //verificamos si la variable delete es verdadera
                $response = array(
                    "status" => 200, //estado de la respuesta
                    "message" => "mesa eliminada" //mensaje de la respuesta
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
                $rules = [
                    'estado' => 'required',
                    'caducidad' => 'required',
                    
                ];
                $isValid = \validator($data, $rules); //validamos los datos con las reglas definidas en la variable rules
                if (!$isValid->fails()) {
                    $mesa = Mesa::find($id); // Busca el servicio que deseas actualizar

                    if (!$mesa) {
                        // Si no se encuentra el servicio, retorna un mensaje de error
                        $response = [
                            "status" => 404,
                            "message" => "mesa no encontrado"
                        ];
                        return response()->json($response, 404);
                    }

                    // Actualiza los campos del servicio con los nuevos datos
                    $mesa = new Mesa(); //creamos un objeto de la clase Vehiculo
                    $mesa->estado = $data['estado']; //asignamos el valor del campo placa del objeto vehiculo con el valor del campo marca del array data
                    $mesa->caducidad = $data['caducidad']; //asignamos el valor del campo marca del objeto vehiculo con el valor del campo marca del array data
                
                    $mesa->save();

                    // Retorna una respuesta exitosa
                    $response = [
                        "status" => 200,
                        "message" => "mesa actualizado correctamente",
                        "mesa" => $mesa
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
}
