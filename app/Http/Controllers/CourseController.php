<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Models\Course;

class CourseController extends Controller
{
    //Trae todos los datos de la DB
    public function index(){
        $courses = Course::all();

        if(count($courses) > 0){
            return $courses;
        }else{
            return [];
        }
    }

    //Trae un dato filtrado por id de la DB
    public function show($id){
        $course = Course::find($id);

        if(is_object($course)){
            return $course;
        }else{
            return $data = [
                'code' => 404,
                'status' => 'error',
                'message' => "El curso no existe"
            ];
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
                'number' => 'required|numeric',
                'name' => 'required'
            ]);

            if($validate->fails()){
                $data = [
                    'code' => 404,
                    'status' => 'error',
                    'message' => "No se ha guardado el curso"
                ];
            }else{
                $findCourse = DB::table('courses')->select('name')->where('number', '=', $params_array['number'])->where('name', '=', $params_array['name'])->first();

                if($findCourse === null){
                    //Guardar el curso
                    $course = new Course();
                    $course->number = $params_array['number'];
                    $course->name = strtoupper($params_array['name']);
                    $course->save();

                    $data = [
                        'code' => 200,
                        'status' => 'success',
                        'message' => 'Curso cargado correctamente'
                    ];
                }else{
                    $data = [
                        'code' => 404,
                        'status' => 'error',
                        'message' => 'Este curso ya esta cargado, por favor ingrese otro'
                    ];
                }
            }
        }else{
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'Usted no ha cargado ningun curso'
            ];
        }
        
        //Devolver el resultado
        return response()->json($data, $data['code']);
    }

    //Actualiza un dato en la DB
    public function update ($id, Request $request){
        //Recoger los datos por POST
        $form = $request->all();
        $json = json_encode($form);
        $params = json_encode($json); //OBJETO
        $params_array = json_decode($json, true); //ARRAY

        if(!empty($params_array)){
            //Validar los datos
            $validate = \Validator::make($params_array, [
                'name' => 'required',
                'number' => 'required|numeric'
            ]);

            //Sacar lo que no quiero actualizar
            unset($params_array['id']);
            unset($params_array['created_at']);

            $findCourse = DB::table('courses')->select('name')->where('number', '=', $params_array['number'])->where('name', '=', $params_array['name'])->first();

            if($findCourse === null){

                $params_array['name'] = strtoupper($params_array['name']);
                //Actualizar
                $course = Course::where('id', $id)->update($params_array);

                $data = [
                    'code' => 200,
                    'status' => 'success',
                    'course' => $params_array
                ];
            }else{
                $data = [
                    'code' => 404,
                    'status' => 'error',
                    'message' => 'Ya existe un curso igual, por favor ingrese otro'
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
        $course = Course::find($id);

        if(!empty($course)){
            //Borrarlo
            $course->delete();
            
            //Devolver el resultado
            $data = [
                'code' => 200,
                'status' => 'success',
                'Notas eliminadas' => $course
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
