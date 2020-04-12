<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClassCat extends Model
{
    public $timestamps = false;
    
    public function class() {
        return $this->belongsTo('App\Jugyou');
    }

    public function first() {
        return $this->belongsTo('App\First');
    }

    public function second() {
        return $this->belongsTo('App\Second');
    }

    public function third() {
        return $this->belongsTo('App\Third');
    }

    public function type() {
        return $this->belongsTo('App\Type');
    }

    public function level() {
        return $this->belongsTo('App\Level');
    }
}
