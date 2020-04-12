<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dept extends Model
{
    public $timestamps = false;
    
    public function class() {
        return $this->hasMany('App\Jugyou');
    }
}
