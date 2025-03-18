<?php

declare(strict_types=1);

namespace Modules\UiScheduler\Schedulers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Modules\UiScheduler\App\Jobs\ProcessJob;

class LaravelSchedulerAdapter implements SchedulerAdapterInterface
{
    protected ProcessScheduler $processScheduler;

    /**
     * Constructor to initialize ProcessScheduler.
     */
    public function __construct()
    {
        $this->processScheduler = new ProcessScheduler();
    }

    /**
     * Schedule the jobs using Laravel's Schedule.
     */
    public function schedule($schedule = null): ?Schedule
    {
        // Go through defined jobs
        foreach ($this->processScheduler->loadJobs() as $job) {
            $this->scheduleJob($schedule, $job);
        }

        return $schedule;
    }

    /**
     * Schedule a single job.
     */
    public function scheduleJob($schedule, array $job): void
    {
        // Prepare Mutex with key
        $mutex = $this->processScheduler->prepareMutex($job);

        // Schedule the job with the defined frequency and description
        $schedule->call(function () use ($mutex, $job) {
            // Dispatch the job to the queue
            ProcessJob::dispatch($mutex, $job);
            //   ProcessScheduler::processJobs($mutex, $job);
            Log::info($job['command'].' job processed to queue.');
        })->cron($job['frequency'])
            ->description($job['description']);
    }

    /**
     * Run the Laravel Scheduler.
     */
    public static function run(): void
    {
        // Run Laravel Scheduler
        Artisan::call('schedule:run');
    }
}
