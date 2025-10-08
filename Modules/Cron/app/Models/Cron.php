<?php
namespace Modules\Cron\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Cron\Models\Cron\Schedule;

class Cron extends Model
{
    // Updated table name
    protected $table = 'crons';

    // Updated PK
    protected $primaryKey = 'cron_id';

    // Mass assignable fields
    protected $fillable = [
        'name',
        'command',
        'expression',
        'class',
        'method',
        'frequency',
        'is_active',
        'last_run_at',
    ];

    // Cast timestamps
    protected $casts = [
        'is_active' => 'boolean',
        'last_run_at' => 'datetime',
    ];

    /**
     * Relation: Cron has many schedules/logs
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'cron_id', 'cron_id');
    }
}
