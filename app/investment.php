<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class investment extends Model
{
    protected $table="invest";






    public function inyects(){

        return $this->hasMany('App\inyects', 'id');
    }
}
