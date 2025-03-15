<?php

/*
|--------------------------------------------------------------------------
| Job Configuration
|--------------------------------------------------------------------------
|
| This file is for defining the jobs and commands that will be scheduled
| by the application. You can add new jobs or commands by copying the
| pattern below and modifying it as needed.
|
| Example:
| [
|     'type' => 'command', // command/exec
|     'command' => 'job:run LogMessage', // job:run {jobName}/shell command
|     'frequency' => '* * * * *', // Cron format
|     'description' => 'Run the LogMessage job'
| ],
|
*/

return [
    [
        'type' => 'command',
        'command' => 'job:run LogMessage',
        'frequency' => '* * * * *',
        'description' => 'LogMessage',
    ],
    [
        'type' => 'command',
        'command' => 'job:run LogMessageSleep',
        'frequency' => '* * * * *',
        'description' => 'LogMessageSleep',
    ],
    [
        'type' => 'command',
        'command' => 'job:run LogMessage',
        'frequency' => '*/2 * * * *',
        'description' => 'LogMessage',
    ],
    [
        'type' => 'command',
        'command' => 'job:run LogMessage',
        'frequency' => '*/3 * * * *',
        'description' => 'LogMessage',
    ],

];
