<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class inyects extends Model
{
    use SoftDeletes;

    public function invests(){

        return $this->belongsTo('App\investment', 'id');
    }

    public function users(){

        return $this->belongsTo('App\User', 'id');
    }
}
