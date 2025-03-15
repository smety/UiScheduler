<?php

declare(strict_types=1);

namespace Modules\UiScheduler\Mutexes;

use Illuminate\Support\Facades\Log;

class MutexFactory
{
    /**
     * Create a mutex adapter based on the configured mutex type.
     *
     * @param  string  $key  The key for the mutex.
     * @return MutexAdapterInterface The created mutex adapter.
     */
    public function createMutex(string $key): MutexAdapterInterface
    {
        $mutexType = $this->getMutexType();

        switch ($mutexType) {
            case 'redis':
                return new RedisMutexAdapter($key); // Redis mutex
            case 'file':
                return new FileMutexAdapter($key); // Custom mutex
            default:
                Log::error('Unsupported mutex type, laravel mutex selected.');

                return new FileMutexAdapter($key);
        }
    }

    protected function getMutexType(): string
    {
        $mutexType = config('uischeduler_config.mutex');

        if (! $mutexType) {
            Log::error('Mutex type not found in configuration.');

            return 'file';
        }

        return $mutexType;
    }
}
