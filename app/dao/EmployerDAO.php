<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../models/Employer.php';

class EmployerDAO
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->conn;
    }

    // CREATE
    public function create(Employer $employer)
    {
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
    public function getTotalCount($search = '')
    {
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

    // Get employer by user ID
    public function getByUserId($userId)
    {
        $sql = "SELECT * FROM EMPLOYERS WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            return new Employer(
                $row['id'],
                $row['company_name'],
                $row['website'],
                $row['logo'],
                $row['contact_phone'],
                $row['contact_email'],
                $row['contact_person'],
                $row['description'],
                $row['user_id']
            );
        }
        return null;
    }

    // Update employer information
    public function update($id, $data)
    {
        $sql = "UPDATE EMPLOYERS SET 
                company_name = ?,
                website = ?,
                logo = ?,
                contact_phone = ?,
                contact_email = ?,
                contact_person = ?,
                description = ?
                WHERE id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param(
            "sssssssi",
            $data['company_name'],
            $data['website'],
            $data['logo'],
            $data['contact_phone'],
            $data['contact_email'],
            $data['contact_person'],
            $data['description'],
            $id
        );

        return $stmt->execute();
    }

    // DELETE
    public function delete($id)
    {
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
    public function getEmployerByUserId($user_id)
    {
        $sql = "SELECT * FROM EMPLOYERS WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        if (!$row)
            return null;

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

    public function updateCompanyProfile(Employer $employer)
    {
        $sql = "UPDATE EMPLOYERS 
                SET company_name = ?, contact_person = ?, contact_email = ?, 
                    contact_phone = ?, website = ?, description = ?, logo = ? 
                WHERE user_id = ?";

        $stmt = $this->db->prepare($sql);

        // Store values in variables for bind_param
        $companyName = $employer->getCompanyName();
        $contactPerson = $employer->getContactPerson();
        $contactEmail = $employer->getContactEmail();
        $contactPhone = $employer->getContactPhone();
        $website = $employer->getWebsite();
        $description = $employer->getDescription();
        $logo = $employer->getLogo();
        $userId = $employer->getUserId();

        $stmt->bind_param(
            "sssssssi",
            $companyName,
            $contactPerson,
            $contactEmail,
            $contactPhone,
            $website,
            $description,
            $logo,
            $userId
        );

        return $stmt->execute();
    }

}
