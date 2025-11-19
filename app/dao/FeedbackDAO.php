<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../models/Feedback.php';

class FeedbackDAO {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->conn;
    }
    
    // CREATE
    public function create(Feedback $feedback) {
        $sql = "INSERT INTO FEEDBACK (user_id, comments, created_at) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->db->error);
        }
        
        $userId = $feedback->getUserId();
        $comments = $feedback->getComments();
        $createdAt = $feedback->getCreatedAt();
        
        $stmt->bind_param("iss", $userId, $comments, $createdAt);
        
        $result = $stmt->execute();
        $insertedId = $result ? $this->db->insert_id : null;
        
        $stmt->close();
        
        return $insertedId;
    }
    
    // READ - Get all feedbacks with filters and pagination
    public function getAll($search = '', $dateFrom = '', $dateTo = '', $limit = null, $offset = 0) {
        $sql = "SELECT f.*, u.Name as user_name 
                FROM FEEDBACK f
                INNER JOIN USERS u ON f.user_id = u.UID
                WHERE 1=1";
        
        $params = [];
        $types = '';
        
        // Search by user name
        if (!empty($search)) {
            $sql .= " AND u.Name LIKE ?";
            $params[] = "%{$search}%";
            $types .= 's';
        }
        
        // Filter by date range
        if (!empty($dateFrom)) {
            $sql .= " AND DATE(f.created_at) >= ?";
            $params[] = $dateFrom;
            $types .= 's';
        }
        
        if (!empty($dateTo)) {
            $sql .= " AND DATE(f.created_at) <= ?";
            $params[] = $dateTo;
            $types .= 's';
        }
        
        // Order by newest first
        $sql .= " ORDER BY f.created_at DESC";
        
        if ($limit !== null) {
            $sql .= " LIMIT ? OFFSET ?";
            $params[] = $limit;
            $params[] = $offset;
            $types .= 'ii';
        }
        
        $stmt = $this->db->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->db->error);
        }
        
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        $feedbacks = [];
        
        while ($row = $result->fetch_assoc()) {
            $feedback = new Feedback(
                $row['id'],
                $row['user_id'],
                $row['comments'],
                $row['created_at']
            );
            $feedback->setUserName($row['user_name']);
            $feedbacks[] = $feedback;
        }
        
        $stmt->close();
        return $feedbacks;
    }
    
    // Get total count for pagination
    public function getTotalCount($search = '', $dateFrom = '', $dateTo = '') {
        $sql = "SELECT COUNT(*) as total 
                FROM FEEDBACK f
                INNER JOIN USERS u ON f.user_id = u.UID
                WHERE 1=1";
        
        $params = [];
        $types = '';
        
        // Search by user name
        if (!empty($search)) {
            $sql .= " AND u.Name LIKE ?";
            $params[] = "%{$search}%";
            $types .= 's';
        }
        
        // Filter by date range
        if (!empty($dateFrom)) {
            $sql .= " AND DATE(f.created_at) >= ?";
            $params[] = $dateFrom;
            $types .= 's';
        }
        
        if (!empty($dateTo)) {
            $sql .= " AND DATE(f.created_at) <= ?";
            $params[] = $dateTo;
            $types .= 's';
        }
        
        $stmt = $this->db->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->db->error);
        }
        
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        $stmt->close();
        return $row['total'];
    }
    
    // READ - Get by ID
    public function getById($id) {
        $sql = "SELECT f.*, u.Name as user_name 
                FROM FEEDBACK f
                INNER JOIN USERS u ON f.user_id = u.UID
                WHERE f.id = ?";
        $stmt = $this->db->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->db->error);
        }
        
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            $feedback = new Feedback(
                $row['id'],
                $row['user_id'],
                $row['comments'],
                $row['created_at']
            );
            $feedback->setUserName($row['user_name']);
            $stmt->close();
            return $feedback;
        }
        
        $stmt->close();
        return null;
    }
    
    // DELETE
    public function delete($id) {
        try {
            $sql = "DELETE FROM FEEDBACK WHERE id = ?";
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
            error_log("FeedbackDAO delete error: " . $e->getMessage());
            throw $e;
        }
    }

    // Get total count for pagination for a specific user
    public function getTotalCountForUser($userId, $search, $dateFrom, $dateTo) {
        try{
            $sql = "SELECT COUNT(*) as total 
                    FROM FEEDBACK f
                    INNER JOIN USERS u ON f.user_id = u.UID
                    WHERE f.user_id = ?
                    AND 1=1";
            
            $params = [$userId];
            $types = 'i';
            
            // Search by user name
            if (!empty($search)) {
                $sql .= " AND u.Name LIKE ?";
                $params[] = "%{$search}%";
                $types .= 's';
            }
            
            // Filter by date range
            if (!empty($dateFrom)) {
                $sql .= " AND DATE(f.created_at) >= ?";
                $params[] = $dateFrom;
                $types .= 's';
            }
            
            if (!empty($dateTo)) {
                $sql .= " AND DATE(f.created_at) <= ?";
                $params[] = $dateTo;
                $types .= 's';
            }
            
            $stmt = $this->db->prepare($sql);
            
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $this->db->error);
            }
            
            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }
            
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            
            $stmt->close();
            return $row['total'];
        } catch (Exception $e) {
            error_log("FeedbackDAO getTotalCountForUser error: " . $e->getMessage());
            throw $e;
        } 
    }

    // Get feedbacks for a specific user with filters and pagination
    public function getFeedbacksByUser($userId, $search, $dateFrom, $dateTo, $per_page, $offset) {
        try {
            $sql = "SELECT f.*, u.Name as user_name 
                    FROM FEEDBACK f
                    INNER JOIN USERS u ON f.user_id = u.UID
                    WHERE f.user_id = ?
                    AND 1=1";
            
            $params = [$userId];
            $types = 'i';
            
            // Search by user name
            if (!empty($search)) {
                $sql .= " AND u.Name LIKE ?";
                $params[] = "%{$search}%";
                $types .= 's';
            }
            
            // Filter by date range
            if (!empty($dateFrom)) {
                $sql .= " AND DATE(f.created_at) >= ?";
                $params[] = $dateFrom;
                $types .= 's';
            }
            
            if (!empty($dateTo)) {
                $sql .= " AND DATE(f.created_at) <= ?";
                $params[] = $dateTo;
                $types .= 's';
            }
            
            // Order by newest first
            $sql .= " ORDER BY f.created_at DESC";
            
            if ($per_page !== null) {
                $sql .= " LIMIT ? OFFSET ?";
                $params[] = $per_page;
                $params[] = $offset;
                $types .= 'ii';
            }
            
            $stmt = $this->db->prepare($sql);
            
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $this->db->error);
            }
            
            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }
            
            $stmt->execute();
            $result = $stmt->get_result();
            $feedbacks = [];
            
            while ($row = $result->fetch_assoc()) {
                $feedback = new Feedback(
                    $row['id'],
                    $row['user_id'],
                    $row['comments'],
                    $row['created_at']
                );
                $feedback->setUserName($row['user_name']);
                $feedbacks[] = $feedback;
            }
            
            $stmt->close();
            return $feedbacks;
        } catch (Exception $e) {
            error_log("FeedbackDAO getFeedbacksByUser error: " . $e->getMessage());
            throw $e;
        }
    }
}
