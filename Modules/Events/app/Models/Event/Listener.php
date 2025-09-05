<?php
namespace Modules\Events\Models\Event;

use Illuminate\Database\Eloquent\Model;

class Listener extends Model
{
    protected $table = 'event_listener';
    protected $fillable = ['event_id', 'name', 'component', 'method', 'order_no', 'status'];

    // public function event()
    // {
    //     return $this->belongsTo(Event::class);
    // }
}