<?php

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'admin_role';

    protected $fillable = [
        'name',
        'description',
    ];

    // ✅ Relationship: Role ↔ Users
    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'admin_user_role', // pivot table
            'role_id',         // foreign key on pivot for Role
            'user_id'          // foreign key on pivot for User
        );
    }

    // ✅ Relationship: Role ↔ Resources
    public function resources()
    {
        return $this->belongsToMany(
            Resource::class,
            'admin_role_resource', // pivot table
            'role_id',             // foreign key on pivot for Role
            'resource_id'          // foreign key on pivot for Resource
        );
    }
}
