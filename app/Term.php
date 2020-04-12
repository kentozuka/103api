<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    public $timestamps = false;
    
    public function class() {
        return $this->belongsTo('App\Class');
    }
}
