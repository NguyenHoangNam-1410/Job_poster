<?php

class Feedback {
    private $id;
    private $userId;
    private $comments;
    private $createdAt;
    
    // Additional properties for joined data
    private $userName;

    public function __construct($id = null, $userId = null, $comments = null, $createdAt = null) {
        $this->id = $id;
        $this->userId = $userId;
        $this->comments = $comments;
        $this->createdAt = $createdAt;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getComments() {
        return $this->comments;
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }

    public function getUserName() {
        return $this->userName;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setUserId($userId) {
        $this->userId = $userId;
    }

    public function setComments($comments) {
        $this->comments = $comments;
    }

    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;
    }

    public function setUserName($userName) {
        $this->userName = $userName;
    }
}
