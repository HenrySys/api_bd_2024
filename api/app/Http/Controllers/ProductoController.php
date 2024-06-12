<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto; 

class ProductoController extends Controller
{
    public function index()
    {
        $data = Producto::all(); //obtenemos todos los registros de la tabla vehiculo y los guardamos en la variable data
        $response = array(
            "status" => 200, //estado de la respuesta
            "message" => "Todos los registros de los Productos", //mensaje de la respuesta
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
            if(is_array($data)){
            $data = array_map('trim', $data); //eliminamos los espacios en blanco de los datos
            $precio = floatval($data['precio']);
            $rules = [
                'nombre' => 'required',
                'descripcion' => 'required',
                'precio' => 'required'
            ];
            $isValid = \validator($data, $rules); //validamos los datos con las reglas definidas en la variable rules
            if (!$isValid->fails()) {
                $product = new Producto(); //creamos un objeto de la clase Vehiculo
                $product->nombre = $data['nombre']; //asignamos el valor del campo placa del objeto vehiculo con el valor del campo marca del array data
                $product->descripcion = $data['descripcion']; //asignamos el valor del campo marca del objeto vehiculo con el valor del campo marca del array data
                $product->precio = $precio; //asignamos el valor del campo modelo del objeto vehiculo con el valor del campo modelo del array data
                $product->save(); //guardamos el objeto reserva en la base de datos
                $response = array(
                    "status" => 201, //estado de la respuesta
                    "message" => "Producto creada", //mensaje de la respuesta
                    "producto" => $product //datos de la respuesta que en este caso es el objeto cliente
                );
            } else {
                $response = array(
                    "status" => 406, //estado de la respuesta
                    "message" => "Datos invalidos", //mensaje de la respuesta
                    "errors" => $isValid->errors() //datos de la respuesta que en este caso son los errores de validacion
                );
            }
        }else{
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
        $data = Producto::find($id); //buscamos un registro de la tabla cliente con el id recibido y lo guardamos en la variable data
        if (is_object($data)) { //verificamos si la variable data es un objeto
            $response = array(
                "status" => 200, //estado de la respuesta
                "message" => "Datos del Producto", //mensaje de la respuesta
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
        if (isset($id) ) { //isset = verifica si una variable esta definida, en este caso si el id esta definido

            

            $delete = Producto::where('id', $id)->delete(); //buscamos un registro de la tabla category con el id recibido y lo eliminamos y guardamos el resultado en la variable delete
            //$delete=Category::destroy($id); //otra forma de eliminar un registro
            if ($delete) { //verificamos si la variable delete es verdadera
                $response = array(
                    "status" => 200, //estado de la respuesta
                    "message" => "Producto eliminada" //mensaje de la respuesta
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
            $data = array_map('trim', $data); //eliminamos los espacios en blanco de los datos
            $precio = floatval($data['precio']);
            $rules = [
                'nombre' => 'required',
                'descripcion' => 'required',
                'precio' => 'required'
            ];
            $isValid = \validator($data, $rules); //validamos los datos con las reglas definidas en la variable rules
            if (!$isValid->fails()) {
                $product = Producto::find($id); // Busca la servicio que deseas actualizar

                if (!$product) {
                    // Si no se encuentra la compra, retorna un mensaje de error
                    $response = [
                        "status" => 404,
                        "message" => "reserva no encontrada"
                    ];
                    return response()->json($response, 404);
                }

                // Actualiza los campos del servicio con los nuevos datos
                $product->nombre = $data['nombre']; //asignamos el valor del campo placa del objeto vehiculo con el valor del campo marca del array data
                $product->descripcion = $data['descripcion']; //asignamos el valor del campo marca del objeto vehiculo con el valor del campo marca del array data
                $product->precio = $precio;
                // Guarda los cambios en la base de datos
                $product->save();

                // Retorna una respuesta exitosa
                $response = [
                    "status" => 200,
                    "message" => "Compra actualizada correctamente",
                    "producto" => $product
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
                "message" => "No se encontro el objeto data" //mensaje de la respuesta
            );
        }
        return response()->json($response, $response['status']); //retornamos la respuesta en formato json con el estado de la respuesta
    }

    public function uploadImage(Request $request)
    {
        $isValid = \Validator::make(
            $request->all(),
            ['file0' => 'required|image|mimes:jpg,jpeg,png,gif,svg']
        );
        if (!$isValid->fails()) {
            $image = $request->file('file0');
            $filename = \Str::uuid() . '.' . $image->getClientOriginalExtension();
            \Storage::disk('products')->put($filename, \File::get($image));
            $response = array(
                "status" => 201,
                "message" => "Imagen guardada correctamente",
                "filename" => $filename,
            );
        } else {
            $response = array(
                "status" => 406,
                "message" => "Error: no se encontro la imagen",
                "errors" => $isValid->errors()
            );
        }
        return response()->json($response, $response['status']);
    }

    public function getImage($filename)
    {
        if (isset($filename)) {
            $exist = \Storage::disk('products')->exists($filename);
            if ($exist) {
                $file = \Storage::disk('products')->get($filename);
                return new Response($file, 200);
            } else {
                $response = array(
                    "status" => 404,
                    "message" => "No Existe la imagen"
                );
            }
        } else {
            $response = array(
                "status" => 406,
                "message" => "No se definio el nombre de la imagen"
            );
        }
        return response()->json($response);
    }

    public function updateImage(Request $request, string $filename)
    {
        $isValid = \Validator::make(
            $request->all(),
            ['file0' => 'required|image|mimes:jpg,jpeg,png,gif,svg']
        );
        if (!$isValid->fails()) {
            $image = $request->file('file0');
            $filename = \Str::uuid() . '.' . $image->getClientOriginalExtension();
            \Storage::disk('products')->put($filename, \File::get($image));
            $response = array(
                "status" => 201,
                "message" => "Imagen guardada correctamente",
                "filename" => $filename,
            );
        } else {
            $response = array(
                "status" => 406,
                "message" => "Error: no se encontro la imagen",
                "errors" => $isValid->errors()
            );
        }
        return response()->json($response, $response['status']);
    }

    public function destroyImage($filename)
    {
        if (isset($filename)) {
            $exist = \Storage::disk('products')->exists($filename);
            if ($exist) {
                \Storage::disk('products')->delete($filename);
                $response = array(
                    "status" => 201,
                    "message" => "Imagen eliminada correctamente"
                );
            } else {
                $response = array(
                    "status" => 404,
                    "message" => "No Existe la imagen"
                );
            }
        } else {
            $response = array(
                "status" => 406,
                "message" => "No se definio el nombre de la imagen"
            );
        }
        return response()->json($response);
       
    }
}
