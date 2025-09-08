<?php

namespace Modules\ApiService\Models;
use Illuminate\Database\Eloquent\Model;

class ApiResource extends Model
{
    protected $table = 'api_resource';
    protected $fillable = ['code','description'];
}
