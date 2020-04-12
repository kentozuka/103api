<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClassProf extends Model
{
    public $timestamps = false;
    
    public function class() {
        return $this->belongsTo('App\Jugyou');
    }

    public function prof() {
        return $this->belongsTo('App\Prof');
    }
}
