<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Partner extends Authenticatable {
    use Notifiable;
	use Sortable;

	protected $fillable = ['id', 'first_name', 'last_name', 'email', 'phone', 'password', 'status', 'created_at', 'updated_at'];
	
	public $sortable = ['id', 'first_name', 'last_name', 'email', 'created_at', 'updated_at'];
}
