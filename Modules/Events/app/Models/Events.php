<?php
namespace Modules\Events\Models;

use Illuminate\Database\Eloquent\Model;

class Events extends Model
{
    protected $fillable = ['name', 'code', 'description', 'status'];

    public function listeners()
    {
        return $this->hasMany(Listener::class,'event_id');
    }
}