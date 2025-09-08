<?php

namespace Modules\ApiService\Models;
use Illuminate\Database\Eloquent\Model;

class ApiResource extends Model
{
    protected $table = 'api_resource';
    protected $fillable = ['code','name','route_name','level','parent_id','method','path_ids','label','uri'];

}
