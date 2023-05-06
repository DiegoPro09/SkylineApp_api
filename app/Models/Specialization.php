<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialization extends Model
{
    use HasFactory;
    protected $table = 'specializations';

    //Relacion de uno a uno
    public function courseLevelDivision(){
        return $this->hasOne('App\Models\CourseLevelDivision');
    }

    //Relacion de uno a uno
    public function courseAssignament(){
        return $this->hasOne('App\Models\CourseAssingament');
    }
}
