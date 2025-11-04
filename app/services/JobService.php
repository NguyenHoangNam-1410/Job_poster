<?php
require_once __DIR__ . '/../dao/JobDAO.php';
require_once __DIR__ . '/../dao/JobCategoryDAO.php';

class JobService {
    private $jobDAO;
    private $categoryDAO;

    public function __construct() {
        $this->jobDAO = new JobDAO();
        $this->categoryDAO = new JobCategoryDAO();
    }

    // Get all jobs with filters and pagination
    public function getAllJobs($search, $categoryFilter, $locationFilter, $statusFilter, $per_page, $offset) {
        return $this->jobDAO->getAll($search, $categoryFilter, $locationFilter, $statusFilter, $per_page, $offset);
    }

    // Get total count for pagination
    public function getTotalCount($search, $categoryFilter, $locationFilter, $statusFilter) {
        return $this->jobDAO->getTotalCount($search, $categoryFilter, $locationFilter, $statusFilter);
    }

    // Get job by ID
    public function getJobById($id) {
        return $this->jobDAO->getById($id);
    }

    // Get all categories for filter dropdown
    public function getAllCategories() {
        return $this->categoryDAO->getAll('', null, 0);
    }

    // Get unique locations for filter dropdown
    public function getUniqueLocations() {
        return $this->jobDAO->getUniqueLocations();
    }

    // Create new job
    public function createJob($data, $currentUserId) {
        // Validate required fields
        if (empty($data['title'])) {
            throw new Exception("Job title is required.");
        }

        if (empty($data['employer_id'])) {
            throw new Exception("Employer is required.");
        }

        $job = new Job(
            null,
            $data['employer_id'],
            $currentUserId,
            $data['title'],
            $data['location'] ?? null,
            $data['description'] ?? null,
            $data['requirements'] ?? null,
            $data['salary'] ?? null,
            $data['deadline'] ?? null,
            $data['status'] ?? 'draft'
        );

        $jobId = $this->jobDAO->create($job);
        
        if ($jobId && !empty($data['categories'])) {
            $this->jobDAO->updateJobCategories($jobId, $data['categories']);
        }

        return $jobId;
    }

    // Update job
    public function updateJob($id, $data) {
        $job = $this->jobDAO->getById($id);
        if (!$job) {
            throw new Exception("Job not found.");
        }

        // Validate required fields
        if (empty($data['title'])) {
            throw new Exception("Job title is required.");
        }

        $job->setTitle($data['title']);
        $job->setLocation($data['location'] ?? null);
        $job->setDescription($data['description'] ?? null);
        $job->setRequirements($data['requirements'] ?? null);
        $job->setSalary($data['salary'] ?? null);
        $job->setDeadline($data['deadline'] ?? null);

        // Update status if provided
        if (isset($data['status'])) {
            $job->setStatus($data['status']);
        }

        $result = $this->jobDAO->update($job);
        
        // Update categories
        if (isset($data['categories'])) {
            $this->jobDAO->updateJobCategories($id, $data['categories']);
        }

        return $result;
    }

    // Change job status
    public function changeStatus($id, $newStatus, $currentUserId = null) {
        $job = $this->jobDAO->getById($id);
        if (!$job) {
            throw new Exception("Job not found.");
        }

        // Validate status transition
        $validStatuses = ['draft', 'pending', 'approved', 'rejected', 'overdue', 'soft_deleted'];
        if (!in_array($newStatus, $validStatuses)) {
            throw new Exception("Invalid status.");
        }

        return $this->jobDAO->changeStatus($id, $newStatus);
    }

    // Soft delete job
    public function softDeleteJob($id) {
        $job = $this->jobDAO->getById($id);
        if (!$job) {
            throw new Exception("Job not found.");
        }

        return $this->jobDAO->softDelete($id);
    }

    // Hard delete job
    public function hardDeleteJob($id) {
        $job = $this->jobDAO->getById($id);
        if (!$job) {
            throw new Exception("Job not found.");
        }

        return $this->jobDAO->hardDelete($id);
    }

    // Restore soft deleted job (change status from soft_deleted to draft)
    public function restoreJob($id) {
        $job = $this->jobDAO->getById($id);
        if (!$job) {
            throw new Exception("Job not found.");
        }

        if (!$job->isSoftDeleted()) {
            throw new Exception("Job is not soft deleted.");
        }

        return $this->jobDAO->changeStatus($id, 'draft');
    }

    // Approve job
    public function approveJob($id, $currentUserId) {
        return $this->changeStatus($id, 'approved', $currentUserId);
    }

    // Reject job
    public function rejectJob($id, $currentUserId) {
        return $this->changeStatus($id, 'rejected', $currentUserId);
    }

    // Mark as pending
    public function markAsPending($id) {
        return $this->changeStatus($id, 'pending');
    }

    // Mark as overdue
    public function markAsOverdue($id) {
        return $this->changeStatus($id, 'overdue');
    }

    // Approve job with review record
    public function approveJobWithReview($id, $currentUserId, $reason = null) {
        $job = $this->jobDAO->getById($id);
        if (!$job) {
            throw new Exception("Job not found.");
        }

        // Change status to approved
        $statusChanged = $this->jobDAO->changeStatus($id, 'approved');
        
        if ($statusChanged) {
            // Create review record with optional reason/notes
            $this->jobDAO->createReview($id, $currentUserId, 'approve', $reason);
            return true;
        }
        
        return false;
    }

    // Reject job with review record
    public function rejectJobWithReview($id, $currentUserId, $reason) {
        $job = $this->jobDAO->getById($id);
        if (!$job) {
            throw new Exception("Job not found.");
        }

        if (empty($reason)) {
            throw new Exception("Rejection reason is required.");
        }

        // Change status to rejected
        $statusChanged = $this->jobDAO->changeStatus($id, 'rejected');
        
        if ($statusChanged) {
            // Create review record with reason
            $this->jobDAO->createReview($id, $currentUserId, 'reject', $reason);
            return true;
        }
        
        return false;
    }

    // Get latest review for a job
    public function getLatestReview($jobId) {
        return $this->jobDAO->getLatestReview($jobId);
    }
}
