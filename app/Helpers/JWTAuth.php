<?php

namespace App\Helpers;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class JwtAuth{

    public $key;

    public function __construct(){
        $this->key = "qazxswedcvfrtgbnhyujmikolpñ159753456258";
    }

    public function signup($email, $password, $answer, $dni, $getToken = null){
        //Buscar si existe el usuario 
        $user = User::where([
            'email' => $email,
            'password' => $password,
            'dni' => $dni,
            'answer' => $answer
        ])->first();

        //Comprobar si son correctas
        $signup = false;

        if(is_object($user)){
            $signup = true;
        }

        //Generar el token con los datos del usuario
        if($signup){
            $token = [
                'sub' => $user->id,
                'email' => $user->email,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'role_id' => $user->role_id,
                'state' => $user->state,
                'iat' => time(),
                'exp' => time() + (7 * 24 * 60 * 60)
            ];
            

            $jwt = JWT::encode($token, $this->key, 'HS256');
            $decoded = JWT::decode($jwt, $this->key, ['HS256']);

            //Devolver token
            if(is_null($getToken)){
                $data =  [
                    'code' => 200,
                    'status' => 'success',
                    'data' => $jwt,
                    'user' => $user
                ];
            }else{
                $data = $decoded;
            }
        }else{
            $data = [
                'status' => 'error',
                'code' => 404,
                'message' => 'Login incorrecto, verifique que ha ingresado bien los datos',
                'user' => $email
            ];
        }

        return $data;
    }

    public function checkToken($jwt, $getIdentity = false){
        $auth = false;

        try{
            $jwt = str_replace('"', '', $jwt);
            $decoded = JWT::decode($jwt, $this->key, ['HS256']);
        }catch(\UnexpectedValueException $e){
            $auth = false;
        }catch(\DomainException $e){
            $auth = false;
        }

        if(!empty($decoded) && is_object($decoded) && isset($decoded->sub)){
            $auth = true;
        }else{
            $auth = false;
        }

        if($getIdentity){
            return $decoded;
        }

        return $auth;
    }
}

?>