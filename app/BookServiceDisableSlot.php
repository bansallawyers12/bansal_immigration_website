<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookServiceDisableSlot extends Model
{
    protected $fillable = [
        'id', 'book_service_slot_per_person_id', 'slots', 'disabledates', 'created_at', 'updated_at'
    ];
}
