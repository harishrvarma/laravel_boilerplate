<?php
namespace Modules\Cron\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Cron\Models\Cron\Log;

class Cron extends Model
{
    protected $table = 'cron';
    protected $fillable = [
        'name', 'command', 'expression', 'is_active', 'last_run_at'
    ];

    public function logs()
    {
        return $this->hasMany(Log::class);
    }
}