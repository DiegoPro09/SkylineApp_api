<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use App\Models\Specialization;

class SpecializationController extends Controller
{
    //Trae todos los datos de la DB
    public function index(){
        $specializations = Specialization::all();

        if(count($specializations) > 0){
            return $specializations;
        }else{
            return [];
        }
    }
}
