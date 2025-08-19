<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivitiesLog extends Model
{
    protected $fillable = [
        'id', 'client_id', 'created_by', 'subject', 'description', 'created_at', 'updated_at'
    ];
}
