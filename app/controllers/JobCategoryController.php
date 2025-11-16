<?php
require_once __DIR__ . '/../services/JobCategoryService.php';

class JobCategoryController {
    private $categoryService;
    
    public function __construct() {
        $this->categoryService = new JobCategoryService();
    }

    public function index() {
        $search = $_GET['search'] ?? '';
        $per_page = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;
        $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        if (!in_array($per_page, [10, 25])) {
            $per_page = 10;
        }

        $offset = ($current_page - 1) * $per_page;

        $total_records = $this->categoryService->getTotalCount($search);
        $total_pages = ceil($total_records / $per_page);

        $categories = $this->categoryService->getAllCategories($search, $per_page, $offset);

        // Get job counts for each category
        $categoriesWithCounts = [];
        foreach ($categories as $category) {
            $jobCount = $this->categoryService->getJobCount($category->getId());
            $categoriesWithCounts[] = [
                'category' => $category,
                'job_count' => $jobCount,
                'can_delete' => $jobCount == 0
            ];
        }

        $pagination = [
            'current_page' => $current_page,
            'per_page' => $per_page,
            'total_records' => $total_records,
            'total_pages' => $total_pages,
            'search' => $search
        ];

        require_once __DIR__ . '/../views/admin/job_categories/list.php';
    }

    public function create() {
        $category = null;
        $error = null;
        require_once __DIR__ . '/../views/admin/job_categories/form.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                      strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
            
            try {
                $success = $this->categoryService->createCategory($_POST);
                if ($success) {
                    if ($isAjax) {
                        header('Content-Type: application/json');
                        http_response_code(200);
                        echo json_encode([
                            'success' => true,
                            'message' => 'Category created successfully'
                        ]);
                        exit;
                    } else {
                        header('Location: /Job_poster/public/job-categories');
                        exit;
                    }
                }
            } catch (Exception $e) {
                if ($isAjax) {
                    header('Content-Type: application/json');
                    http_response_code(400);
                    echo json_encode([
                        'success' => false,
                        'message' => $e->getMessage()
                    ]);
                    exit;
                } else {
                    $error = $e->getMessage();
                    $category = null;
                    require_once __DIR__ . '/../views/admin/job_categories/form.php';
                }
            }
        }
    }

    public function edit($id) {
        try {
            $category = $this->categoryService->getCategoryById($id);
            
            if (!$category) {
                header('Location: /Job_poster/public/job-categories?error=' . urlencode('Category not found'));
                exit;
            }

            $error = null;
            require_once __DIR__ . '/../views/admin/job_categories/form.php';
        } catch (Exception $e) {
            header('Location: /Job_poster/public/job-categories?error=' . urlencode($e->getMessage()));
            exit;
        }
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                      strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
            
            try {
                $success = $this->categoryService->updateCategory($id, $_POST);
                if ($success) {
                    if ($isAjax) {
                        header('Content-Type: application/json');
                        http_response_code(200);
                        echo json_encode([
                            'success' => true,
                            'message' => 'Category updated successfully'
                        ]);
                        exit;
                    } else {
                        header('Location: /Job_poster/public/job-categories');
                        exit;
                    }
                }
            } catch (Exception $e) {
                if ($isAjax) {
                    header('Content-Type: application/json');
                    http_response_code(400);
                    echo json_encode([
                        'success' => false,
                        'message' => $e->getMessage()
                    ]);
                    exit;
                } else {
                    $error = $e->getMessage();
                    $category = $this->categoryService->getCategoryById($id);
                    require_once __DIR__ . '/../views/admin/job_categories/form.php';
                }
            }
        }
    }

    public function destroy($id) {
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

        try {
            $result = $this->categoryService->deleteCategory($id);
            if ($result) {
                if ($isAjax) {
                    header('Content-Type: application/json');
                    http_response_code(200);
                    echo json_encode([
                        'success' => true,
                        'message' => 'Category deleted successfully'
                    ]);
                    exit;
                } else {
                    header('Location: /Job_poster/public/job-categories');
                    exit;
                }
            } else {
                throw new Exception('Delete operation failed');
            }
        } catch (Exception $e) {
            error_log("Delete category error: " . $e->getMessage());
            if ($isAjax) {
                header('Content-Type: application/json');
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
                exit;
            } else {
                header('Location: /Job_poster/public/job-categories?error=' . urlencode($e->getMessage()));
                exit;
            }
        }
    }
}
