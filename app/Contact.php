<?php
namespace App;

use Illuminate\Notifications\Notifiable;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Contact extends Authenticatable
{
    use Notifiable;
	use Sortable; 
	
    /** 
     * The attributes that are mass assignable.
     *
     * @var array
     */
	
	 
	protected $fillable = [
        'id', 'name', 'srname', 'first_name', 'middle_name', 'last_name', 'company_name', 'contact_display_name', 'contact_email', 'contact_phone', 'work_phone', 'website', 'designation', 'department', 'skype_name', 'facebook_name', 'twitter_name', 'linkedin_name', 'instagram_name', 'youtube_name', 'country', 'address', 'city', 'zipcode', 'phone', 'subject', 'message', 'status', 'forwarded_to', 'forwarded_at', 'form_source', 'form_variant', 'ip_address', 'created_at', 'updated_at'
    ]; 
  
	public $sortable = ['id', 'name', 'contact_email', 'subject', 'status', 'created_at', 'updated_at'];

    /**
     * Accessor for email attribute to map to contact_email
     * This provides compatibility with the unified form system
     */
    public function getEmailAttribute()
    {
        return $this->contact_email;
    }
 
	 public function currencydata() 
    {
        return $this->belongsTo('App\Currency','currency','id');
    }
	
	public function company()
    {
        return $this->belongsTo('App\Admin','user_id','id');
    }
	
	/*public function desmedia() 
    {
        return $this->belongsTo('App\MediaImage','dest_image','id');
    }
	
	public function mypackage() 
    {
        return $this->hasMany('App\Package','destination','id');
    } */
} 
