<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientNotes extends Model
{
    protected $table = "client_notes";
    protected $fillable= ['user_id','note','client_status ','date'];
    public function users(){
        return $this ->belongsTo('App\User','user_id','id');
    }
}