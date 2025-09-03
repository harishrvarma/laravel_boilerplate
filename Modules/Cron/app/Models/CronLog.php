<?php
namespace Modules\Cron\Models;

use Illuminate\Database\Eloquent\Model;

class CronLog extends Model
{
    protected $fillable = [
        'cron_schedule_id', 'status', 'message', 'started_at', 'finished_at'
    ];

    public function schedule()
    {
        return $this->belongsTo(CronSchedule::class, 'cron_schedule_id');
    }
}