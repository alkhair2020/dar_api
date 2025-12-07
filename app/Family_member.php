<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Family_member extends Model
{
    protected $table = "family_members";
    protected $fillable= ['name','id_card_no','phone','clients_id','family_relations_id','family_members_birth' , 'family_members_check'];

    protected $dates = ['created_at','updated_at'];

    //protected $hidden =['created_at','updated_at'];
    public $timestamps = true;


    public function clients(){
        return $this -> belongsTo('App\Client','clients_id','id');
    }

    public function family_relations(){
        return $this -> belongsTo('App\Family_relation','family_relations_id','id');
    }

}
