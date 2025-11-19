<?php

namespace Modules\Admin\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Modules\Admin\Models\Role;

class User extends Authenticatable
{
    protected $table = 'admin_user';

    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'status',
        'email',
        'password'
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::needsRehash($value) ? Hash::make($value) : $value;
    }

    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'admin_user_role', 'user_id', 'role_id');
    }

    public function role()
    {
        return $this->belongsToMany(Role::class, 'admin_user_role', 'user_id', 'role_id');
    }

    public function resources()
    {
        return $this->roles()
            ->with('resources')
            ->get()
            ->pluck('resources')
            ->flatten()
            ->unique('id');
    }

    public function hasAccess(string $resourceCode): bool
    {
        return $this->allResources()->contains(function ($resource) use ($resourceCode) {
            return $resource->code === $resourceCode;
        });
    }
    

    public function allResources()
    {
        return $this->roles()
            ->with('resources')
            ->get()
            ->pluck('resources')
            ->flatten()
            ->unique('id');
    }
    

}
