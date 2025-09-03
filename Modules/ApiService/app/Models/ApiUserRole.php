<?php

namespace Modules\ApiService\Models;
use Illuminate\Database\Eloquent\Model;

class ApiUserRole extends Model
{
    protected $table = 'api_user_role';
    protected $fillable = ['role_id','user_id'];
}
