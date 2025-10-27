<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../models/User.php';

class UserDAO {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->conn;
    }
    
    // CREATE
    public function create(User $user) {
        $sql = "INSERT INTO LOGIN (Name, Email, Password, Role) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ssss", 
            $user->getUsername(),
            $user->getEmail(),
            password_hash($user->getPassword(), PASSWORD_DEFAULT),
            $user->getRole()
        );
        return $stmt->execute();
    }
    
    // READ - Get all users with pagination and search
    public function getAll($search = '', $limit = null, $offset = 0) {
        $sql = "SELECT * FROM LOGIN";
        
        // Add search condition if search term provided
        if (!empty($search)) {
            $sql .= " WHERE (Name LIKE ? OR Email LIKE ?)";
        }

        $sql .= " ORDER BY UID DESC";
        
        if ($limit !== null) {
            $sql .= " LIMIT ? OFFSET ?";
        }

        $stmt = $this->db->prepare($sql);
        
        if (!empty($search)) {
            $searchTerm = "%{$search}%";
            if ($limit !== null) {
                $stmt->bind_param("ssii", $searchTerm, $searchTerm, $limit, $offset);
            } else {
                $stmt->bind_param("ss", $searchTerm, $searchTerm);
            }
        } else {
            if ($limit !== null) {
                $stmt->bind_param("ii", $limit, $offset);
            }
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        $users = [];
        
        while ($row = $result->fetch_assoc()) {
            $user = new User(
                $row['UID'],
                $row['Name'],
                $row['Email'],
                $row['Role']
            );
            $users[] = $user;
        }
        return $users;
    }

    // Get total count for pagination
    public function getTotalCount($search = '') {
        $sql = "SELECT COUNT(*) as total FROM LOGIN";
        
        if (!empty($search)) {
            $sql .= " WHERE (Name LIKE ? OR Email LIKE ?)";
        }
        
        $stmt = $this->db->prepare($sql);
        
        if (!empty($search)) {
            $searchTerm = "%{$search}%";
            $stmt->bind_param("ss", $searchTerm, $searchTerm);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        return $row['total'];
    }
    
    // READ - Get by ID
    public function getById($id) {
        $sql = "SELECT * FROM LOGIN WHERE UID = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            return new User(
                $row['UID'],
                $row['Name'],
                $row['Email'],
                $row['Role']
            );
        }
        return null;
    }
    
    // UPDATE
    public function update(User $user) {
        $sql = "UPDATE LOGIN SET Name = ?, Email = ?, Role = ? WHERE UID = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sssi", 
            $user->getUsername(),
            $user->getEmail(),
            $user->getRole(),
            $user->getId()
        );
        return $stmt->execute();
    }
    
    // DELETE
    public function delete($id) {
        try {
            // First, try to delete from related tables (if they exist)
            // Delete from ADMIN table if exists
            $sql = "DELETE FROM ADMIN WHERE UID = ?";
            $stmt = $this->db->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $stmt->close();
            }
            
            // Delete from CUSTOMER table if exists
            $sql = "DELETE FROM CUSTOMER WHERE UID = ?";
            $stmt = $this->db->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $stmt->close();
            }
            
            // Now delete from LOGIN table
            $sql = "DELETE FROM LOGIN WHERE UID = ?";
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
            error_log("UserDAO delete error: " . $e->getMessage());
            throw $e;
        }
    }
}