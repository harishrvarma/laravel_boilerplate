<?php
namespace Modules\Cron\Models\Cron;

use Illuminate\Database\Eloquent\Model;
use Modules\Cron\Models\Cron;
class Log extends Model
{
    protected $table = 'cron_log'; 
    protected $fillable = [
        'cron_id', 'status', 'message', 'started_at', 'finished_at'
    ];

    public function schedule()
    {
        return $this->belongsTo(Cron::class, 'cron_id');
    }
}