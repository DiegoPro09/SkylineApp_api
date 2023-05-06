<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\CourseLevelDivision;
use App\Models\Course;

class CourseLevelDivisionController extends Controller
{
    //Trae todos los datos de la DB
    public function index(){
        $cld = CourseLevelDivision::all();

        $count = 0;
        foreach ($cld as $clds) {
            $arrayCld[$count]['id'] = $clds->id;
            $arrayCld[$count]['course'] = $clds->course;
            $arrayCld[$count]['level'] = $clds->level;
            $arrayCld[$count]['division'] = $clds->division;
            $arrayCld[$count]['specialization'] = $clds->specialization;
            $arrayCld[$count]['year'] = $clds->year;
            $count++;
        }

        if(count($cld) > 0){
            return $arrayCld;
        }else{
            return [];
        }
    }

    //Inserta un nuevo dato en la DB
    public function store(Request $request){
        //Recoger los datos por post
        $form = $request->all();
        $json = json_encode($form);
        $params = json_encode($json); //OBJETO
        $params_array = json_decode($json, true); //ARRAY

        if(!empty($params_array)){
            //Validar los datos
            $validate = \Validator::make($params_array, [
                'course_id' => 'required|numeric',
                'level_id' => 'required|numeric',
                'division_id' => 'required|numeric',
                'specialization_id' => 'required|numeric',
                'year' => 'required',
            ]);

            if($validate->fails()){
                $data = [
                    'code' => 404,
                    'status' => 'error',
                    'message' => "No se ha guardado la relacion",
                    'params' => $params_array
                ];
            }else{
                //Guardar relacion
                $cld = new CourseLevelDivision();
                $cld->course_id = $params_array['course_id'];
                $cld->level_id = $params_array['level_id'];
                $cld->division_id = $params_array['division_id'];
                $cld->specialization_id = $params_array['specialization_id'];
                $cld->year = $params_array['year'];
                $cld->save();

                $data = [
                    'code' => 200,
                    'status' => 'success',
                    'cld' => $cld
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
                'course_id' => 'required|numeric',
                'level_id' => 'required|numeric',
                'division_id' => 'required|numeric',
                'specialization_id' => 'required|numeric',
                'year' => 'required',
            ]);

            //Sacar lo que no quiero actualizar
            unset($params_array['id']);
            unset($params_array['created_at']);
            
            //Actualizar
            $cld = CourseLevelDivision::where('id', $id)->update($params_array);

            $data = [
                'code' => 200,
                'status' => 'success',
                'cld' => $params_array
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
