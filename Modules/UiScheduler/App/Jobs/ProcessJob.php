<?php

declare(strict_types=1);

namespace Modules\UiScheduler\App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\UiScheduler\Mutexes\MutexAdapterInterface;
use Modules\UiScheduler\Schedulers\ProcessScheduler;

class ProcessJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        private MutexAdapterInterface $mutex,
        private array $queuejob
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        ProcessScheduler::processJobs($this->mutex, $this->queuejob);
    }
}
