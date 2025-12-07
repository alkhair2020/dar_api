<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PhoneVerificationCode extends Model
{
    //
    protected $fillable=['phone','code'];
}
