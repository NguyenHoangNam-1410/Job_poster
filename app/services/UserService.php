<?php
require_once __DIR__ . '/../dao/UserDAO.php';

class UserService {
    private $userDAO;

    public function __construct() {
        $this->userDAO = new UserDAO();
    }

    public function getAllUsers($search, $roleFilter, $per_page, $offset) {
        return $this->userDAO->getAll($search, $roleFilter, $per_page, $offset);
    }

    public function getTotalCount($search, $roleFilter) {
        return $this->userDAO->getTotalCount($search, $roleFilter);
    }

    public function getUserById($id) {
        return $this->userDAO->getById($id);
    }

    public function createUser($data, $currentUserId = null) {
        // Validate that only Admin or Staff roles can be created
        if (!in_array($data['role'], ['Admin', 'Staff'])) {
            throw new Exception("Invalid role. Only Admin and Staff can be created.");
        }

        // Check if email already exists
        if ($this->userDAO->emailExists($data['email'])) {
            throw new Exception("Email already exists.");
        }

        // Validate password is provided
        if (empty($data['password'])) {
            throw new Exception("Password is required.");
        }

        $user = new User(
            null, 
            $data['username'], 
            $data['email'], 
            $data['role'], 
            $data['password'],
            null, // avatar
            1 // is_active
        );
        return $this->userDAO->create($user);
    }

    public function updateUser($id, $data, $currentUserId = null) {
        $user = $this->userDAO->getById($id);
        if (!$user) {
            throw new Exception("User not found.");
        }

        // Prevent admin from editing other admins (unless editing themselves)
        if ($user->getRole() === 'Admin' && $id != $currentUserId) {
            throw new Exception("You cannot edit other administrators.");
        }

        // Validate role
        if (!in_array($data['role'], ['Admin', 'Staff'])) {
            throw new Exception("Invalid role. Only Admin and Staff are allowed.");
        }

        // Check if email already exists (excluding current user)
        if ($this->userDAO->emailExists($data['email'], $id)) {
            throw new Exception("Email already exists.");
        }

        $user->setUsername($data['username']);
        $user->setEmail($data['email']);
        $user->setRole($data['role']);

        return $this->userDAO->update($user);
    }

    public function deleteUser($id, $currentUserId = null) {
        $user = $this->userDAO->getById($id);
        if (!$user) {
            throw new Exception("User not found.");
        }

        // Prevent admin from deleting themselves
        if ($id == $currentUserId) {
            throw new Exception("You cannot delete yourself.");
        }

        // Allow admins to delete other admins or staff
        return $this->userDAO->delete($id);
    }

    public function canEdit($userId, $currentUserId) {
        $user = $this->userDAO->getById($userId);
        if (!$user) {
            return false;
        }

        // Can edit if it's the same user OR if the target is not an admin
        return ($userId == $currentUserId) || ($user->getRole() !== 'Admin');
    }
}
