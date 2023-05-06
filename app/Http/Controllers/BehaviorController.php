<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Behavior;
use App\Helpers\JwtAuth;

class BehaviorController extends Controller
{
    //Trae todos los datos de la DB
    public function index(){
        $behaviors = Behavior::all()->load('users');

        if(count($behaviors)){
            $data = [
                'code' => 200,
                'status' => 'success',
                'behaviors' => $behaviors
            ];
        }else{
            $data = [
                'code' => 400,
                'status' => 'error',
                'message' => "Aun no hay conductas cargadas"
            ];
        }

        return response()->json($data, $data['code']);
    }

    //Trae un dato filtrado por id de la DB
    public function show($id){
        $behavior = Behavior::find($id)->load('users');

        if(is_object($behavior)){
            $data = [
                'code' => 200,
                'status' => 'success',
                'behavior' => $behavior
            ];
        }else{
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'No existe ninguna conducta'
            ];
        }

        return response()->json($data, $data['code']);
    }

    //Inserta los datos en la DB
    public function store(Request $request){
        //Recoger los datos por post
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        if(!empty($params_array)){
            //Validar los datos
            $validate = \Validator::make($params_array, [
                "user_id" => 'required|numeric',
                "f_justify" => 'required',
                "f_injustify" => 'numeric',
                "sanctions" => 'numeric',
                "observations" => 'string'
            ]);
            
            if($validate->fails()){
                $data = [
                    'code' => 404,
                    'status' => 'error',
                    'message' => 'Nose ha podido cargar la nota'
                ];
            }else{
                //Guardar notas
                $behavior = new Behavior();
                $behavior->user_id = $params_array['user_id'];
                $behavior->f_justify = $params_array['f_justify'];
                $behavior->f_injustify = $params_array['f_injustify'];
                $behavior->sanctions = $params_array['sanctions'];
                $behavior->observations = $params_array['observations'];
                $behavior->save();
                    
                $data = [
                    'code' => 200,
                    'status' => 'success',
                    'behavior' => $behavior
                ];
            }
        }else{
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'No se ha ingresado bien los datos o hay campos vacios'
            ];
        }

        //Devolver el resultado
        return response()->json($data, $data['code']);
    }

    //Actualiza los datos
    public function update($id, Request $request){
        //Recoger los datos por post
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        if(!empty($params_array)){
            //Validar los datos
            $validate = \Validator::make($params_array, [
                "user_id" => 'required|numeric',
                "f_justify" => 'required',
                "f_injustify" => 'numeric',
                "sanctions" => 'numeric',
                "observations" => 'string'
            ]);

            if($validate->fails()){
                $data = [
                    'code' => 404,
                    'status' => 'error',
                    'message' => 'Nose ha podido cargar las notas'
                ];
            }else{
                //Eliminar lo que no se actualiza
                unset($params_array['id']);
                unset($params_array['user_id']);
                unset($params_array['updated_at']);

                //Actualizar 
                $behavior = Behavior::where('id', $id)->update($params_array);
                    
                //Devolver el resultado
                $data = [
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'Datos actualizados',
                    'behavior' => $params_array
                ];
            
            }
        }else{
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'Nose ha ingresado bien los datos o hay campos vacios'
            ];
        }

        return response()->json($data, $data['code']);
    }

    //Eliminar un dato de la DB
    public function destroy($id, Request $request){
        //Comprobar si existen los datos
        $behavior = Behavior::find($id);

        if(!empty($behavior)){
            //Borrarlo
            $behavior->delete();
            
            //Devolver el resultado
            $data = [
                'code' => 200,
                'status' => 'success',
                'Datos eliminadas' => $behavior
            ];
        }else{
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'No existe'
            ];
        }

        return response()->json($data, $data['code']);
    }
}
