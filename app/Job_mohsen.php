<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job_mohsen extends Model
{
    protected $table = "job_mohsens";
    protected $fillable= ['name_ar','name_en'];

    protected $hidden =['created_at','updated_at'];
    // public $timestamps = true;

    public function users(){
        return $this -> hasMany('App\User','jobs_id','id');
    } 
}
