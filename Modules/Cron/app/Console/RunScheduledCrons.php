<?php

namespace Modules\Cron\Console;

use Illuminate\Console\Command;
use Modules\Cron\Models\Cron;
use Modules\Cron\Models\Cron\Schedule;
use Artisan;
use Exception;
use Carbon\Carbon;

class RunScheduledCrons extends Command
{
    public $signature = 'cron:run-scheduled';
    public $description = 'Run all cron schedules whose scheduled_for time is due.';

    public function handle()
    {
        $now = Carbon::now('UTC');

        // Fetch all pending schedules due for execution
        $schedules = Schedule::where('status', 0)
            ->where('scheduled_for', '<=', $now)
            ->with('cron')
            ->orderBy('scheduled_for')
            ->get();

        if ($schedules->isEmpty()) {
            $this->info('⏸ No schedules due for execution.');
            return;
        }

        foreach ($schedules as $schedule) {
            $cron = $schedule->cron;
            if (!$cron || !$cron->is_active) {
                $this->warn("⚠️ Skipping schedule ID {$schedule->id}: Inactive or missing cron.");
                continue;
            }
        
            $this->line("▶️ Running cron '{$cron->name}' (Schedule ID: {$schedule->id}, scheduled_for: {$schedule->scheduled_for})");
        
            // ✅ Explicitly set started_at without re-saving same status
            $schedule->started_at = now();
            $schedule->save();
        
            try {
                $output = '';
        
                if ($cron->class && $cron->method) {
                    if (!class_exists($cron->class)) {
                        throw new Exception("Class {$cron->class} not found.");
                    }
        
                    $object = new $cron->class();
        
                    if (!method_exists($object, $cron->method)) {
                        throw new Exception("Method {$cron->method} not found in class {$cron->class}.");
                    }
        
                    $result = $object->{$cron->method}();
                    $output = is_string($result) ? $result : json_encode($result);
        
                } elseif ($cron->command) {
                    Artisan::call($cron->command);
                    $output = Artisan::output();
                } else {
                    throw new Exception("No valid class-method or command defined for cron '{$cron->name}'.");
                }
        
                // ✅ Mark success
                $schedule->update([
                    'finished_at' => now(),
                    'status' => 1, // Success
                    'log' => $output,
                ]);
        
                $cron->update(['last_run_at' => now()]);
        
                $this->info("✅ Cron '{$cron->name}' executed successfully.");
        
            } catch (Exception $e) {
                // ✅ Mark failed
                $schedule->update([
                    'finished_at' => now(),
                    'status' => 2, // Failure
                    'log' => $e->getMessage(),
                ]);
        
                $this->error("❌ Cron '{$cron->name}' failed: {$e->getMessage()}");
            }
        }
        

        $this->info('🏁 All due schedules processed.');
    }
}
