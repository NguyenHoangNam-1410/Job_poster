<?php
class Job {
    private $id;
    private $employer_id;
    private $posted_by;
    private $title;
    private $location;
    private $description;
    private $requirements;
    private $salary;
    private $deadline;
    private $status;
    private $created_at;
    private $updated_at;
    private $approved_at;
    private $rejected_at;
    
    // Additional fields for display
    private $employer_name;
    private $company_name;
    private $posted_by_name;
    private $employer_avatar;
    private $categories = [];
    
    // Constructor
    public function __construct(
        $id = null,
        $employer_id = null,
        $posted_by = null,
        $title = null,
        $location = null,
        $description = null,
        $requirements = null,
        $salary = null,
        $deadline = null,
        $status = 'draft'
    ) {
        $this->id = $id;
        $this->employer_id = $employer_id;
        $this->posted_by = $posted_by;
        $this->title = $title;
        $this->location = $location;
        $this->description = $description;
        $this->requirements = $requirements;
        $this->salary = $salary;
        $this->deadline = $deadline;
        $this->status = $status;
    }
    
    // Getters
    public function getId() { return $this->id; }
    public function getEmployerId() { return $this->employer_id; }
    public function getPostedBy() { return $this->posted_by; }
    public function getTitle() { return $this->title; }
    public function getLocation() { return $this->location; }
    public function getDescription() { return $this->description; }
    public function getRequirements() { return $this->requirements; }
    public function getSalary() { return $this->salary; }
    public function getDeadline() { return $this->deadline; }
    public function getStatus() { return $this->status; }
    public function getCreatedAt() { return $this->created_at; }
    public function getUpdatedAt() { return $this->updated_at; }
    public function getApprovedAt() { return $this->approved_at; }
    public function getRejectedAt() { return $this->rejected_at; }
    public function getEmployerName() { return $this->employer_name; }
    public function getCompanyName() { return $this->company_name; }
    public function getPostedByName() { return $this->posted_by_name; }
    public function getEmployerAvatar() { return $this->employer_avatar; }
    public function getCategories() { return $this->categories; }
    
    // Setters
    public function setId($id) { $this->id = $id; }
    public function setEmployerId($employer_id) { $this->employer_id = $employer_id; }
    public function setPostedBy($posted_by) { $this->posted_by = $posted_by; }
    public function setTitle($title) { $this->title = $title; }
    public function setLocation($location) { $this->location = $location; }
    public function setDescription($description) { $this->description = $description; }
    public function setRequirements($requirements) { $this->requirements = $requirements; }
    public function setSalary($salary) { $this->salary = $salary; }
    public function setDeadline($deadline) { $this->deadline = $deadline; }
    public function setStatus($status) { $this->status = $status; }
    public function setCreatedAt($created_at) { $this->created_at = $created_at; }
    public function setUpdatedAt($updated_at) { $this->updated_at = $updated_at; }
    public function setApprovedAt($approved_at) { $this->approved_at = $approved_at; }
    public function setRejectedAt($rejected_at) { $this->rejected_at = $rejected_at; }
    public function setEmployerName($employer_name) { $this->employer_name = $employer_name; }
    public function setCompanyName($company_name) { $this->company_name = $company_name; }
    public function setPostedByName($posted_by_name) { $this->posted_by_name = $posted_by_name; }
    public function setEmployerAvatar($employer_avatar) { $this->employer_avatar = $employer_avatar; }
    public function setCategories($categories) { $this->categories = $categories; }
    
    // Status check methods
    public function isDraft() { return $this->status === 'draft'; }
    public function isPending() { return $this->status === 'pending'; }
    public function isApproved() { return $this->status === 'approved'; }
    public function isRejected() { return $this->status === 'rejected'; }
    public function isOverdue() { return $this->status === 'overdue'; }
    public function isSoftDeleted() { return $this->status === 'soft_deleted'; }
    
    // Get status badge color
    public function getStatusColor() {
        switch ($this->status) {
            case 'draft': return 'bg-gray-100 text-gray-800';
            case 'pending': return 'bg-yellow-100 text-yellow-800';
            case 'approved': return 'bg-green-100 text-green-800';
            case 'rejected': return 'bg-red-100 text-red-800';
            case 'overdue': return 'bg-blue-100 text-blue-800';
            case 'soft_deleted': return 'bg-purple-100 text-purple-800';
            default: return 'bg-gray-100 text-gray-800';
        }
    }
}
