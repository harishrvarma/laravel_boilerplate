<?php

namespace Modules\Admin\Models\Role;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $table = 'admin_role_resource';
    protected $fillable = ['role_id','resource_id'];
}
