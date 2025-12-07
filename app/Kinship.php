<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kinship extends Model
{
    protected $table = "kinships";
    protected $fillable= ['name_ar','name_en', 'show'];

    //protected $hidden =['created_at','updated_at'];

    public function escorts(){
        return $this -> hasMany('App\Escort','kinship_id','id');
    }

    public function receipt_agents_clients(){
        return $this -> hasMany('App\Receipt_agents_client','kinship_id','id');
    }
}
