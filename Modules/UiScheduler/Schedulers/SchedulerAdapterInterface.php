<?php

namespace Modules\UiScheduler\Schedulers;

use Illuminate\Console\Scheduling\Schedule;

interface SchedulerAdapterInterface
{
    /**
     * Schedules tasks according to defined jobs.
     *
     * @param  mixed  $schedule  Schedule object (can be Crunz\Schedule or Illuminate\Console\Scheduling\Schedule).
     * @return mixed Returns Schedule or null.
     */
    public function schedule($schedule = null);

    /**
     * Runs the scheduler.
     *
     * @param  mixed  $schedule
     */
    public function scheduleJob($schedule, array $job): void;

    /**
     * Runs the scheduler.
     */
    public static function run(): void;
}
