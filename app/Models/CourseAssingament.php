<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseAssingament extends Model
{
    use HasFactory;

    protected $table = 'course_assignaments';

    //Relacion de uno a uno
    public function courseLevelDivision(){
        return $this->belongsTo('App\Models\CourseLevelDivision');
    }

    //Relacion de uno a uno
    public function assignament(){
        return $this->belongsTo('App\Models\Assignament');
    }

    //Relacion de uno a uno
    public function specialization(){
        return $this->belongsTo('App\Models\Specialization');
    }
}
