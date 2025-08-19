<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use Notifiable;
	use Sortable;

    protected $fillable = [
        'id', 'client_id', 'user_id', 'title', 'description', 'mail_id', 'type', 'created_at', 'updated_at'
    ];
   
	public $sortable = ['id', 'created_at', 'updated_at','task_group','followup_date'];
	
	
    public function noteClient()
    {
        return $this->belongsTo('App\Admin','client_id','id');
    }

    public function noteUser()
    {
        return $this->belongsTo('App\Admin','user_id','id');
    }

    public function assigned_user()
    {
        return $this->belongsTo('App\Admin','assigned_to','id');
    }

    public function lead()
    {
        return $this->belongsTo('App\Appointment','lead_id','id');
    }
	
}
