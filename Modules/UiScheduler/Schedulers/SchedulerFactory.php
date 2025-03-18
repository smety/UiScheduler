<?php

namespace Modules\UiScheduler\Schedulers;

use Illuminate\Support\Facades\Log;

class SchedulerFactory
{
    /**
     * Run the scheduler based on the configured scheduler type.
     *
     * @return void
     */
    public static function run(): void
    {
        // Get the scheduler type from the configuration
        $schedulerType = self::getSchedulerType();
        $mutexType = self::getMutexType();

        // Log the start of the scheduler run
        Log::info('----------------------------------------------------------------------------------------------');
        Log::info($schedulerType.' scheduler - START.');
        Log::info($mutexType.' mutex.');

        // Execute the appropriate scheduler based on the type
        switch ($schedulerType) {
            case 'laravel':
                LaravelSchedulerAdapter::run();
                break;
            case 'crunz':
                CrunzSchedulerAdapter::run();
                break;
            default:
                // Log an error if the scheduler type is unsupported
                Log::error('Unsupported scheduler type.');

                return;
        }

        // Log the completion of the scheduler run
        Log::info($schedulerType.' scheduler - DONE.');
        Log::info('----------------------------------------------------------------------------------------------');
    }

    /**
     * Get the scheduler type from the configuration.
     *
     * @return string
     */
    public static function getSchedulerType(): string
    {
        return config('uischeduler_config.scheduler');
    }

    public static function getMutexType(): string
    {
        return config('uischeduler_config.mutex');
    }
}
