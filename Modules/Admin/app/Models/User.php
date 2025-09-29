<?php

namespace Modules\Admin\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Modules\Admin\Models\Role; // use the Role model directly

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

    // Password hashing
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::needsRehash($value) ? Hash::make($value) : $value;
    }

    // Full name accessor
    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    // ✅ Relationship: User ↔ Roles (many-to-many)
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'admin_user_role', 'user_id', 'role_id');
    }

    public function role()
    {
        return $this->belongsToMany(Role::class, 'admin_user_role', 'user_id', 'role_id');
    }

    // ✅ Direct access to resources via roles
    public function resources()
    {
        return $this->roles()
            ->with('resources')
            ->get()
            ->pluck('resources')
            ->flatten()
            ->unique('id');
    }

    // ✅ Check if user has access to a resource by its code
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
