<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prof extends Model
{
    public $timestamps = false;
    
    public function class_prof() {
        return $this->belongsTo('App\ClassProf');
    }
}
