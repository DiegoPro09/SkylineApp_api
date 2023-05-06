<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;
    protected $table = 'levels';

    //Relacion de uno a uno
    public function courseLevelDivision(){
        return $this->hasOne('App\Models\CourseLevelDivision');
    }
}
