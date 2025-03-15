<?php

namespace Modules\UiScheduler\Mutexes;

interface MutexAdapterInterface
{
    /**
     * Acquires a mutex.
     *
     * @param  string  $key  The key of the mutex.
     * @param  int  $ttl  The time-to-live of the mutex in seconds.
     * @return bool True on success, false on failure.
     */
    public function acquire(string $key, int $ttl): bool;

    /**
     * Releases a mutex.
     *
     * @param  string  $key  The key of the mutex.
     * @return bool True on success, false on failure.
     */
    public function release(string $key): bool;
}
