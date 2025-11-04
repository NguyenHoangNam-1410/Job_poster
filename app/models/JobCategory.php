<?php
class JobCategory {
    private $id;
    private $category_name;
    
    // Constructor
    public function __construct($id = null, $category_name = null) {
        $this->id = $id;
        $this->category_name = $category_name;
    }
    
    // Getters
    public function getId() { 
        return $this->id; 
    }
    
    public function getCategoryName() { 
        return $this->category_name; 
    }
    
    // Setters
    public function setId($id) { 
        $this->id = $id; 
    }
    
    public function setCategoryName($category_name) { 
        $this->category_name = $category_name; 
    }
}
