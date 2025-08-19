<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppointmentLog extends Model
{
    protected $fillable = [
        'id', 'appointment_id', 'action', 'description', 'created_at', 'updated_at'
    ];
}
