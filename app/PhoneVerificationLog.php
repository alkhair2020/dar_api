<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PhoneVerificationLog extends Model
{
    protected $table = "phone_verification_logs";
    protected $fillable = ['phone', 'sent_at'];
}
