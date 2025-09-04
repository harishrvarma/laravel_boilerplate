<?php

namespace Modules\ApiService\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class ApiUser extends Authenticatable
{
    // use HasApiTokens;

    protected $table = 'api_user';
    protected $fillable = ['name','email','password','status',];

    public function role(){
        return $this->hasMany(ApiUserRole::class,'user_id');
    }

    public function roles()
    {
        return $this->belongsToMany(ApiRole::class, 'api_user_role', 'user_id', 'role_id');
    }

    public function resources()
    {
        return $this->roles()
                    ->join('api_role_resource', 'api_role.id', '=', 'api_role_resource.role_id')
                    ->join('api_resource', 'api_role_resource.resource_id', '=', 'api_resource.id')
                    ->select('api_resource.*');
    }
}
