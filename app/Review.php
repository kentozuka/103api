<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    public function class() {
        return $this->belongsTo('App\Jugyou');
    }

    public function like() {
        return $this->hasMany('App\Like');
    }

    public function dislike() {
        return $this->hasMany('App\Dislike');
    }
}
