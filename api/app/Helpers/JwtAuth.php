<?php
namespace App\Helpers;

use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\Key;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class JwtAuth{
    private $key;
    function __construct(){
        $this->key="yO4EsCt3eEFO2zXXn9usA5o5ST85SBb"; //Llave privada
    }
    public function getToken($email,$password){
        $pass=hash('sha256',$password);
        //var_dump($pass);
        $user=User::where(['email'=>$email,'password'=>hash('sha256',$password)])->first();
        //var_dump($user);
        if(is_object($user)){
            /**Payload Llave publica*/
            $token=array(
                'iss'=>$user->id,
                'name'=>$user->name,
                'email'=>$user->email,
                'rol'=>$user->rol,
                'phone'=>$user->phone,
                'iat'=>time(),
                'exp'=>time()+(2000)
            );
            $data=JWT::encode($token,$this->key,'HS256');
        }else{
            $data=array(
                'status'=>401,
                'message'=>'Datos de autenticación incorrectos'
            );
        }
        return $data;
    }
    public function checkToken($jwt,$getId=false){
        $authFlag=false;
        if(isset($jwt)){
            try{
                $decoded=JWT::decode($jwt,new Key($this->key,'HS256'));
            }catch(\DomainException $ex){
                $authFlag=false;
            }catch(ExpiredException $ex){
                $authFlag=false;
            }
            if(!empty($decoded)&&is_object($decoded)&&isset($decoded->iss)){
                $authFlag=true;
            }
            if($getId && $authFlag){
                return $decoded;
            }
        }
        return $authFlag;
    }
}