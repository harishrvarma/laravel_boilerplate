<?php

namespace Modules\Cron\Console;

use Illuminate\Console\Command;
use Modules\Cron\Models\Cron;
use Modules\Cron\Models\Cron\Schedule;
use Cron\CronExpression;
use Carbon\Carbon;

class GenerateSchedules extends Command
{
    protected $signature = 'cron:generate-schedules';
    protected $description = 'Generate upcoming schedule entries for active crons based on interval and factor.';

    public function handle()
    {
        $now = Carbon::now('UTC'); // Current UTC time

        $intervalMinutes = 60;   // Your interval
        $intervalFactor  = 2;    // Your factor
        $frameTime       = $intervalMinutes * $intervalFactor; // Total horizon in minutes

        $crons = Cron::where('is_active', 1)->get();

        foreach ($crons as $cron) {
            try {
                $this->generateForCron($cron, $now, $intervalMinutes, $intervalFactor, $frameTime);
            } catch (\Exception $e) {
                $this->error("❌ Error processing [{$cron->name}]: " . $e->getMessage());
                continue;
            }
        }

        $this->info("✅ Schedule generation completed.");
    }

    protected function generateForCron(Cron $cron, Carbon $now, int $intervalMinutes, int $intervalFactor, int $frameTime)
    {
        try {
            $cronExpr = CronExpression::factory($cron->expression);
        } catch (\Exception $e) {
            $this->error("⚠️ Invalid cron expression for [{$cron->name}]: {$cron->expression}");
            return;
        }

        // Get last schedule
        $lastSchedule = Schedule::where('cron_id', $cron->cron_id)
            ->orderByDesc('scheduled_for')
            ->first();

        if (!$lastSchedule) {
            // First-time schedule — create 1 record
            $nextRun = Carbon::parse($cronExpr->getNextRunDate($now, 0, true), 'UTC');
            Schedule::create([
                'cron_id'       => $cron->cron_id,
                'scheduled_for' => $nextRun->copy()->setTimezone('UTC'),
                'status'        => 0,
            ]);
            $this->line("🆕 {$cron->name}: First schedule created at {$nextRun->format('Y-m-d H:i')}");
            return;
        }

        $lastScheduleTime = Carbon::parse($lastSchedule->scheduled_for, 'UTC');

        // Calculate total gap from last schedule to now
        $gapMinutes = max(0, $now->diffInMinutes($lastScheduleTime, false));

        // Remaining time in the frame horizon
        $remainingTime = $frameTime - $gapMinutes;

        if ($remainingTime <= 0) {
            $this->line("⏭ {$cron->name}: Already beyond frame horizon, skipping.");
            return;
        }

        // Determine cron frequency
        $cronInterval = $this->getCronIntervalMinutes($cron->expression);

        // Pending schedules to generate
        $pendingCount = (int) ceil($remainingTime / $cronInterval);
        if ($pendingCount <= 0) {
            $this->line("⚪ {$cron->name}: No pending schedules required.");
            return;
        }

        $this->line("📅 {$cron->name}: Generating {$pendingCount} new schedules from last schedule at {$lastScheduleTime->format('Y-m-d H:i')}");

        $nextRun = $cronExpr->getNextRunDate($lastScheduleTime, 0, true);
        $created = 0;

        for ($i = 0; $i < $pendingCount; $i++) {
            $nextRunTime = Carbon::parse($nextRun, 'UTC');

            // Avoid duplicates
            $exists = Schedule::where('cron_id', $cron->cron_id)
                ->where('scheduled_for', $nextRunTime->format('Y-m-d H:i:s'))
                ->exists();

            if (!$exists) {
                Schedule::create([
                    'cron_id'       => $cron->cron_id,
                    'scheduled_for' => $nextRunTime,
                    'status'        => 0,
                ]);
                $this->line("   ➕ Added schedule at {$nextRunTime->format('Y-m-d H:i')}");
                $created++;
            } else {
                $this->line("⚪ Schedule already exists at {$nextRunTime->format('Y-m-d H:i')}");
            }

            $nextRun = $cronExpr->getNextRunDate($nextRunTime, 0, false);
        }

        $this->info("🟢 {$cron->name}: {$created} new schedules created.");
    }

    /**
     * Calculate interval in minutes from cron expression
     */
    protected function getCronIntervalMinutes(string $expression): int
    {
        $cron = CronExpression::factory($expression);
        $now = Carbon::now('UTC');
        $next = $cron->getNextRunDate($now);
        return $now->diffInMinutes(Carbon::parse($next, 'UTC'));
    }
}
