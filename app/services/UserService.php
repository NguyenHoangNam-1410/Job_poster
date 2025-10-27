<?php
require_once __DIR__ . '/../dao/UserDAO.php';

class UserService {
    private $userDAO;

    public function __construct() {
        $this->userDAO = new UserDAO();
    }

    public function getAllUsers($search, $per_page, $offset) {
        return $this->userDAO->getAll($search, $per_page, $offset);
    }

    public function getTotalCount($search) {
        return $this->userDAO->getTotalCount($search);
    }

    public function getUserById($id) {
        return $this->userDAO->getById($id);
    }

    public function createUser($data) {
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        $user = new User(null, $data['username'], $data['email'], $data['role'], $hashedPassword);
        return $this->userDAO->create($user);
    }

    public function updateUser($id, $data) {
        $user = $this->userDAO->getById($id);
        if (!$user) return false;

        $user->setUsername($data['username']);
        $user->setEmail($data['email']);
        $user->setRole($data['role']);

        return $this->userDAO->update($user);
    }

    public function deleteUser($id) {
        return $this->userDAO->delete($id);
    }
}
