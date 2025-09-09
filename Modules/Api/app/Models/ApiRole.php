<?php

namespace Modules\Api\Models;
use Illuminate\Database\Eloquent\Model;

class ApiRole extends Model
{
    protected $table = 'api_role';
    protected $fillable = ['name','description'];

    public function resources()
    {
        return $this->belongsToMany(
            ApiResource::class,
            'api_role_resource',
            'role_id',
            'resource_id'
        );
    }
    
}
