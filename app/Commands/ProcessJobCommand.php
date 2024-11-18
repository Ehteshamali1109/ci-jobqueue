<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Controllers\JobQueueController;

class ProcessJobCommand extends BaseCommand
{
    protected $group       = 'Custom';
    protected $name        = 'jobqueue:process'; // The command to run in CLI
    protected $description = 'Process the next job in the job queue';

    public function run(array $params = [])
    {
        // You can call the controller method from here
        $controller = new JobQueueController();
        $controller->process_job(); // Make sure this method exists in the controller
    }
}
