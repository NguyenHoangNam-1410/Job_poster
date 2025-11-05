<?php
require_once __DIR__ . '/../../config/db.php';

class StatisticDAO {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->conn;
    }
    
    /**
     * Get count of users by role
     */
    public function getUserCountByRole($role) {
        $sql = "SELECT COUNT(*) as count FROM USERS WHERE Role = ?";
        $stmt = $this->db->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->db->error);
        }
        
        $stmt->bind_param("s", $role);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        
        return $row['count'] ?? 0;
    }
    
    /**
     * Get job count by status
     */
    public function getJobCountByStatus($status) {
        $sql = "SELECT COUNT(*) as count FROM JOBS WHERE status = ?";
        $stmt = $this->db->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->db->error);
        }
        
        $stmt->bind_param("s", $status);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        
        return $row['count'] ?? 0;
    }
    
    /**
     * Get total jobs count
     */
    public function getTotalJobsCount() {
        $sql = "SELECT COUNT(*) as count FROM JOBS";
        $stmt = $this->db->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->db->error);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        
        return $row['count'] ?? 0;
    }
    
    /**
     * Get top staff members with highest workload (most actions)
     */
    public function getTopStaffByWorkload($limit = 3) {
        $sql = "SELECT u.UID, u.Name, u.Role, COUNT(sa.id) as action_count
                FROM USERS u
                INNER JOIN STAFF_ACTIONS sa ON u.UID = sa.user_id
                WHERE u.Role IN ('Admin', 'Staff')
                GROUP BY u.UID, u.Name, u.Role
                ORDER BY action_count DESC
                LIMIT ?";
        
        $stmt = $this->db->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->db->error);
        }
        
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $staff = [];
        while ($row = $result->fetch_assoc()) {
            $staff[] = [
                'id' => $row['UID'],
                'name' => $row['Name'],
                'role' => $row['Role'],
                'action_count' => $row['action_count']
            ];
        }
        
        $stmt->close();
        return $staff;
    }
    
    /**
     * Get top employers who posted most jobs (approved and overdue status only)
     */
    public function getTopEmployersByJobs($limit = 3) {
        $sql = "SELECT e.id, e.company_name, u.UID as user_id, u.Name as user_name, COUNT(j.id) as job_count
                FROM EMPLOYERS e
                INNER JOIN USERS u ON e.user_id = u.UID
                INNER JOIN JOBS j ON e.id = j.employer_id
                WHERE j.status IN ('approved', 'overdue')
                GROUP BY e.id, e.company_name, u.UID, u.Name
                ORDER BY job_count DESC
                LIMIT ?";
        
        $stmt = $this->db->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->db->error);
        }
        
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $employers = [];
        while ($row = $result->fetch_assoc()) {
            $employers[] = [
                'id' => $row['id'],
                'company_name' => $row['company_name'],
                'user_id' => $row['user_id'],
                'user_name' => $row['user_name'],
                'job_count' => $row['job_count']
            ];
        }
        
        $stmt->close();
        return $employers;
    }
}
