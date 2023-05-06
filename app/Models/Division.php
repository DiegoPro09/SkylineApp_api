<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    use HasFactory;
    protected $table = 'divisions';

    //Relacion de uno a uno
    public function courseLevelDivision(){
        return $this->hasOne('App\Models\CourseLevelDivision');
    }
}
