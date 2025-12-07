<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class temp_perm extends Model
{
    protected $table = "temp_perms";
    protected $fillable= ['name_ar','name_en'];

    protected $hidden =['created_at','updated_at'];
    // public $timestamps = true;

    public function clients(){
        return $this -> hasMany('App\Client','status_temp_perm_id','id');
    } 
}
