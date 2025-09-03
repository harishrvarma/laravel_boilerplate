<?php

namespace Modules\ApiService\Models;
use Illuminate\Database\Eloquent\Model;

class ApiRole extends Model
{
    protected $table = 'api_role';
    protected $fillable = ['name','description'];

    public function resource(){
        return $this->hasMany(ApiRoleResource::class,'role_id');
    }
}
