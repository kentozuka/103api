<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    public $timestamps = false;
    
    public function class_cat() {
        return $this->belongsTo('App\ClassCat');
    }
}
