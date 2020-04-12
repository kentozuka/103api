<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Grading extends Model
{
    public $timestamps = false;
    
    public function class() {
        return $this->belongsTo('App\Jugyou');
    }

    public function detail() {
        return $this->hasOne('App\Detail');
    }
}
