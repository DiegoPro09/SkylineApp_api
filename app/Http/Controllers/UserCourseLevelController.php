<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\UserCourseLevel;

class UserCourseLevelController extends Controller
{
    //Inserta un nuevo dato en la DB
    public function store(Request $request){
        //Recoger los datos por post
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        if(!empty($params_array)){
            //Validar los datos
            $validate = \Validator::make($params_array, [
                'user_id' => 'required|numeric',
                'course_id' => 'required|numeric',
                'level_id' => 'required|numeric'
            ]);

            if($validate->fails()){
                $data = [
                    'code' => 404,
                    'status' => 'error',
                    'message' => "No se ha guardado la relacion"
                ];
            }else{
                //Guardar relacion
                $ucl = new UserCourseLevel();
                $ucl->user_id = $params_array['user_id'];
                $ucl->course_id = $params_array['course_id'];
                $ucl->level_id = $params_array['level_id'];
                $ucl->save();

                $data = [
                    'code' => 404,
                    'status' => 'success',
                    'ucl' => $ucl
                ];
            }
        }else{
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'Usted no ha cargado ninguna relacion'
            ];
        }
        
        //Devolver el resultado
        return response()->json($data, $data['code']);
    }

    //Actualiza un dato en la DB
    public function update ($id, Request $request){
        //Recoger los datos por POST
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        if(!empty($params_array)){
            //Validar los datos
            $validate = \Validator::make($params_array, [
                'user_id' => 'required|numeric',
                'course_id' => 'required|numeric',
                'ucl_id' => 'required|numeric'
            ]);

            //Sacar lo que no quiero actualizar
            unset($params_array['id']);
            unset($params_array['created_at']);
            
            //Actualizar
            $ucl = UserCourseLevel::where('id', $id)->update($params_array);

            $data = [
                'code' => 200,
                'status' => 'success',
                'ucl' => $params_array
            ];

        }else{
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'No se reconoce la relacion'
            ];
        }

        return response()->json($data, $data['code']);
    }
}
