<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reason extends Model
{
    protected $table = "reasons";
    protected $fillable= ['name_ar','name_en'];

    protected $hidden =['created_at','updated_at'];
    // public $timestamps = true;

    public function clients(){
        return $this -> hasMany('App\Client','reason_id','id');
    } 

    // protected $dates = ['date_of_birth','last_delivery_date','next_delivery_date','	created_at','updated_at'];


    /* public function doctors(){
        return $this -> hasMany('App\Doctor','hospital_id','id');
    } */
}
