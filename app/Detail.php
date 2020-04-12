<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detail extends Model
{
    public function class() {
        return $this->belongsTo('App\Jugyou');
    }

    public function grading() {
        return $this->belongsTo('App\Grading');
    }
}
