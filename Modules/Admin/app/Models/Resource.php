<?php

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
class Resource extends Model
{
    protected $table = 'admin_resource';
    protected $fillable = ['code','name','route_name','level','parent_id','method','path_ids','label','uri'];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'admin_role_resource', 'resource_id', 'role_id');
    }
}
