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
        $sql = "INSERT INTO USERS (Name, Email, Password, Role, Avatar, is_active) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        
        $name = $user->getName();
        $email = $user->getEmail();
        $hashedPassword = password_hash($user->getPassword(), PASSWORD_DEFAULT);
        $role = $user->getRole();
        $avatar = $user->getAvatar();
        $isActive = $user->getIsActive();
        
        $stmt->bind_param("sssssi", 
            $name,
            $email,
            $hashedPassword,
            $role,
            $avatar,
            $isActive
        );
        return $stmt->execute();
    }
    
    // READ - Get all users with pagination, search, and role filter
    public function getAll($search = '', $roleFilter = '', $limit = null, $offset = 0) {
        $sql = "SELECT UID, Name, Email, Role, Avatar, is_active, created_at, updated_at FROM USERS WHERE 1=1";
        $params = [];
        $types = '';
        
        // Add search condition if search term provided
        if (!empty($search)) {
            $sql .= " AND (Name LIKE ? OR Email LIKE ?)";
            $searchTerm = "%{$search}%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $types .= 'ss';
        }

        // Add role filter if provided
        if (!empty($roleFilter)) {
            $sql .= " AND Role = ?";
            $params[] = $roleFilter;
            $types .= 's';
        }

        $sql .= " ORDER BY UID DESC";
        
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
        $users = [];
        
        while ($row = $result->fetch_assoc()) {
            $user = new User(
                $row['UID'],
                $row['Name'],
                $row['Email'],
                $row['Role'],
                null, // Don't return password
                $row['Avatar'],
                $row['is_active']
            );
            $users[] = $user;
        }
        return $users;
    }

    // Get total count for pagination with role filter
    public function getTotalCount($search = '', $roleFilter = '') {
        $sql = "SELECT COUNT(*) as total FROM USERS WHERE 1=1";
        $params = [];
        $types = '';
        
        if (!empty($search)) {
            $sql .= " AND (Name LIKE ? OR Email LIKE ?)";
            $searchTerm = "%{$search}%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $types .= 'ss';
        }

        if (!empty($roleFilter)) {
            $sql .= " AND Role = ?";
            $params[] = $roleFilter;
            $types .= 's';
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
    
    // READ - Get by ID
    public function getById($id) {
        $sql = "SELECT UID, Name, Email, Role, Avatar, is_active, created_at, updated_at FROM USERS WHERE UID = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            return new User(
                $row['UID'],
                $row['Name'],
                $row['Email'],
                $row['Role'],
                null, // Don't return password
                $row['Avatar'],
                $row['is_active']
            );
        }
        return null;
    }

    // Check if email exists (for unique email validation)
    public function emailExists($email, $excludeId = null) {
        $sql = "SELECT UID FROM USERS WHERE Email = ?";
        $params = [$email];
        $types = 's';
        
        if ($excludeId !== null) {
            $sql .= " AND UID != ?";
            $params[] = $excludeId;
            $types .= 'i';
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->num_rows > 0;
    }
    
    // UPDATE
    public function update(User $user) {
        $sql = "UPDATE USERS SET Name = ?, Email = ?, Role = ?, Avatar = ?, is_active = ? WHERE UID = ?";
        $stmt = $this->db->prepare($sql);
        
        $name = $user->getName();
        $email = $user->getEmail();
        $role = $user->getRole();
        $avatar = $user->getAvatar();
        $isActive = $user->getIsActive();
        $id = $user->getId();
        
        $stmt->bind_param("ssssii", 
            $name,
            $email,
            $role,
            $avatar,
            $isActive,
            $id
        );
        return $stmt->execute();
    }
    
    // DELETE
    public function delete($id) {
        try {
            // The database has CASCADE DELETE set up, so we just delete from USERS
            $sql = "DELETE FROM USERS WHERE UID = ?";
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