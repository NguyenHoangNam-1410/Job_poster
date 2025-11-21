<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../models/JobCategory.php';

class JobCategoryDAO
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->conn;
    }

    // CREATE
    public function create(JobCategory $category)
    {
        $sql = "INSERT INTO JOB_CATEGORIES (category_name) VALUES (?)";
        $stmt = $this->db->prepare($sql);

        $categoryName = $category->getCategoryName();
        $stmt->bind_param("s", $categoryName);

        return $stmt->execute();
    }

    // READ - Get all categories with pagination and search
    public function getAll($search = '', $limit = null, $offset = 0)
    {
        $sql = "SELECT id, category_name FROM JOB_CATEGORIES WHERE 1=1";
        $params = [];
        $types = '';

        // Add search condition if search term provided
        if (!empty($search)) {
            $sql .= " AND category_name LIKE ?";
            $searchTerm = "%{$search}%";
            $params[] = $searchTerm;
            $types .= 's';
        }

        $sql .= " ORDER BY category_name ASC";

        if ($limit !== null) {
            $sql .= " LIMIT ? OFFSET ?";
            $params[] = $limit;
            $params[] = $offset;
            $types .= 'ii';
        }

        $stmt = $this->db->prepare($sql);

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $categories = [];

        while ($row = $result->fetch_assoc()) {
            $category = new JobCategory(
                $row['id'],
                $row['category_name']
            );
            $categories[] = $category;
        }
        return $categories;
    }

    // Get total count for pagination
    public function getTotalCount($search = '')
    {
        $sql = "SELECT COUNT(*) as total FROM JOB_CATEGORIES WHERE 1=1";
        $params = [];
        $types = '';

        if (!empty($search)) {
            $sql .= " AND category_name LIKE ?";
            $searchTerm = "%{$search}%";
            $params[] = $searchTerm;
            $types .= 's';
        }

        $stmt = $this->db->prepare($sql);

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
        $sql = "SELECT id, category_name FROM JOB_CATEGORIES WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            return new JobCategory(
                $row['id'],
                $row['category_name']
            );
        }
        return null;
    }

    // Check if category name exists (for unique validation)
    public function categoryNameExists($categoryName, $excludeId = null)
    {
        $sql = "SELECT id FROM JOB_CATEGORIES WHERE category_name = ?";
        $params = [$categoryName];
        $types = 's';

        if ($excludeId !== null) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
            $types .= 'i';
        }

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0;
    }

    // Check if category is used in jobs (before deletion)
    public function isUsedInJobs($id)
    {
        $sql = "SELECT COUNT(*) as count FROM JOB_CATEGORY_MAP WHERE category_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row['count'] > 0;
    }

    // Get count of jobs using this category
    public function getJobCount($id)
    {
        $sql = "SELECT COUNT(*) as count FROM JOB_CATEGORY_MAP WHERE category_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row['count'];
    }

    // UPDATE
    public function update(JobCategory $category)
    {
        $sql = "UPDATE JOB_CATEGORIES SET category_name = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);

        $categoryName = $category->getCategoryName();
        $id = $category->getId();

        $stmt->bind_param("si", $categoryName, $id);
        return $stmt->execute();
    }

    // DELETE
    public function delete($id)
    {
        try {
            // Check if used in jobs first
            if ($this->isUsedInJobs($id)) {
                throw new Exception("Cannot delete category. It is currently assigned to one or more jobs.");
            }

            $sql = "DELETE FROM JOB_CATEGORIES WHERE id = ?";
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
            error_log("JobCategoryDAO delete error: " . $e->getMessage());
            throw $e;
        }
    }
}
