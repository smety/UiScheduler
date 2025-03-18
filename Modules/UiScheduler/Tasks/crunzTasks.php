<?php

namespace Modules\UiScheduler\Tasks;

use Crunz\Schedule;
use Modules\UiScheduler\Schedulers\CrunzSchedulerAdapter;

// Initialize Laravel components for Crunz
CrunzSchedulerAdapter::initializationLaravel(); // Laravel Initialization - Facades, Artisan, Config, etc.

// Create a new Crunz scheduler adapter instance
$adapter = new CrunzSchedulerAdapter();

// Get the schedule instance from the adapter
$schedule = $adapter->schedule();

// Return the configured schedule
return $schedule;
