<?php
class JobQueue_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // Insert a job into the queue
    public function add_to_queue($job_data) {
        $data = array(
            'job_name' => $job_data['job_name'],
            'status' => 'pending',
            'job_data' => json_encode($job_data['job_data']),
            'created_at' => date('Y-m-d H:i:s')
        );

        $this->db->insert('job_queue', $data);
    }

    // Get a pending job from the queue
    public function get_pending_job() {
        $this->db->where('status', 'pending');
        $this->db->order_by('created_at', 'asc');
        $query = $this->db->get('job_queue', 1);
        return $query->row();
    }

    // Update job status to processed
    public function update_job_status($job_id, $status) {
        $this->db->where('id', $job_id);
        $this->db->update('job_queue', array('status' => $status));
    }
}
