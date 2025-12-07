<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientAttachments extends Model
{
    // protected $table = "client_attachments";
    protected $fillable= ['delivery_id','client_id','image','video'];

    public function deliveries(){
        return $this -> belongsTo('App\Deliveries','delivery_id','id');
    }
    public function clients(){
        return $this -> belongsTo('App\Client','clients_id','id');
    }
}
