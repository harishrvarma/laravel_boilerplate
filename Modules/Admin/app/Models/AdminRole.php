<?php

namespace Modules\Admin\Models;
use Illuminate\Database\Eloquent\Model;

class AdminRole extends Model
{
    protected $table = 'admin_user_role';
    protected $fillable = ['role_id','admin_id'];
}
