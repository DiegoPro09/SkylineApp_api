<?php

namespace App\Http\Controllers;

use App\Models\Assignament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;

class AssignamentController extends Controller
{
    //Trae todos los datos de la DB
    public function index(){
        $assignaments = Assignament::all();

        if(count($assignaments) > 0){
            return $assignaments;
        }else{
            return [];
        }
    }

    //Filtra un solo dato por id en la DB y lo devuelve
    public function show($id){
        $assignament = Assignament::find($id);

        if(is_object($assignament)){
            return $assignament;
        }else{
            return $data = [
                'code' => 404,
                'status' => 'error',
                'message' => "La materia no existe"
            ];
        }
    }

    //Guarda la materia en la DB
    public function store(Request $request){
        //Recoger los datos por post
        $form = $request->all();
        $json = json_encode($form);
        $params_array = json_decode($json, true);

        if(!empty($params_array)){
            //Validar los datos
            $validate = \Validator::make($params_array, [
                'name' => 'required'
            ]);

            if($validate->fails()){
                $data = [
                    'code' => 404,
                    'status' => 'error',
                    'message' => "No se ha guardado la materia, verifique que ingreso correctamente los datos"
                ];
            }else{
                $findAssignament = DB::table('assignaments')->select('name')->where('name', '=', $params_array['name'])->first();

                if($findAssignament === null){
                    //Guardar la materia
                    $assignament = new Assignament();
                    $assignament->name = strtoupper($params_array['name']);
                    $assignament->save();
                    
                    $data = [
                        'code' => 200,
                        'status' => 'success',
                        'assignament' => $assignament
                    ];
                }else{
                    $data = [
                        'code' => 404,
                        'status' => 'error',
                        'message' => 'Esta materia ya fue creada, por favor ingrese otra'
                    ];
                }
            }
        }else{
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'Usted no ha cargado ninguna materia'
            ];
        }
        
        //Devolver el resultado
        return response()->json($data, $data['code']);
    }

    public function update ($id, Request $request){
        //Recoger los datos por POST
        $form = $request->all();
        $json = json_encode($form);
        $params_array = json_decode($json, true);

        if(!empty($params_array)){
            //Validar los datos
            $validate = \Validator::make($params_array, [
                'name' => 'required'
            ]);

            //Sacar lo que no quiero actualizar
            unset($params_array['id']);
            unset($params_array['created_at']);
            
            $findAssignament = DB::table('assignaments')->select('name')->where('name', '=', $params_array['name'])->first();

            if($findAssignament === null){
                
                $params_array['name'] = strtoupper($params_array['name']);
                //Actualizar
                $assignament = Assignament::where('id', $id)->update($params_array);
                
                $data = [
                    'code' => 200,
                    'status' => 'success',
                    'assignament' => $params_array
                ];
            }else{
                $data = [
                    'code' => 404,
                    'status' => 'error',
                    'message' => 'Ya existe una materia con este nombre, por favor ingrese otra'
                ];
            }

        }else{
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'No se reconoce la materia'
            ];
        }

        return response()->json($data, $data['code']);
    }

    //Eliminar un dato de la DB
    public function destroy($id, Request $request){
        //Comprobar si existen los datos
        $assignament = Assignament::find($id);

        if(!empty($assignament)){
            //Borrarlo
            $assignament->delete(); 
            
            //Devolver el resultado
            $data = [
                'code' => 200,
                'status' => 'success',
                'Notas eliminadas' => $assignament
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
