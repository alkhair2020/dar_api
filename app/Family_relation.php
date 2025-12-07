<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Family_relation extends Model
{
    protected $table = "family_relations";
    protected $fillable= ['name_ar','name_en']; 

    //protected $hidden =['created_at','updated_at'];

    public function family_members(){
        return $this -> hasMany('App\Family_member','family_relations_id','id');
    } 
}
