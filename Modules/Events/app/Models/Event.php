<?php
namespace Modules\Events\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Events\Models\Event\Listener;

class Event extends Model
{
    protected $table = 'event';
    protected $fillable = ['name', 'code', 'description', 'status'];

    public function listeners()
    {
        return $this->hasMany(Listener::class,'event_id');
    }
}