<?php
namespace Modules\Events\Models;

use Illuminate\Database\Eloquent\Model;

class Listener extends Model
{
    protected $fillable = ['event_id', 'name', 'component', 'method', 'order_no', 'status'];

    // public function event()
    // {
    //     return $this->belongsTo(Event::class);
    // }
}