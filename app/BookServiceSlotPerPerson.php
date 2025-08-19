<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookServiceSlotPerPerson extends Model
{
    protected $fillable = [
        'id', 'book_service_id', 'slots', 'created_at', 'updated_at'
    ];
}
