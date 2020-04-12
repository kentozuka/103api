<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Campus extends Model
{
    public $timestamps = false;
    
    public function class() {
        return $this->hasMany('App\Jugyou');
    }
}
