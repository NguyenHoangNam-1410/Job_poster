<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../models/Job.php';

class JobDAO {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->conn;
    }
    
    // CREATE
    public function create(Job $job) {
        $sql = "INSERT INTO JOBS (employer_id, posted_by, title, location, description, requirements, salary, deadline, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        
        $employerId = $job->getEmployerId();
        $postedBy = $job->getPostedBy();
        $title = $job->getTitle();
        $location = $job->getLocation();
        $description = $job->getDescription();
        $requirements = $job->getRequirements();
        $salary = $job->getSalary();
        $deadline = $job->getDeadline();
        $status = $job->getStatus();
        
        $stmt->bind_param("iissssdss", 
            $employerId,
            $postedBy,
            $title,
            $location,
            $description,
            $requirements,
            $salary,
            $deadline,
            $status
        );
        
        if ($stmt->execute()) {
            return $this->db->insert_id;
        }
        return false;
    }
    
    // READ - Get all jobs with filters, search, and pagination
    public function getAll($search = '', $categoryFilter = '', $locationFilter = '', $statusFilter = '', $limit = null, $offset = 0) {
        $sql = "SELECT DISTINCT j.id, j.employer_id, j.posted_by, j.title, j.location, j.description, 
                j.requirements, j.salary, j.deadline, j.status, j.created_at, j.updated_at,
                e.company_name as employer_name,
                u.Name as posted_by_name
                FROM JOBS j
                LEFT JOIN EMPLOYERS e ON j.employer_id = e.id
                LEFT JOIN USERS u ON j.posted_by = u.UID
                LEFT JOIN JOB_CATEGORY_MAP jcm ON j.id = jcm.job_id
                WHERE 1=1";
        
        $params = [];
        $types = '';
        
        // Add search condition (by title)
        if (!empty($search)) {
            $sql .= " AND j.title LIKE ?";
            $searchTerm = "%{$search}%";
            $params[] = $searchTerm;
            $types .= 's';
        }
        
        // Add category filter
        if (!empty($categoryFilter)) {
            $sql .= " AND jcm.category_id = ?";
            $params[] = $categoryFilter;
            $types .= 'i';
        }
        
        // Add location filter
        if (!empty($locationFilter)) {
            $sql .= " AND j.location LIKE ?";
            $locationTerm = "%{$locationFilter}%";
            $params[] = $locationTerm;
            $types .= 's';
        }
        
        // Add status filter
        if (!empty($statusFilter)) {
            // Check if multiple statuses (comma-separated)
            if (strpos($statusFilter, ',') !== false) {
                $statuses = explode(',', $statusFilter);
                $placeholders = implode(',', array_fill(0, count($statuses), '?'));
                $sql .= " AND j.status IN ($placeholders)";
                foreach ($statuses as $status) {
                    $params[] = trim($status);
                    $types .= 's';
                }
            } else {
                $sql .= " AND j.status = ?";
                $params[] = $statusFilter;
                $types .= 's';
            }
        }
        
        $sql .= " ORDER BY j.created_at DESC";
        
        if ($limit !== null) {
            $sql .= " LIMIT ? OFFSET ?";
            $params[] = $limit;
            $params[] = $offset;
            $types .= 'ii';
        }
        
        $stmt = $this->db->prepare($sql);
        
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        $jobs = [];
        
        while ($row = $result->fetch_assoc()) {
            $job = $this->mapRowToJob($row);
            // Load categories for each job
            $job->setCategories($this->getJobCategories($job->getId()));
            $jobs[] = $job;
        }
        
        return $jobs;
    }
    
    // Get total count for pagination
    public function getTotalCount($search = '', $categoryFilter = '', $locationFilter = '', $statusFilter = '') {
        $sql = "SELECT COUNT(DISTINCT j.id) as total 
                FROM JOBS j
                LEFT JOIN JOB_CATEGORY_MAP jcm ON j.id = jcm.job_id
                WHERE 1=1";
        
        $params = [];
        $types = '';
        
        if (!empty($search)) {
            $sql .= " AND j.title LIKE ?";
            $searchTerm = "%{$search}%";
            $params[] = $searchTerm;
            $types .= 's';
        }
        
        if (!empty($categoryFilter)) {
            $sql .= " AND jcm.category_id = ?";
            $params[] = $categoryFilter;
            $types .= 'i';
        }
        
        if (!empty($locationFilter)) {
            $sql .= " AND j.location LIKE ?";
            $locationTerm = "%{$locationFilter}%";
            $params[] = $locationTerm;
            $types .= 's';
        }
        
        if (!empty($statusFilter)) {
            // Check if multiple statuses (comma-separated)
            if (strpos($statusFilter, ',') !== false) {
                $statuses = explode(',', $statusFilter);
                $placeholders = implode(',', array_fill(0, count($statuses), '?'));
                $sql .= " AND j.status IN ($placeholders)";
                foreach ($statuses as $status) {
                    $params[] = trim($status);
                    $types .= 's';
                }
            } else {
                $sql .= " AND j.status = ?";
                $params[] = $statusFilter;
                $types .= 's';
            }
        }
        
        $stmt = $this->db->prepare($sql);
        
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        return $row['total'];
    }
    
    // READ - Get by ID with employer and category info
    public function getById($id) {
        $sql = "SELECT j.*, e.company_name as employer_name, u.Name as posted_by_name
                FROM JOBS j
                LEFT JOIN EMPLOYERS e ON j.employer_id = e.id
                LEFT JOIN USERS u ON j.posted_by = u.UID
                WHERE j.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            $job = $this->mapRowToJob($row);
            
            // Get categories for this job
            $job->setCategories($this->getJobCategories($id));
            
            return $job;
        }
        return null;
    }
    
    // Get categories for a specific job
    public function getJobCategories($jobId) {
        $sql = "SELECT jc.id, jc.category_name 
                FROM JOB_CATEGORIES jc
                INNER JOIN JOB_CATEGORY_MAP jcm ON jc.id = jcm.category_id
                WHERE jcm.job_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $jobId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $categories = [];
        while ($row = $result->fetch_assoc()) {
            $categories[] = [
                'id' => $row['id'],
                'name' => $row['category_name']
            ];
        }
        return $categories;
    }
    
    // Get unique locations for filter dropdown
    public function getUniqueLocations() {
        $sql = "SELECT DISTINCT location FROM JOBS WHERE location IS NOT NULL AND location != '' ORDER BY location ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $locations = [];
        while ($row = $result->fetch_assoc()) {
            $locations[] = $row['location'];
        }
        return $locations;
    }
    
    // UPDATE
    public function update(Job $job) {
        $sql = "UPDATE JOBS SET 
                title = ?, 
                location = ?, 
                description = ?, 
                requirements = ?, 
                salary = ?, 
                deadline = ?,
                status = ?
                WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        
        $title = $job->getTitle();
        $location = $job->getLocation();
        $description = $job->getDescription();
        $requirements = $job->getRequirements();
        $salary = $job->getSalary();
        $deadline = $job->getDeadline();
        $status = $job->getStatus();
        $id = $job->getId();
        
        $stmt->bind_param("ssssdss" . "i", 
            $title,
            $location,
            $description,
            $requirements,
            $salary,
            $deadline,
            $status,
            $id
        );
        
        return $stmt->execute();
    }
    
    // UPDATE job categories
    public function updateJobCategories($jobId, $categoryIds) {
        // First, delete existing categories
        $sql = "DELETE FROM JOB_CATEGORY_MAP WHERE job_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $jobId);
        $stmt->execute();
        
        // Then, insert new categories
        if (!empty($categoryIds)) {
            $sql = "INSERT INTO JOB_CATEGORY_MAP (job_id, category_id) VALUES (?, ?)";
            $stmt = $this->db->prepare($sql);
            
            foreach ($categoryIds as $categoryId) {
                $stmt->bind_param("ii", $jobId, $categoryId);
                $stmt->execute();
            }
        }
        
        return true;
    }
    
    // CHANGE STATUS
    public function changeStatus($id, $newStatus) {
        $sql = "UPDATE JOBS SET status = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("si", $newStatus, $id);
        return $stmt->execute();
    }
    
    // SOFT DELETE
    public function softDelete($id) {
        return $this->changeStatus($id, 'soft_deleted');
    }
    
    // HARD DELETE
    public function hardDelete($id) {
        try {
            // Categories will be deleted automatically due to CASCADE
            $sql = "DELETE FROM JOBS WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $this->db->error);
            }
            
            $stmt->bind_param("i", $id);
            $result = $stmt->execute();
            
            if (!$result) {
                throw new Exception("Execute failed: " . $stmt->error);
            }
            
            $stmt->close();
            return $result;
            
        } catch (Exception $e) {
            error_log("JobDAO hardDelete error: " . $e->getMessage());
            throw $e;
        }
    }
    
    // Helper method to map database row to Job object
    private function mapRowToJob($row) {
        $job = new Job(
            $row['id'],
            $row['employer_id'],
            $row['posted_by'],
            $row['title'],
            $row['location'],
            $row['description'],
            $row['requirements'],
            $row['salary'],
            $row['deadline'],
            $row['status']
        );
        
        $job->setCreatedAt($row['created_at']);
        $job->setUpdatedAt($row['updated_at']);
        
        if (isset($row['employer_name'])) {
            $job->setCompanyName($row['employer_name']);
            $job->setEmployerName($row['employer_name']);
        }
        
        if (isset($row['posted_by_name'])) {
            $job->setPostedByName($row['posted_by_name']);
        }
        
        return $job;
    }

    // CREATE REVIEW
    public function createReview($jobId, $reviewedBy, $action, $reason = null) {
        $sql = "INSERT INTO JOB_REVIEWS (job_id, reviewed_by, action, reason) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iiss", $jobId, $reviewedBy, $action, $reason);
        return $stmt->execute();
    }

    // GET LATEST REVIEW for a job
    public function getLatestReview($jobId) {
        $sql = "SELECT jr.*, u.Name as reviewer_name 
                FROM JOB_REVIEWS jr
                LEFT JOIN USERS u ON jr.reviewed_by = u.UID
                WHERE jr.job_id = ? 
                ORDER BY jr.created_at DESC 
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $jobId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            return $row;
        }
        return null;
    }
}

