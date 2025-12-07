<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CartAge extends Model
{
    protected $table = "cart_age";
    protected $fillable= ['name_ar','name_en','from_no','to_no']; 
    public $timestamps = false;
}
