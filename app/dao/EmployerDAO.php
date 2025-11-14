<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../models/Employer.php';

class EmployderDAO {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->conn;
    }
    
    // CREATE
    public function create(Employer $employer) {
        $sql = "INSERT INTO EMPLOYERS (user_id, company_name, website, logo, contact_phone, contact_email, contact_person) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        
        $userId = $employer->getUserId();
        $companyName = $employer->getCompanyName();
        $website = $employer->getWebsite();
        $logo = $employer->getLogo();
        $contactPhone = $employer->getContactPhone();
        $contactEmail = $employer->getContactEmail();
        $contactPerson = $employer->getContactPerson();

        $stmt->bind_param("issssss", $userId, $companyName, $website, $logo, $contactPhone, $contactEmail, $contactPerson);
        
        return $stmt->execute();
        
    }
    
    // Get total count for pagination
    public function getTotalCount($search = '') {
        $sql = "SELECT COUNT(*) as total FROM EMPLOYERS WHERE 1=1"; // 1=1 is a placeholder
        $params = [];
        $types = '';
        
        if (!empty($search)) {
            $sql .= " AND company_name LIKE ?";
            $searchTerm = "%{$search}%";
            $params[] = $searchTerm;
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
    
    // DELETE
    public function delete($id) {
        try {
            $sql = "DELETE FROM EMPLOYERS WHERE id = ?";
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
            error_log("EmployerDAO delete error: " . $e->getMessage());
            throw $e;
        }
    }
}
