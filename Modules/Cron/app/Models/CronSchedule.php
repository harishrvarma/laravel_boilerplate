<?php
namespace Modules\Cron\Models;

use Illuminate\Database\Eloquent\Model;

class CronSchedule extends Model
{
    protected $fillable = [
        'name', 'command', 'expression', 'is_active', 'last_run_at'
    ];

    public function logs()
    {
        return $this->hasMany(CronLog::class);
    }
}