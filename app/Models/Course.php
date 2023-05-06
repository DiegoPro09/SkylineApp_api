<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $table = 'courses';

    //Relacion de uno a uno
    public function courseLevelDivision(){
        return $this->hasOne('App\Models\CourseLevelDivision');
    }
}
