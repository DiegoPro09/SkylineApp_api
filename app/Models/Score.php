<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    use HasFactory;
    protected $table = 'scores';
    
    //Relacion de muchos a uno
    public function users(){
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    //Relacion de muchos a uno
    public function assignaments(){
        return $this->belongsTo('App\Models\Assignament', 'assignament_id');
    }

    //Relacion de muchos a uno
    public function courseLevelDivisions(){
        return $this->belongsTo('App\Models\CourseLevelDivision', 'cld_id');
    }
}
