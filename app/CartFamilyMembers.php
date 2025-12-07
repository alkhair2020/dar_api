<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CartFamilyMembers extends Model
{
    protected $table = "cart_family_members";
    protected $fillable= ['name_ar','name_en','from_no','to_no']; 
    public $timestamps = false;
}
