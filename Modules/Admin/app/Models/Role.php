<?php

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
class Role extends Model
{
    protected $table = 'admin_role';
    protected $fillable = ['name','description'];

    public function admins()
    {
        return $this->belongsToMany(Admin::class, 'admin_user_role', 'role_id', 'admin_id');
    }
    public function resources()
    {
        return $this->belongsToMany(
            Resource::class,
            'admin_role_resource',
            'role_id',
            'resource_id'
        );
    }
}
