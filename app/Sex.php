<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sex extends Model
{
    protected $table = "sexs";
    protected $fillable= ['name_ar','name_en'];

    protected $hidden =['created_at','updated_at'];
    // public $timestamps = true;

     public function clients(){
        return $this -> hasMany('App\Client','sex','id');
    } 
    
    public function users(){
        return $this -> hasMany('App\User','gender','id');
    } 
    // protected $dates = ['date_of_birth','last_delivery_date','next_delivery_date','	created_at','updated_at'];


    /* public function doctors(){
        return $this -> hasMany('App\Doctor','hospital_id','id');
    } */
}
