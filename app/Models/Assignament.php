<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignament extends Model
{
    use HasFactory;
    protected $table = 'assignaments';

    //Relacion de uno a muchos
    public function scores(){
        return $this->hasMany('App\Models\Score');
    }

    //Relacion de uno a muchos
    public function courseAssignament(){
        return $this->hasOne('App\Models\CourseAssingament');
    }
}
