<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ServiceAccount extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'service_name',
        'description',
        'admin_id',
        'token',
        'is_active',
        'last_used_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_used_at' => 'datetime'
    ];

    protected $hidden = [
        'token'
    ];

    /**
     * Get the admin that owns the service account
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    /**
     * Scope to get only active service accounts
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Generate a new token
     */
    public function generateNewToken()
    {
        $this->update([
            'token' => \Illuminate\Support\Str::random(64),
            'last_used_at' => now()
        ]);
        
        return $this->token;
    }
} 