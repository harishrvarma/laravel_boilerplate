<?php
namespace Modules\Cron\Models\Cron;

use Illuminate\Database\Eloquent\Model;
use Modules\Cron\Models\Cron;

class Schedule extends Model
{
    // Updated table name
    protected $table = 'cron_schedules';

    // Updated PK
    protected $primaryKey = 'schedule_id';

    // Mass assignable fields
    protected $fillable = [
        'cron_id',
        'scheduled_for',
        'status',
        'log',
        'started_at',
        'finished_at'
    ];

    // Cast timestamps
    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    /**
     * Relation: Schedule belongs to a Cron
     */
    public function cron()
    {
        return $this->belongsTo(Cron::class, 'cron_id', 'cron_id');
    }
}
