<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    public $timestamps = false;
    
    public function class() {
        return $this->belongsTo('App\Jugyou', 'class_id');
    }
}
