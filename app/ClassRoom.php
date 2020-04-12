<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClassRoom extends Model
{
    public $timestamps = false;
    
    public function class() {
        return $this->belongsTo('App\Jugyou');
    }

    public function room() {
        return $this->belongsTo('App\Room');
    }
}
