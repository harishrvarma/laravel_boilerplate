<?php

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
class AdminResource extends Model
{
    protected $table = 'admin_resource';
    protected $fillable = ['code','name','route_name','level','parent_id','method','path_ids','label','uri'];
}
