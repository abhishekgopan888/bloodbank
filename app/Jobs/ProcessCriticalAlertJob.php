<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Bus\Batchable;
use App\Models\TemperatureLog;
use App\Models\Alert;
use Illuminate\Support\Carbon;
use App\Events\CriticalTemperatureEvent;

class ProcessCriticalAlertJob implements ShouldQueue
{
    use Queueable;
    use Batchable;

    public int $refrigeratorId = 0;

    /**
     * Create a new job instance.
     */
    public function __construct(int $refrigeratorId = 0)
    {
        $this->refrigeratorId = $refrigeratorId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (!$this->refrigeratorId) {
            return;
        }

        $since = Carbon::now()->subMinutes(30);
        $logs = TemperatureLog::where('refrigerator_id', $this->refrigeratorId)
            ->where('recorded_at', '>=', $since)
            ->orderBy('recorded_at')
            ->get();

        $continuous = 0;
        $threshold = 8.0;

        foreach ($logs as $log) {
            if ($log->temperature > $threshold) {
                $continuous++;
            } else {
                $continuous = 0;
            }

            if ($continuous >= 10) {
                $alert = Alert::create([
                    'refrigerator_id' => $this->refrigeratorId,
                    'type' => 'critical_temperature',
                    'message' => 'Refrigerator exceeded critical temperature for 10 consecutive minutes',
                    'metadata' => ['continuous_minutes' => $continuous],
                ]);

                event(new CriticalTemperatureEvent($alert));
                return;
            }
        }
    }
}
