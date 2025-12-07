<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{

    protected $fillable = ['id_card_number', 'name', 'phone', 'birth_date'];
}