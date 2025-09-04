<?php

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
class AdminRole extends Model
{
    protected $table = 'admin_role';
    protected $fillable = ['name','description'];

    public function resources(){
        return $this->hasMany(AdminRoleResource::class,'role_id');
    }
}
