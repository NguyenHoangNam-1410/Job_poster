<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../models/User.php';

class UserDAO
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->conn;
    }

    // CREATE
    public function create(User $user)
    {
        $sql = "INSERT INTO USERS (Name, Email, Password, Role, Avatar, is_active, auth_provider) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            error_log("UserDAO::create - Prepare failed: " . $this->db->error);
            return false;
        }

        $name = $user->getName();
        $email = $user->getEmail();
        $hashedPassword = password_hash($user->getPassword(), PASSWORD_DEFAULT);
        $role = $user->getRole();
        $avatar = $user->getAvatar();
        $isActive = $user->getIsActive();
        $authProvider = $user->getAuthProvider();
        $stmt->bind_param(
            "sssssis",
            $name,
            $email,
            $hashedPassword,
            $role,
            $avatar,
            $isActive,
            $authProvider
        );
        return $stmt->execute();
    }

    // READ - Get all users with pagination, search, and role filter
    public function getAll($search = '', $roleFilter = '', $limit = null, $offset = 0)
    {
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
        if (!$stmt) {
            error_log("UserDAO::getAll - Prepare failed: " . $this->db->error);
            return [];
        }

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
            $user->setCreatedAt($row['created_at']);
            $user->setUpdatedAt($row['updated_at']);
            $users[] = $user;
        }
        return $users;
    }

    // Get total count for pagination with role filter
    public function getTotalCount($search = '', $roleFilter = '')
    {
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
        if (!$stmt) {
            error_log("UserDAO::getTotalCount - Prepare failed: " . $this->db->error);
            return 0;
        }

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row['total'];
    }

    // READ - Get by ID
    public function getById($id)
    {
        $sql = "SELECT UID, Name, Email, Role, Avatar, is_active, created_at, updated_at, auth_provider FROM USERS WHERE UID = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            error_log("UserDAO::getById - Prepare failed: " . $this->db->error);
            return null;
        }
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
                $row['is_active'],
                $row['auth_provider']
            );
        }
        return null;
    }

    public function getByEmail($email)
    {
        $sql = "SELECT UID, Name, Email, Role, Avatar, is_active, created_at, updated_at, auth_provider FROM USERS WHERE Email = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            error_log("UserDAO::getByEmail - Prepare failed: " . $this->db->error);
            return null;
        }
        $stmt->bind_param("s", $email);
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
                $row['is_active'],
                $row['auth_provider']
            );
        }
        return null;
    }

    public function getLocalUserByEmail($email)
    {
        $sql = "SELECT UID, Name, Email, Role, Avatar, is_active, created_at, updated_at FROM USERS WHERE Email = ? AND auth_provider = 'local'";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            error_log("UserDAO::getLocalUserByEmail - Prepare failed: " . $this->db->error);
            return null;
        }
        $stmt->bind_param("s", $email);
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

    public function getHashedPassword($email)
    {
        $sql = "SELECT Password FROM USERS WHERE Email = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            error_log("UserDAO::getHashedPassword - Prepare failed: " . $this->db->error);
            return null;
        }
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            return $row['Password'];
        }
        return null;
    }

    public function getAuthProvider($email)
    {
        $sql = "SELECT auth_provider FROM USERS WHERE Email = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            error_log("UserDAO::getAuthProvider - Prepare failed: " . $this->db->error);
            return null;
        }
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            return $row['auth_provider'];
        }
        return null;
    }
    // Check if email exists (for unique email validation)
    public function emailExists($email, $excludeId = null)
    {
        $sql = "SELECT UID FROM USERS WHERE Email = ?";
        $params = [$email];
        $types = 's';

        if ($excludeId !== null) {
            $sql .= " AND UID != ?";
            $params[] = $excludeId;
            $types .= 'i';
        }

        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            error_log("UserDAO::emailExists - Prepare failed: " . $this->db->error);
            return false;
        }
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0;
    }

    public function createUserGoogle($email, $name, $avatar = null)
    {
        $sql = "INSERT INTO USERS (Email, Password, Role, Name, Avatar, auth_provider, is_active) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            error_log("UserDAO::createUserGoogle - Prepare failed: " . $this->db->error);
            throw new Exception("Database prepare failed: " . $this->db->error);
        }

        $password = password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT);
        $role = 'Employer';
        $isActive = 1;
        $authProvider = 'google';

        $stmt->bind_param("ssssssi", $email, $password, $role, $name, $avatar, $authProvider, $isActive);
        if ($stmt->execute()) {
            $id = $stmt->insert_id;
            return new User($id, $name, $email, $role, null, $avatar, $isActive, $authProvider);
        } else {
            throw new Exception("Insert failed: " . $stmt->error);
        }
    }

    public function createUserFacebook($email, $name, $avatar = null)
    {
        $sql = "INSERT INTO USERS (Email, Password, Role, Name, Avatar, auth_provider, is_active) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            error_log("UserDAO::createUserFacebook - Prepare failed: " . $this->db->error);
            throw new Exception("Database prepare failed: " . $this->db->error);
        }

        $password = password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT);
        $role = 'Employer';
        $isActive = 1;
        $authProvider = 'facebook';

        $stmt->bind_param("ssssssi", $email, $password, $role, $name, $avatar, $authProvider, $isActive);
        if ($stmt->execute()) {
            $id = $stmt->insert_id;
            return new User($id, $name, $email, $role, null, $avatar, $isActive, $authProvider);
        } else {
            throw new Exception("Insert failed: " . $stmt->error);
        }
    }

    // UPDATE PASSWORD BY EMAIL
    public function updatePasswordByEmail($email, $password)
    {
        $sql = "UPDATE USERS SET Password = ? WHERE Email = ?";
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            error_log("UserDAO::updatePasswordByEmail - Prepare failed: " . $this->db->error);
            return false;
        }
        $stmt->bind_param("ss", $hashedPassword, $email);
        return $stmt->execute();
    }

    // UPDATE
    public function update(User $user)
    {
        $sql = "UPDATE USERS SET Name = ?, Email = ?, Role = ?, Avatar = ?, is_active = ? WHERE UID = ?";
        $stmt = $this->db->prepare($sql);

        $name = $user->getName();
        $email = $user->getEmail();
        $role = $user->getRole();
        $avatar = $user->getAvatar();
        $isActive = $user->getIsActive();
        $id = $user->getId();

        $stmt->bind_param(
            "ssssii",
            $name,
            $email,
            $role,
            $avatar,
            $isActive,
            $id
        );
        return $stmt->execute();
    }

    // Get user avatar
    public function getAvatar($userId)
    {
        $sql = "SELECT Avatar FROM USERS WHERE UID = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            return $row['Avatar'];
        }
        return null;
    }

    // Get password hash for verification
    public function getPasswordHash($userId)
    {
        $sql = "SELECT Password FROM USERS WHERE UID = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            return $row['Password'];
        }
        return null;
    }

    // Verify password
    public function verifyPassword($userId, $password)
    {
        $hash = $this->getPasswordHash($userId);
        if ($hash === null) {
            return false;
        }
        return password_verify($password, $hash);
    }

    // Update password
    public function updatePassword($userId, $newPassword)
    {
        $sql = "UPDATE USERS SET Password = ? WHERE UID = ?";
        $stmt = $this->db->prepare($sql);

        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt->bind_param("si", $hashedPassword, $userId);

        return $stmt->execute();
    }

    // Update profile (name, email, avatar only)
    public function updateProfile($userId, $name, $email, $avatar)
    {
        $sql = "UPDATE USERS SET Name = ?, Email = ?, Avatar = ? WHERE UID = ?";
        $stmt = $this->db->prepare($sql);

        $stmt->bind_param("sssi", $name, $email, $avatar, $userId);
        return $stmt->execute();
    }

    // DELETE
    public function delete($id)
    {
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