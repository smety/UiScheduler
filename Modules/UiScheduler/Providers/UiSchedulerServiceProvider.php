<?php

namespace Modules\UiScheduler\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;
use Modules\UiScheduler\Console\JobRun;
use Modules\UiScheduler\Console\UiSchedulerRun;
use Modules\UiScheduler\Schedulers\LaravelSchedulerAdapter;

class UiSchedulerServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Register the console commands
        $this->commands([
            JobRun::class,
            UiSchedulerRun::class,
        ]);

        // Register LaravelSchedulerAdapter as a singleton in the service container
        $this->app->singleton(LaravelSchedulerAdapter::class, function ($app) {
            return new LaravelSchedulerAdapter();
        });
    }

    public function boot()
    {
        // Retrieve the LaravelSchedulerAdapter instance from the service container
        $adapter = app(LaravelSchedulerAdapter::class);

        // Schedule the jobs using the Laravel scheduler
        $this->app->booted(function () use ($adapter) {
            $schedule = $this->app->make(Schedule::class);
            $adapter->schedule($schedule);
        });

        // Publish the configuration file for the jobs
        $this->publishes([
            __DIR__.'/../Config/uischeduler_jobs.php' => config_path('uischeduler_jobs.php'),
        ], 'config');

        // Check if the published configuration file exists
        $publishedConfigPath = config_path('uischeduler_jobs.php');

        if (! file_exists($publishedConfigPath)) {
            throw new \RuntimeException("Config 'uischeduler_jobs.php' not found. Publish it -> docs");
        }

        // Merge the package configuration file with the application's configuration
        $this->mergeConfigFrom(__DIR__.'/../Config/uischeduler_config.php', 'uischeduler_config');
    }
}
