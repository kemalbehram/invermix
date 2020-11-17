<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class investment extends Model
{
    use SoftDeletes;

    protected $table="invest";


    public function inyects(){

        return $this->hasMany('App\inyects', 'id');
    }
}
