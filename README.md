# CodeIgniter Job Queue Example

This project demonstrates how to create a job queue system in CodeIgniter 4.5.5, allowing you to add jobs to a queue and process them using a custom CLI command.

## Table of Contents
- [Requirements](#requirements)
- [Installation](#installation)
- [Setup](#setup)
  - [Creating the Database Table](#creating-the-database-table)
  - [Creating the Job Model](#creating-the-job-model)
  - [Creating a Controller to Add Jobs](#creating-a-controller-to-add-jobs)
  - [Creating the Console Command](#creating-the-console-command)
  - [Using the Job Queue](#using-the-job-queue)

## Requirements
- CodeIgniter 4.5.5
- PHP 7.4 or later
- MySQL (or compatible database)

## Installation
1. Clone the repository (or create a new CodeIgniter project):

    ```bash
    git clone https://github.com/yourusername/ci-jobqueue.git
    cd ci-jobqueue
    ```

2. Install dependencies:

    ```bash
    composer install
    ```

3. Set up the environment file:

    ```bash
    cp env .env
    ```

4. Edit the `.env` file to set your database credentials and environment settings:

    ```
    CI_ENVIRONMENT = development
    app.baseURL = 'http://localhost/ci-jobqueue'

    database.default.hostname = localhost
    database.default.database = ci_jobqueue
    database.default.username = root
    database.default.password = your_password
    database.default.DBDriver = MySQLi
    ```

## Setup
To manage jobs in the queue, you’ll need to create a database table, a model for interacting with the database, a controller to add jobs, and a custom CLI command to process jobs.

### Creating the Database Table
Create a `job_queue` table in your database to store job data and statuses. You can execute the following SQL command in your database:

```sql
CREATE TABLE job_queue (
    id INT AUTO_INCREMENT PRIMARY KEY,
    job_name VARCHAR(255) NOT NULL,
    job_data TEXT,
    status ENUM('pending', 'processing', 'completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
This table stores:

job_name: the name/type of the job.
job_data: JSON-encoded job data.
status: the job's status (pending, processing, or completed).
created_at and updated_at: timestamps for tracking job creation and updates.
2. Creating the Job Model
Create a model, JobQueueModel, to interact with the job_queue table.

Create a file named JobQueueModel.php in app/Models/ with the following content:

php
Copy code
<?php

namespace App\Models;

use CodeIgniter\Model;

class JobQueueModel extends Model
{
    protected $table = 'job_queue';
    protected $primaryKey = 'id';
    protected $allowedFields = ['job_name', 'job_data', 'status'];

    public function add_to_queue($job_data)
    {
        $job_data['status'] = 'pending';
        return $this->insert($job_data);
    }

    public function get_pending_job()
    {
        return $this->where('status', 'pending')
                    ->orderBy('id', 'ASC')
                    ->first();
    }

    public function update_job_status($job_id, $status)
    {
        return $this->update($job_id, ['status' => $status]);
    }
}
3. Creating a Controller to Add Jobs
Create a controller, JobQueueController, to add jobs to the queue.

Create a file named JobQueueController.php in app/Controllers/ with the following content:

php
Copy code
<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\JobQueueModel;

class JobQueueController extends Controller
{
    public function add_job()
    {
        $jobModel = new JobQueueModel();

        $job_data = [
            'job_name' => 'send_email',
            'job_data' => json_encode(['email' => 'test@example.com', 'message' => 'Welcome to our service!'])
        ];

        $jobModel->add_to_queue($job_data);
        echo "Job added to queue.";
    }
}
Define a route for adding a job in app/Config/Routes.php:

php
Copy code
$routes->get('jobqueue/add', 'JobQueueController::add_job');
Now, you can add a job to the queue by visiting http://localhost/ci-jobqueue/jobqueue/add.

4. Creating the Console Command
Create a custom command to process jobs in the queue.

Create a file named ProcessJobQueue.php in app/Commands/:

php
Copy code
<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\JobQueueModel;

class ProcessJobQueue extends BaseCommand
{
    protected $group       = 'JobQueue';
    protected $name        = 'jobqueue:process';
    protected $description = 'Processes the next job in the queue.';

    public function run(array $params)
    {
        $jobModel = new JobQueueModel();
        $job = $jobModel->get_pending_job();

        if ($job) {
            CLI::write("Processing job: " . $job['job_name'], 'green');

            $jobModel->update_job_status($job['id'], 'processing');

            $this->process_send_email(json_decode($job['job_data'], true));

            $jobModel->update_job_status($job['id'], 'completed');
            CLI::write("Job processed successfully.", 'green');
        } else {
            CLI::write("No pending jobs in the queue.", 'yellow');
        }
    }

    private function process_send_email($data)
    {
        CLI::write("Sending email to: " . $data['email'] . " with message: " . $data['message']);
    }
}
Register the command in app/Config/Commands.php:

php
Copy code
$commands['jobqueue:process'] = App\Commands\ProcessJobQueue::class;
5. Using the Job Queue
To add a job: Visit the following URL in your browser to add a job to the queue:

arduino
Copy code
http://localhost/ci-jobqueue/jobqueue/add
To process a job: Run the following command in the terminal:

bash
Copy code
php spark jobqueue:process
This command checks for pending jobs, processes the first pending job, and updates the job’s status to completed.

Conclusion
This guide provides a basic job queue setup in CodeIgniter, allowing you to queue and process jobs via the command line. You can expand this system to handle different job types and more complex workflows.







