<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../models/Employer.php';

class EmployerDAO {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->conn;
    }
    
    // CREATE
    public function create(Employer $employer) {
        $sql = "INSERT INTO EMPLOYERS (user_id, company_name, website, logo, contact_phone, contact_email, contact_person, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        
        $userId = $employer->getUserId();
        $companyName = $employer->getCompanyName();
        $website = $employer->getWebsite();
        $logo = $employer->getLogo();
        $contactPhone = $employer->getContactPhone();
        $contactEmail = $employer->getContactEmail();
        $contactPerson = $employer->getContactPerson();
        $description = $employer->getDescription();

        $stmt->bind_param("isssssss", $userId, $companyName, $website, $logo, $contactPhone, $contactEmail, $contactPerson, $description);
        
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

    // Get employer by user id
    public function getEmployerByUserId($user_id){   
        $sql = "SELECT * FROM EMPLOYERS WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        if (!$row) return null;

        $employer = new Employer();
        $employer->setId($row['id']);
        $employer->setCompanyName($row['company_name']);
        $employer->setContactPerson($row['contact_person']);
        $employer->setContactEmail($row['contact_email']);
        $employer->setContactPhone($row['contact_phone']);
        $employer->setWebsite($row['website']);
        $employer->setDescription($row['description']);
        $employer->setLogo($row['logo']);
        $employer->setUserId($row['user_id']);

        return $employer;

    }

    public function updateCompanyProfile(Employer $employer) {
        $sql = "UPDATE EMPLOYERS 
                SET company_name = ?, contact_person = ?, contact_email = ?, 
                    contact_phone = ?, website = ?, description = ?, logo = ? 
                WHERE user_id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param(
            "sssssssi",
            $employer->getCompanyName(),
            $employer->getContactPerson(),
            $employer->getContactEmail(),
            $employer->getContactPhone(),
            $employer->getWebsite(),
            $employer->getDescription(),
            $employer->getLogo(),
            $employer->getUserId()
        );

        return $stmt->execute();
    }
    
}
