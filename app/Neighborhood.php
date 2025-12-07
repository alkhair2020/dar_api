<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Neighborhood extends Model
{
    protected $table = "neighborhoods";
    protected $fillable= ['name_ar','name_en','id_municipalities'];

    protected $hidden =['created_at','updated_at'];
    // public $timestamps = true;

    public function clients(){
        return $this -> hasMany('App\Client','neighborhood_id','id');
    }

    // protected $dates = ['date_of_birth','last_delivery_date','next_delivery_date','	created_at','updated_at'];


    /* public function doctors(){
        return $this -> hasMany('App\Doctor','hospital_id','id'); 
    } */
}
