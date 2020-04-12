<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Saved extends Model
{
    public function class() {
        return $this->belongsTo('App\Jugyou');
    }
}
