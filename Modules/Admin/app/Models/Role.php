<?php

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
class Role extends Model
{
    protected $table = 'admin_role';
    protected $fillable = ['name'];
}
