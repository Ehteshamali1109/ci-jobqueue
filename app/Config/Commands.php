<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Commands extends BaseConfig
{
    public $commands = [
        'jobqueue:process' => \App\Commands\ProcessJobCommand::class, // Register your command
    ];
}
