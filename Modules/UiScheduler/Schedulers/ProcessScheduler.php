<?php

declare(strict_types=1);

namespace Modules\UiScheduler\Schedulers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Modules\UiScheduler\Mutexes\MutexAdapterInterface;
use Modules\UiScheduler\Mutexes\MutexFactory;

class ProcessScheduler
{
    protected MutexFactory $mutexFactory;

    public function __construct()
    {
        $this->mutexFactory = new MutexFactory();
    }

    public function loadJobs(): array
    {
        return config('uischeduler_jobs');
    }

    public function generateMutexKey(array $job): string
    {
        return sprintf('mutex_%s_%s', $job['command'], $job['frequency']);
    }

    public function prepareMutex(array $job): MutexAdapterInterface
    {
        $key = $this->generateMutexKey($job);
        $mutex = $this->mutexFactory->createMutex($key);
        $mutex->key = $key;
        $mutex->ttl = 120;

        return $mutex;
    }

    public static function processJobs($mutex, $job): void
    {
        // Check if is possible generate Mutex
        if ($mutex->acquire($mutex->key, $mutex->ttl)) {
            Log::info('-- Mutex Acquired: '.$job['command']);
            try {

                if ($job['type'] === 'command') {
                    Log::info('[QUEUE JOB RUN]');
                    Artisan::call($job['command']);

                }
            } finally {
                $mutex->release($mutex->key);
                Log::info('-- Mutex Released: '.$job['command']);
            }
        } else {
            Log::info('-- Mutex Locked - SKIP: '.$job['command']);
        }
    }
}
