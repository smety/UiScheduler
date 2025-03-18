<?php

declare(strict_types=1);

namespace Modules\UiScheduler\Console;

use Illuminate\Console\Command;
use Modules\UiScheduler\Schedulers\SchedulerFactory;

class UiSchedulerRun extends Command
{
    protected $signature = 'uischeduler:run';

    public function __construct(
        private SchedulerFactory $schedulerFactory
    ) {
        parent::__construct();
    }

    public function handle(): void
    {
        $this->schedulerFactory->run($this->getLaravel());
    }
}
