<?php

namespace Modules\Admin\Models;
use Illuminate\Database\Eloquent\Model;

class RoleResource extends Model
{
    protected $table = 'admin_role_resource';
    protected $fillable = ['role_id','resource_id'];
}
