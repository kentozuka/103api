<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jugyou extends Model
{
    public function posts()
    {
        return $this->hasMany('Post');
    }

    protected $table = 'classes';
    
    public function dept() {
        return $this->belongsTo('App\Dept');
    }

    public function language() {
        return $this->belongsTo('App\Language');
    }

    public function term() {
        return $this->belongsTo('App\Term');
    }

    public function campus() {
        return $this->belongsTo('App\Campus');
    }

    public function profs() {
        return $this->belongsToMany('App\Prof', 'class_profs', 'class_id');
    }

    public function rooms() {
        return $this->belongsToMany('App\Room', 'class_rooms', 'class_id');
    }

    public function class_cat() {
        return $this->hasMany('App\ClassCat', 'class_id');
    }

    public function class_prof() {
        return $this->hasMany('App\ClassProf', 'class_id');
    }

    public function class_room() {
        return $this->hasMany('App\ClassRoom', 'class_id');
    }

    public function period() {
        return $this->hasMany('App\Period', 'class_id');
    }

    public function detail() {
        return $this->hasOne('App\Detail', 'class_id');
    }

    public function review() {
        return $this->hasMany('App\Review', 'class_id');
    }

}
