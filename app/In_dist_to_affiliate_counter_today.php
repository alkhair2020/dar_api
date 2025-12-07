<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class In_dist_to_affiliate_counter_today extends Model
{
    protected $table = "in_dist_to_affiliate_counter_todays";
    protected $fillable= ['counter','created_at','updated_at'];
}
