<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;

class RolesController extends Controller
{
    //Trae todos los datos de la DB
    public function index(){
        $roles = Role::all();

        if(count($roles) > 0){
            return $roles;
        }else{
            return [];
        }
    }
}
