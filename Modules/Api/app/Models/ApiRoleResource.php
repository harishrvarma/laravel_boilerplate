<?php

namespace Modules\Api\Models;
use Illuminate\Database\Eloquent\Model;

class ApiRoleResource extends Model
{
    protected $table = 'api_role_resource';
    protected $fillable = ['role_id','resource_id'];

}
