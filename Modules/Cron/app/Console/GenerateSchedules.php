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
    protected $description = 'Generate upcoming schedule entries for active crons based on time gap and interval factor.';

    public function handle()
    {
        $now = Carbon::now('UTC')->seconds(0); // round seconds to avoid duplicates

        $intervalMinutes = 60; // base interval (minutes)
        $intervalFactor  = 2;  // horizon factor multiplier
        $frameTime       = $intervalMinutes * $intervalFactor; // total look-ahead window in minutes

        $crons = Cron::where('is_active', 1)->get();

        foreach ($crons as $cron) {
            try {
                $this->generateForCron($cron, $now, $intervalMinutes, $intervalFactor, $frameTime);
            } catch (\Exception $e) {
                $this->error("❌ Error processing [{$cron->name}]: " . $e->getMessage());
                continue; // don't stop for one failed cron
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

        // Get frequency of cron expression in minutes
        $cronInterval = $this->getCronIntervalMinutes($cron->expression);

        // Get latest scheduled entry
        $lastSchedule = Schedule::where('cron_id', $cron->cron_id)
            ->orderByDesc('scheduled_for')
            ->first();

        if ($lastSchedule) {
            $lastScheduleTime = Carbon::parse($lastSchedule->scheduled_for)->seconds(0);
            $totalGapMinutes = $lastScheduleTime->diffInMinutes($now);
        } else {
            $lastScheduleTime = $now->copy();
            $totalGapMinutes = 0;
        }

        // Remaining time before we reach horizon
        $remainingTime = max(0, $frameTime - $totalGapMinutes);

        $this->line("🕒 {$cron->name}: last={$lastScheduleTime->format('H:i')} | frame={$frameTime}m | gap={$totalGapMinutes}m | remaining={$remainingTime}m");

        if ($remainingTime <= 0) {
            $this->line("⏭ {$cron->name}: already beyond horizon, skipping.");
            return;
        }

        // Calculate how many new schedules to add
        $scheduleCount = (int) floor($remainingTime / $cronInterval);

        if ($scheduleCount <= 0) {
            $this->line("⚪ {$cron->name}: no new schedules needed (remaining < cron interval).");
            return;
        }

        $this->line("📅 {$cron->name}: will generate {$scheduleCount} new schedules (every {$cronInterval}m)");

        $nextRun = $cronExpr->getNextRunDate($lastScheduleTime, 0, true);
        $created = 0;

        for ($i = 0; $i < $scheduleCount; $i++) {
            $nextRunTime = Carbon::parse($nextRun)->seconds(0);

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
                $this->line("⚪ Exists {$nextRunTime->format('Y-m-d H:i')}");
            }

            // get next run from the current nextRunTime
            $nextRun = $cronExpr->getNextRunDate($nextRunTime, 0, false);
        }

        $this->info("🟢 {$cron->name}: {$created} new schedules created.");
    }

    /**
     * Get the interval in minutes for a cron expression.
     */
    protected function getCronIntervalMinutes(string $expression): int
    {
        $cron = CronExpression::factory($expression);
        $now = Carbon::now('UTC')->seconds(0);
        $next = $cron->getNextRunDate($now);
        return $now->diffInMinutes(Carbon::parse($next));
    }
}
