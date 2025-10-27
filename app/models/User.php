<?php
class User {
    private $id;
    private $username;
    private $email;
    private $password;
    private $role;
    private $created_at;
    
    // Constructor
    public function __construct($id = null, $username = null, $email = null, $role = 'Customer', $password = null) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->role = $role;
        $this->password = $password;
    }
    
    // Getters
    public function getId() { return $this->id; }
    public function getUsername() { return $this->username; }
    public function getEmail() { return $this->email; }
    public function getPassword() { return $this->password; }
    public function getRole() { return $this->role; }
    
    // Setters
    public function setId($id) { $this->id = $id; }
    public function setUsername($username) { $this->username = $username; }
    public function setEmail($email) { $this->email = $email; }
    public function setPassword($password) { $this->password = $password; }
    public function setRole($role) { $this->role = $role; }
}