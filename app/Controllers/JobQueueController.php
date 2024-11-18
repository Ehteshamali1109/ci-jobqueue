<?php
class JobQueueController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('JobQueue_model');
    }

    // Add a job to the queue
    public function add_job() {
        $job_data = array(
            'job_name' => 'send_email',
            'job_data' => array('email' => 'test@example.com', 'message' => 'Welcome to our service!')
        );
        
        $this->JobQueue_model->add_to_queue($job_data);
        echo "Job added to queue.";
    }

    // Process the next job in the queue
    public function process_job() {
        $job = $this->JobQueue_model->get_pending_job();
        
        if ($job) {
            // Mark job as processing
            $this->JobQueue_model->update_job_status($job->id, 'processing');
            
            // Here, you would implement your job logic (e.g., sending email)
            $this->process_send_email(json_decode($job->job_data, true));

            // Mark job as completed
            $this->JobQueue_model->update_job_status($job->id, 'completed');
            echo "Job processed successfully.";
        } else {
            echo "No pending jobs in the queue.";
        }
    }

    // Sample job logic (sending email)
    private function process_send_email($data) {
        // Here you would add your email sending logic (e.g., using PHPMailer or CodeIgniter's email class)
        echo "Sending email to: " . $data['email'] . " with message: " . $data['message'];
    }
}
