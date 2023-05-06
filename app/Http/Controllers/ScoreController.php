<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Score;
use App\Helpers\JwtAuth;

class ScoreController extends Controller
{
    //Trae todos los datos de la DB
    public function index(){
        $scores = Score::all()->load('assignaments')->load('users')->load('courseLevelDivisions');

        if(count($scores)){
            $data = [
                'code' => 200,
                'status' => 'success',
                'scores' => $scores
            ];
        }else{
            $data = [
                'code' => 400,
                'status' => 'error',
                'message' => "Aun no hay ninguna nota cargada"
            ];
        }

        return response()->json($data, $data['code']);
    }

    //Trae un dato filtrado por id de la DB
    public function show($id){
        $score = Score::find($id)->load('assignaments')->load('users')->load('courseLevelDivisions');

        if(is_object($score)){
            $data = [
                'code' => 200,
                'status' => 'success',
                'score' => $score
            ];
        }else{
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'No existe ninguna nota'
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
                "cld_id" => 'required|numeric',
                "user_id" => 'required|numeric',
                "assignament_id" => 'required|numeric',
                "first_score" => 'numeric',
                "second_score" => 'numeric',
                "dec_score" => 'numeric',
                "mar_score" => 'numeric',
                "average" => 'numeric',
                "year" => 'required|numeric'
            ]);
            
            if($validate->fails()){
                $data = [
                    'code' => 404,
                    'status' => 'error',
                    'message' => 'Nose ha podido cargar la nota'
                ];
            }else{
                if(($params_array['first_score'] > 0 && $params_array['first_score'] < 11) && 
                    ($params_array['second_score'] > 0 && $params_array['second_score'] < 11) && 
                    ($params_array['dec_score'] > 0 && $params_array['dec_score'] < 11) && 
                    ($params_array['mar_score'] > 0 && $params_array['mar_score'] < 11) && 
                    ($params_array['average'] > 0 && $params_array['average'] < 11)){
                    
                    //Guardar notas
                    $score = new Score();
                    $score->cld_id = $params_array['cld_id'];
                    $score->user_id = $params_array['user_id'];
                    $score->assignament_id = $params_array['assignament_id'];
                    $score->first_score = $params_array['first_score'];
                    $score->second_score = $params_array['second_score'];
                    $score->dec_score = $params_array['dec_score'];
                    $score->mar_score = $params_array['mar_score'];
                    $score->average = $params_array['average'];
                    $score->year = $params_array['year'];
                    $score->save();
                    
                    $data = [
                        'code' => 200,
                        'status' => 'success',
                        'score' => $score
                    ];
                }else{
                    $data = [
                        'code' => 404,
                        'status' => 'error',
                        'message' => 'Las notas tienen que ser mayor a 0 y menor que 11'
                    ];
                }
            }
        }else{
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'Nose ha ingresado bien los datos o hay campos vacios'
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
                "first_score" => 'numeric',
                "second_score" => 'numeric',
                "dec_score" => 'numeric',
                "mar_score" => 'numeric',
                "average" => 'numeric',
                "year" => 'required|numeric'
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
                unset($params_array['cld_id']);
                unset($params_array['user_id']);
                unset($params_array['assignament_id']);
                unset($params_array['updated_at']);

                if(($params_array['first_score'] > 0 && $params_array['first_score'] < 11) && 
                    ($params_array['second_score'] > 0 && $params_array['second_score'] < 11) && 
                    ($params_array['dec_score'] > 0 && $params_array['dec_score'] < 11) && 
                    ($params_array['mar_score'] > 0 && $params_array['mar_score'] < 11) && 
                    ($params_array['average'] > 0 && $params_array['average'] < 11)){

                    //Actualizar 
                    $score = Score::where('id', $id)->update($params_array);
                    
                    //Devolver el resultado
                    $data = [
                        'code' => 200,
                        'status' => 'success',
                        'message' => 'Datos actualizados',
                        'score' => $params_array
                    ];
                }else{
                    $data = [
                        'code' => 404,
                        'status' => 'error',
                        'message' => 'Las notas tienen que ser mayor a 0 y menor que 11'
                    ];
                }
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
        $score = Score::find($id);

        if(!empty($score)){
            //Borrarlo
            $score->delete();
            
            //Devolver el resultado
            $data = [
                'code' => 200,
                'status' => 'success',
                'Notas eliminadas' => $score
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
