<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VerifiedNumber extends Model
{
    protected $fillable = [
        'id', 'phone_number', 'is_verified', 'created_at', 'updated_at'
    ];
}
