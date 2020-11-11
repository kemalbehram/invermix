<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class inyects extends Model
{
    public function invests(){

        return $this->belongsTo('App\investment', 'id');
    }

    public function users(){

        return $this->belongsTo('App\User', 'id');
    }
}
