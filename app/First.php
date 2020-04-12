<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class First extends Model
{
    public $timestamps = false;
    
    public function classcat() {
        return $this->hasMany('App\ClassCat');
    }
}
