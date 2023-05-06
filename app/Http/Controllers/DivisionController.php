<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use App\Models\Division;

class DivisionController extends Controller
{
    //Trae todos los datos de la DB
    public function index(){
        $divisions = Division::all();

        if(count($divisions) > 0){
            return $divisions;
        }else{
            return [];
        }
    }

    //Trae un dato filtrado por id de la DB
    public function show($id){
        $division = Division::find($id);

        if(is_object($division)){
            $data = [
                'code' => 200,
                'status' => 'success',
                'division' => $division
            ];
        }else{
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => "The division does't exists"
            ];
        }

        return response()->json($data, $data['code']);
    }

    //Inserta un nuevo dato en la DB
    public function store(Request $request){
        //Recoger los datos por post
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        if(!empty($params_array)){
            //Validar los datos
            $validate = \Validator::make($params_array, [
                'name' => 'required|alpha',
                'turn' => 'required|alpha',
            ]);

            if($validate->fails()){
                $data = [
                    'code' => 400,
                    'status' => 'error',
                    'message' => "No se ha guardado la division"
                ];
            }else{
                if(strlen($params_array['name']) === 1){
                    $findDivision = DB::table('divisions')->select('name')->where('turn', '=', $params_array['turn'])->where('name', '=', $params_array['name'])->first();

                    if($findDivision === null){
                        //Guardar la division
                        $division = new Division();
                        $division->name = strtoupper($params_array['name']);
                        $division->turn = strtoupper($params_array['turn']);
                        $division->save();
                        
                        $data = [
                            'code' => 404,
                            'status' => 'success',
                            'division' => $division
                        ];
                    }else{
                        $data = [
                            'code' => 404,
                            'status' => 'error',
                            'message' => 'Eta division y turno estan cargados por favor ingrese uno nuevo'
                        ];
                    }
                }else{
                    $data = [
                        'code' => 404,
                        'status' => 'error',
                        'message' => 'Solamente puede ingresar un caracter como nombre'
                    ];
                }
            }
        }else{
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'Usted no ha cargado ninguna division'
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
                'name' => 'required|alpha',
                'turn' => 'required|alpha'
            ]);

            //Sacar lo que no quiero actualizar
            unset($params_array['id']);
            unset($params_array['created_at']);
            
            if(strlen($params_array['name']) === 1){
                $findDivision = DB::table('divisions')->select('name')->where('turn', '=', $params_array['turn'])->where('name', '=', $params_array['name'])->first();

                if($findDivision === null){
                    $params_array['name'] = strtoupper($params_array['name']);
                    $params_array['turn'] = strtoupper($params_array['turn']);

                    //Actualizar
                    $division = Division::where('id', $id)->update($params_array);
                    
                    $data = [
                        'code' => 200,
                        'status' => 'success',
                        'division' => $params_array
                    ];
                }else{
                    $data = [
                        'code' => 404,
                        'status' => 'error',
                        'message' => 'Eta division y turno estan cargados por favor ingrese uno nuevo'
                    ];
                }
            }else{
                $data = [
                    'code' => 404,
                    'status' => 'error',
                    'message' => 'Solamente puede ingresar un caracter como nombre'
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
        $division = Division::find($id);

        if(!empty($division)){
            //Borrarlo
            $division->delete();
            
            //Devolver el resultado
            $data = [
                'code' => 200,
                'status' => 'success', 
                'Notas eliminadas' => $division
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
