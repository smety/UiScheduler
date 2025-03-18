<?php

declare(strict_types=1);

namespace Modules\UiScheduler\Mutexes;

use Illuminate\Support\Facades\Cache;

class FileMutexAdapter implements MutexAdapterInterface
{
    private Cache $lock;

    public function acquire($key, $ttl): bool
    {
        // Try to acquire the lock with the given key and TTL
        $this->key = $key;
        $lock = Cache::lock($key, $ttl);
        $this->lock = $lock;

        return $lock->get();
    }

    /**
     * Release the cache lock.
     */
    public function release($key): bool
    {
        // Release the lock with the given key
        $lock = $this->lock;

        return $lock->release();
    }

    /**
     * Check if the cache lock exists.
     */
    public function exists(): bool
    {
        // Check if the lock exists with the given key
        $lock = Cache::lock($this->key);

        return $lock->exists();
    }
}
