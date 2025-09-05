<?php

namespace Modules\Admin\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

class Admin extends Authenticatable
{
    protected $table = 'admin';
    protected $fillable = ['first_name','username','status','email','password','last_name'];

    protected $hidden = [
        'password',
    ];
 
    // Casts
    protected $casts = [
        'status' => 'boolean',
    ];
 

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::needsRehash($value) ? Hash::make($value) : $value;
    }
 
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function role()
    {
        return $this->belongsToMany(Role::class, 'admin_user_role', 'admin_id', 'role_id');
    }

    public function roles()
    {
        return $this->hasMany(AdminRole::class, 'admin_id',);
    }
 
    public function hasAccess($resourceCode)
    {
        foreach ($this->role as $role) {
            if ($role->resources->pluck('code')->contains($resourceCode)) {
                return true;
            }
        }
        return false;
    }
}
