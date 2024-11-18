<?php

namespace App\Controllers;

use CodeIgniter\Controller; // Use CI4 controller class

class JobQueueController extends Controller
{
    // Load the model in the constructor
    public function __construct()
    {
        // You can load models here like in CI4
        $this->JobQueue_model = new \App\Models\JobQueueModel();
    }

    // Add a job to the queue
    public function add_job()
    {
        $job_data = array(
            'job_name' => 'send_email',
            'job_data' => json_encode(array('email' => 'ehteshamali1456@gmail.com', 'message' => 'Welcome to our service!'))
        );

        // Add job to queue using the model
        $this->JobQueue_model->add_to_queue($job_data);
        echo "Job added to queue.";
    }

    // Process the next job in the queue
    public function process_job()
    {
        $job = $this->JobQueue_model->get_pending_job();

        if ($job) {
            // Mark job as processing
            $this->JobQueue_model->update_job_status($job['id'], 'processing');

            // Here, you would implement your job logic (e.g., sending email)
            $this->process_send_email(json_decode($job['job_data'], true));

            // Mark job as completed
            $this->JobQueue_model->update_job_status($job['id'], 'completed');
            echo "Job processed successfully.";
        } else {
            echo "No pending jobs in the queue.";
        }
    }

    // Sample job logic (sending email)
    private function process_send_email($data)
    {
        // Implement your email sending logic here
        echo "Sending email to: " . $data['email'] . " with message: " . $data['message'];
    }
}
