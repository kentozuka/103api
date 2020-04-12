<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Third extends Model
{
    public $timestamps = false;
    
    public function classcat() {
        return $this->hasMany('App\ClassCat');
    }
    
    public function first() {
        return $this->belongsTo('App\Second');
    }

    public function second() {
        return $this->belongsTo('App\Second');
    }
}
