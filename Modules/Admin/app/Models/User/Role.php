<?php

namespace Modules\Admin\Models\User;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'admin_user_role';
    protected $fillable = ['role_id','user_id'];
}
