<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookService extends Model
{
    protected $table = 'book_services';
    
    protected $fillable = [
        'id', 'title', 'description', 'price', 'status', 'created_at', 'updated_at'
    ];
}