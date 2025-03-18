<?php

namespace Modules\UiScheduler\Mutexes;

use Predis\Client;

/**
 * RedisMutexAdapter is responsible for handling Redis-based locks.
 */
class RedisMutexAdapter implements MutexAdapterInterface
{
    private $client;

    private $lock;

    /**
     * Constructor initializes the Redis client with configuration settings.
     */
    public function __construct()
    {
        $this->client = new Client([
            'scheme' => config('uischeduler_config.redis.scheme'),
            'host' => config('uischeduler_config.redis.host'),
            'port' => config('uischeduler_config.redis.port'),
        ]);
    }

    /**
     * Acquire a Redis lock.
     *
     * @param  string  $key  Lock key
     * @param  int  $ttl  Time-to-live in seconds
     */
    public function acquire($key, $ttl): bool
    {
        // Attempt to set a lock with the given key and TTL
        $lock = $this->client->set($key, 'locked', 'EX', $ttl, 'NX');
        $this->lock = $lock ? $key : null;

        return (bool) $lock;
    }

    /**
     * Release the Redis lock.
     *
     * @param  string  $key  Lock key
     */
    public function release($key): bool
    {
        // Check if the lock is currently held
        if ($this->lock) {
            $lock = $this->lock;
            $result = $this->client->del([$lock]);
            $this->lock = null;

            return (bool) $result;
        }

        return false;
    }

    /**
     * Check if the Redis lock exists.
     *
     * @param  string  $key  Lock key
     */
    public function exists($key): bool
    {
        // Check if the lock key exists in Redis
        $exists = $this->client->exists($key);

        return (bool) $exists;
    }
}
