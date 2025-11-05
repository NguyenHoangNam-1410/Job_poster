<?php

class StaffAction {
    private $id;
    private $userId;
    private $jobId;
    private $actionType;
    private $actionDate;
    
    // Additional properties for joined data
    private $userName;
    private $userRole;
    private $jobTitle;

    public function __construct($id = null, $userId = null, $jobId = null, $actionType = null, $actionDate = null) {
        $this->id = $id;
        $this->userId = $userId;
        $this->jobId = $jobId;
        $this->actionType = $actionType;
        $this->actionDate = $actionDate;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getJobId() {
        return $this->jobId;
    }

    public function getActionType() {
        return $this->actionType;
    }

    public function getActionDate() {
        return $this->actionDate;
    }

    public function getUserName() {
        return $this->userName;
    }

    public function getUserRole() {
        return $this->userRole;
    }

    public function getJobTitle() {
        return $this->jobTitle;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setUserId($userId) {
        $this->userId = $userId;
    }

    public function setJobId($jobId) {
        $this->jobId = $jobId;
    }

    public function setActionType($actionType) {
        $this->actionType = $actionType;
    }

    public function setActionDate($actionDate) {
        $this->actionDate = $actionDate;
    }

    public function setUserName($userName) {
        $this->userName = $userName;
    }

    public function setUserRole($userRole) {
        $this->userRole = $userRole;
    }

    public function setJobTitle($jobTitle) {
        $this->jobTitle = $jobTitle;
    }
}
