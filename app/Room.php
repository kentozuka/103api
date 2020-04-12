<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    public $timestamps = false;
    
    public function class_room() {
        return $this->hasOne('App\ClassRoom');
    }

    public function campus() {
        return $this->belongsTo('App\Campus');
    }
}
