<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Behavior extends Model
{
    use HasFactory;
    protected $table = 'behaviors';

    //Relacion de muchos a uno
    public function users(){
        return $this->BelongsTo('App\Models\User', 'user_id');
    }
}
