<?php

namespace App\Models; // Ensure the namespace is correct

use CodeIgniter\Model;

class JobQueueModel extends Model
{
    protected $table = 'job_queue';
    protected $primaryKey = 'id';
    protected $allowedFields = ['job_name', 'job_data', 'status'];
    protected $useTimestamps = true;

    // Add job to queue
    public function add_to_queue($job_data)
    {
        return $this->insert($job_data);
    }

    // Get the next pending job
    public function get_pending_job()
    {
        return $this->where('status', 'pending')->first();
    }

    // Update job status
    public function update_job_status($job_id, $status)
    {
        return $this->update($job_id, ['status' => $status]);
    }
}
