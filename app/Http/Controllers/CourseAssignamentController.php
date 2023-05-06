<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\CourseAssingament;
use App\Models\Course;

class CourseAssignamentController extends Controller
{
    //Trae todos los datos de la DB
    public function index(){
        $cac = CourseAssingament::all();

        $count = 0;
        foreach ($cac as $cacs) {
            $arrayCac[$count]['id'] = $cacs->id;
            $arrayCac[$count]['cld'] = $cacs->courseLevelDivision;
            $arrayCac[$count]['assignament'] = $cacs->assignament;
            $arrayCac[$count]['specialization'] = $cacs->specialization;
            $arrayCac[$count]['order'] = $cacs->order;
            $count++;
        }

        if(count($cac) > 0){
            return $arrayCac;
        }else{
            return [];
        }
    }
}
