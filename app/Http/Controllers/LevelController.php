<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use App\Models\Level;

class LevelController extends Controller
{
    //Trae todos los datos de la DB
    public function index(){
        $levels = Level::all();

        if(count($levels) > 0){
            return $levels;
        }else{
            return [];
        }
    }

    //Trae un dato filtrado por id de la DB
    public function show($id){
        $level = Level::find($id);

        if(is_object($level)){
            return $level;
        }else{
            return $data = [
                'code' => 404,
                'status' => 'error',
                'message' => "El nivel no existe"
            ];
        }
    }
}
