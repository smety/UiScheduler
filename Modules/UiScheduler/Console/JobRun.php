<?php

declare(strict_types=1);

namespace Modules\UiScheduler\Console;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class JobRun extends Command
{
    protected $signature = 'job:run {jobName}';

    /**
     * @throws Exception
     */
    public function handle(): void
    {
        $jobName = $this->argument('jobName');

        // Name of Job with path
        $jobClass = sprintf("App\Jobs\%s", $jobName);

        // Check class exists
        if (class_exists($jobClass)) {
            try {
                $jobInstance = app($jobClass);
                $jobInstance->handle();
                Log::info(sprintf("Job %s executed successfully.", $jobName));
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        } else {
            throw new Exception(sprintf("Job %s was not found.", $jobName));
        }
    }
}
