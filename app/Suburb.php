<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Suburb extends Authenticatable
{
    protected $fillable = [
        'postcode',
        'suburb',
        'state'
    ];
} 