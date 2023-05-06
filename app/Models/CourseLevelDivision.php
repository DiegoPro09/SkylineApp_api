<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseLevelDivision extends Model
{
    use HasFactory;
    protected $table = 'course_level_divisions';

    //Relacion de uno a uno
    public function course(){
        return $this->belongsTo('App\Models\Course');
    }

    //Relacion de uno a uno
    public function level(){
        return $this->belongsTo('App\Models\Level');
    }

    //Relacion de uno a uno
    public function division(){
        return $this->belongsTo('App\Models\Division');
    }

    //Relacion de uno a uno
    public function specialization(){
        return $this->belongsTo('App\Models\Specialization');
    }

    //Relacion de uno a muchos
    public function courseAssignaments(){
        return $this->hasMany('App\Models\CourseAssingament');
    }
}
