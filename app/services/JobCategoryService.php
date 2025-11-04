<?php
require_once __DIR__ . '/../dao/JobCategoryDAO.php';

class JobCategoryService {
    private $categoryDAO;

    public function __construct() {
        $this->categoryDAO = new JobCategoryDAO();
    }

    // Get all categories with pagination and search
    public function getAllCategories($search, $per_page, $offset) {
        return $this->categoryDAO->getAll($search, $per_page, $offset);
    }

    // Get all categories without pagination (for dropdowns in other features)
    public function getAllCategoriesForSelect() {
        return $this->categoryDAO->getAll('', null, 0);
    }

    // Get total count for pagination
    public function getTotalCount($search) {
        return $this->categoryDAO->getTotalCount($search);
    }

    // Get category by ID
    public function getCategoryById($id) {
        return $this->categoryDAO->getById($id);
    }

    // Create new category
    public function createCategory($data) {
        // Validate category name
        if (empty($data['category_name'])) {
            throw new Exception("Category name is required.");
        }

        // Trim and validate
        $categoryName = trim($data['category_name']);
        if (strlen($categoryName) < 2) {
            throw new Exception("Category name must be at least 2 characters long.");
        }

        // Check if category name already exists
        if ($this->categoryDAO->categoryNameExists($categoryName)) {
            throw new Exception("Category name already exists.");
        }

        $category = new JobCategory(null, $categoryName);
        return $this->categoryDAO->create($category);
    }

    // Update category
    public function updateCategory($id, $data) {
        $category = $this->categoryDAO->getById($id);
        if (!$category) {
            throw new Exception("Category not found.");
        }

        // Validate category name
        if (empty($data['category_name'])) {
            throw new Exception("Category name is required.");
        }

        // Trim and validate
        $categoryName = trim($data['category_name']);
        if (strlen($categoryName) < 2) {
            throw new Exception("Category name must be at least 2 characters long.");
        }

        // Check if category name already exists (excluding current category)
        if ($this->categoryDAO->categoryNameExists($categoryName, $id)) {
            throw new Exception("Category name already exists.");
        }

        $category->setCategoryName($categoryName);
        return $this->categoryDAO->update($category);
    }

    // Delete category
    public function deleteCategory($id) {
        $category = $this->categoryDAO->getById($id);
        if (!$category) {
            throw new Exception("Category not found.");
        }

        // Check if category is used in jobs
        if ($this->categoryDAO->isUsedInJobs($id)) {
            $jobCount = $this->categoryDAO->getJobCount($id);
            throw new Exception("Cannot delete category '{$category->getCategoryName()}'. It is currently assigned to {$jobCount} job(s).");
        }

        return $this->categoryDAO->delete($id);
    }

    // Get job count for a category (useful for display)
    public function getJobCount($id) {
        return $this->categoryDAO->getJobCount($id);
    }

    // Check if category can be deleted
    public function canDelete($id) {
        return !$this->categoryDAO->isUsedInJobs($id);
    }
}
