<?php
class User {
    private $id;
    private $name;
    private $email;
    private $password;
    private $role;
    private $avatar;
    private $is_active;
    private $created_at;
    private $updated_at;
    
    // Constructor
    public function __construct($id = null, $name = null, $email = null, $role = 'Guest', $password = null, $avatar = null, $is_active = 1) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->role = $role;
        $this->password = $password;
        $this->avatar = $avatar;
        $this->is_active = $is_active;
    }
    
    // Getters
    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getUsername() { return $this->name; } // Alias for backward compatibility
    public function getEmail() { return $this->email; }
    public function getPassword() { return $this->password; }
    public function getRole() { return $this->role; }
    public function getAvatar() { return $this->avatar; }
    public function getIsActive() { return $this->is_active; }
    public function getCreatedAt() { return $this->created_at; }
    public function getUpdatedAt() { return $this->updated_at; }
    
    // Setters
    public function setId($id) { $this->id = $id; }
    public function setName($name) { $this->name = $name; }
    public function setUsername($name) { $this->name = $name; } // Alias for backward compatibility
    public function setEmail($email) { $this->email = $email; }
    public function setPassword($password) { $this->password = $password; }
    public function setRole($role) { $this->role = $role; }
    public function setAvatar($avatar) { $this->avatar = $avatar; }
    public function setIsActive($is_active) { $this->is_active = $is_active; }
}