<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Illuminate\Http\Response;
use App\Models\User;
use App\Helpers\JwtAuth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        $response = [
            'status' => 200,
            'data' => $users
        ];
        return response()->json($response, $response['status']);
    }

    public function show($id)
    {
        $user = User::find($id);
        if ($user) {
            $response = [
                'status' => 200,
                'user' => $user
            ];
        } else {
            $response = [
                'status' => 404,
                'message' => 'User not found'
            ];
        }
        return response()->json($response, $response['status']);
    }

    public function store(Request $request)
    {
        $data_input = $request->input('data', null);
        if ($data_input) {
            $data = json_decode($data_input, true);
            $data = array_map('trim', $data);
            $rules = [
                'name' => 'required|alpha',
                'email' => 'required|email|unique:users',
                'password' => 'required',
                'rol' => 'required',
                'phone' => 'required',
                'lastName' => 'required',
                'address' => 'required',
                'image' => 'required|image|mimes:jpg,jpeg,png,gif,svg'
            ];

            $isValid = \Validator::make($data, $rules);
            if (!$isValid->fails()) {
                $user = new User();
                $user->name = $data['name'];
                $user->email = $data['email'];
                $user->password = hash('sha256', $data['password']);
                $user->rol = $data['rol'];
                $user->phone = $data['phone'];
                $user->lastName = $data['lastName'];
                $user->address = $data['address'];
                $user->image = $data['image'];
                $user->save();

                $response = [
                    'status' => 201,
                    'message' => 'Usuario creado',
                    'user' => $user
                ];
            } else {
                $response = [
                    'status' => 406,
                    'message' => 'Datos inválidos',
                    'errors' => $isValid->errors()
                ];
            }
        } else {
            $response = [
                'status' => 400,
                'message' => 'No se encontró el objeto data'
            ];
        }

        return response()->json($response, $response['status']);
    }

    public function update(Request $request){
        $data_input=$request->input('data',null);
        if($data_input){
            $data=json_decode($data_input,true);
            $data=array_map('trim',$data);
            $rules=[
                'name'=>'required|alpha',
                'email'=>'required|email',
                'password'=>'required',
                'phone'=>'required',
                'rol'=>'required',
                'lastName' => 'required',
                'address' => 'required',
                'image'=>'required|image|mimes:jpg,jpeg,png,gif,svg'              
            ];
            $isValid=\validator($data,$rules);
            if(!$isValid->fails()){
                
                $jwt = new JwtAuth() ;
                $old = $jwt->checkToken($request->header('bearertoken'),true)->iss;
                $user = User::find($old);
                $user->name=$data['name'];
                $user->email=$data['email'];
                $user->password=hash('sha256',$data['password']);
                $user->rol=$data['rol'];
                $user->phone=$data['phone'];
                $user->lastName = $data['lastName'];
                $user->address = $data['address'];
                $user->image=$data['image'];
                $user->save();
                $response=array(
                    'status'=>201,
                    'message'=>'Usuario modificado',
                    'user'=>$user
                );
            }else{
                $response=array(
                    'status'=>406,
                    'message'=>'Datos inválidos',
                    'errors'=>$isValid->errors()
                );
            }
        }else{
            $response=array(
                'status'=>400,
                'message'=>'No se encontró el objeto data'                
            );
        }
        return response()->json($response,$response['status']);
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            $response = [
                'status' => 200,
                'message' => 'Usuario eliminado'
            ];
        } else {
            $response = [
                'status' => 404,
                'message' => 'User not found'
            ];
        }
        return response()->json($response, $response['status']);
    }

    public function login(Request $request){
        $data_input=$request->input('data',null);
        $data=json_decode($data_input,true);
        $data=array_map('trim',$data);
        $rules=['email'=>'required','password'=>'required'];
        $isValid=\validator($data,$rules);
        if(!$isValid->fails()){
            $jwt=new JwtAuth();
            $response=$jwt->getToken($data['email'],$data['password']);
            return response()->json($response);
        }else{
            $response=array(
                'status'=>406,
                'message'=>'Error en la validación de los datos',
                'errors'=>$isValid->errors(),
            );
            return response()->json($response,406);
        }
    }

    public function getIdentity(Request $request)
    {
        $jwt = new JwtAuth();
        $token = $request->header('bearertoken');
        if (isset($token)) {
            $response = $jwt->checkToken($token, true);
        } else {
            $response = [
                'status' => 404,
                'message' => 'Token (bearertoken) no encontrado',
            ];
        }
        return response()->json($response);
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
            \Storage::disk('users')->put($filename, \File::get($image));
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
            $exist = \Storage::disk('users')->exists($filename);
            if ($exist) {
                $file = \Storage::disk('users')->get($filename);
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
            \Storage::disk('users')->put($filename, \File::get($image));
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
            $exist = \Storage::disk('users')->exists($filename);
            if ($exist) {
                \Storage::disk('users')->delete($filename);
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