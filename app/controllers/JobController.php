<?php
require_once __DIR__ . '/../services/JobService.php';

class JobController {
    private $jobService;
    
    public function __construct() {
        $this->jobService = new JobService();
    }

    private function getCurrentUserId() {
        // TODO: Replace with actual session-based user ID when authentication is implemented
        return $_SESSION['user_id'] ?? 1;
    }

    public function index() {
        $search = $_GET['search'] ?? '';
        $categoryFilter = $_GET['category'] ?? '';
        $locationFilter = $_GET['location'] ?? '';
        $statusFilter = $_GET['status'] ?? '';
        $per_page = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;
        $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        if (!in_array($per_page, [10, 25, 50])) {
            $per_page = 10;
        }

        // Define allowed statuses for staff view
        $allowedStatuses = ['approved', 'overdue', 'soft_deleted'];
        
        // If a specific status is selected, validate it's in allowed list
        if (!empty($statusFilter) && !in_array($statusFilter, $allowedStatuses)) {
            $statusFilter = '';
        }
        
        // If no status filter is selected, default to show only allowed statuses
        $statusesToQuery = !empty($statusFilter) ? $statusFilter : implode(',', $allowedStatuses);

        $offset = ($current_page - 1) * $per_page;

        $total_records = $this->jobService->getTotalCount($search, $categoryFilter, $locationFilter, $statusesToQuery);
        $total_pages = ceil($total_records / $per_page);

        $jobs = $this->jobService->getAllJobs($search, $categoryFilter, $locationFilter, $statusesToQuery, $per_page, $offset);

        // Get filter options
        $categories = $this->jobService->getAllCategories();
        $locations = $this->jobService->getUniqueLocations();

        $pagination = [
            'current_page' => $current_page,
            'per_page' => $per_page,
            'total_records' => $total_records,
            'total_pages' => $total_pages,
            'search' => $search,
            'category_filter' => $categoryFilter,
            'location_filter' => $locationFilter,
            'status_filter' => $statusFilter
        ];

        require_once __DIR__ . '/../views/staff/jobs/list.php';
    }

    public function edit($id) {
        try {
            $job = $this->jobService->getJobById($id);
            
            if (!$job) {
                header('Location: /Job_poster/public/jobs?error=' . urlencode('Job not found'));
                exit;
            }

            // Get categories for the form
            $categories = $this->jobService->getAllCategories();
            $error = null;
            
            require_once __DIR__ . '/../views/staff/jobs/form.php';
        } catch (Exception $e) {
            header('Location: /Job_poster/public/jobs?error=' . urlencode($e->getMessage()));
            exit;
        }
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $success = $this->jobService->updateJob($id, $_POST);
                if ($success) {
                    header('Location: /Job_poster/public/jobs?success=' . urlencode('Job updated successfully'));
                    exit;
                }
            } catch (Exception $e) {
                $error = $e->getMessage();
                $job = $this->jobService->getJobById($id);
                $categories = $this->jobService->getAllCategories();
                require_once __DIR__ . '/../views/staff/jobs/form.php';
            }
        }
    }

    // Soft delete
    public function softDelete($id) {
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

        try {
            $result = $this->jobService->softDeleteJob($id);
            if ($result) {
                if ($isAjax) {
                    header('Content-Type: application/json');
                    http_response_code(200);
                    echo json_encode([
                        'success' => true,
                        'message' => 'Job soft deleted successfully'
                    ]);
                    exit;
                } else {
                    header('Location: /Job_poster/public/jobs?success=' . urlencode('Job soft deleted successfully'));
                    exit;
                }
            } else {
                throw new Exception('Soft delete operation failed');
            }
        } catch (Exception $e) {
            error_log("Soft delete job error: " . $e->getMessage());
            if ($isAjax) {
                header('Content-Type: application/json');
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
                exit;
            } else {
                header('Location: /Job_poster/public/jobs?error=' . urlencode($e->getMessage()));
                exit;
            }
        }
    }

    // Hard delete
    public function hardDelete($id) {
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

        try {
            $result = $this->jobService->hardDeleteJob($id);
            if ($result) {
                if ($isAjax) {
                    header('Content-Type: application/json');
                    http_response_code(200);
                    echo json_encode([
                        'success' => true,
                        'message' => 'Job permanently deleted'
                    ]);
                    exit;
                } else {
                    header('Location: /Job_poster/public/jobs?success=' . urlencode('Job permanently deleted'));
                    exit;
                }
            } else {
                throw new Exception('Hard delete operation failed');
            }
        } catch (Exception $e) {
            error_log("Hard delete job error: " . $e->getMessage());
            if ($isAjax) {
                header('Content-Type: application/json');
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
                exit;
            } else {
                header('Location: /Job_poster/public/jobs?error=' . urlencode($e->getMessage()));
                exit;
            }
        }
    }

    // Change status
    public function changeStatus($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                      strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

            try {
                $newStatus = $_POST['status'] ?? '';
                if (empty($newStatus)) {
                    throw new Exception('Status is required');
                }

                $currentUserId = $this->getCurrentUserId();
                $result = $this->jobService->changeStatus($id, $newStatus, $currentUserId);
                
                if ($result) {
                    if ($isAjax) {
                        header('Content-Type: application/json');
                        http_response_code(200);
                        echo json_encode([
                            'success' => true,
                            'message' => 'Job status changed successfully'
                        ]);
                        exit;
                    } else {
                        header('Location: /Job_poster/public/jobs?success=' . urlencode('Job status changed successfully'));
                        exit;
                    }
                } else {
                    throw new Exception('Status change operation failed');
                }
            } catch (Exception $e) {
                error_log("Change status error: " . $e->getMessage());
                if ($isAjax) {
                    header('Content-Type: application/json');
                    http_response_code(500);
                    echo json_encode([
                        'success' => false,
                        'message' => $e->getMessage()
                    ]);
                    exit;
                } else {
                    header('Location: /Job_poster/public/jobs?error=' . urlencode($e->getMessage()));
                    exit;
                }
            }
        }
    }

    // Restore soft deleted job
    public function restore($id) {
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

        try {
            $result = $this->jobService->restoreJob($id);
            if ($result) {
                if ($isAjax) {
                    header('Content-Type: application/json');
                    http_response_code(200);
                    echo json_encode([
                        'success' => true,
                        'message' => 'Job restored successfully'
                    ]);
                    exit;
                } else {
                    header('Location: /Job_poster/public/jobs?success=' . urlencode('Job restored successfully'));
                    exit;
                }
            } else {
                throw new Exception('Restore operation failed');
            }
        } catch (Exception $e) {
            error_log("Restore job error: " . $e->getMessage());
            if ($isAjax) {
                header('Content-Type: application/json');
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
                exit;
            } else {
                header('Location: /Job_poster/public/jobs?error=' . urlencode($e->getMessage()));
                exit;
            }
        }
    }

    // Job Approval - List pending and rejected jobs
    public function approvalIndex() {
        $search = $_GET['search'] ?? '';
        $statusFilter = $_GET['status'] ?? '';
        $per_page = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;
        $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        if (!in_array($per_page, [10, 25, 50])) {
            $per_page = 10;
        }

        // Define allowed statuses for approval view (only pending and rejected)
        $allowedStatuses = ['pending', 'rejected'];
        
        // If a specific status is selected, validate it's in allowed list
        if (!empty($statusFilter) && !in_array($statusFilter, $allowedStatuses)) {
            $statusFilter = '';
        }
        
        // If no status filter is selected, default to show only allowed statuses
        $statusesToQuery = !empty($statusFilter) ? $statusFilter : implode(',', $allowedStatuses);

        $offset = ($current_page - 1) * $per_page;

        $total_records = $this->jobService->getTotalCount($search, '', '', $statusesToQuery);
        $total_pages = ceil($total_records / $per_page);

        $jobs = $this->jobService->getAllJobs($search, '', '', $statusesToQuery, $per_page, $offset);

        $pagination = [
            'current_page' => $current_page,
            'per_page' => $per_page,
            'total_records' => $total_records,
            'total_pages' => $total_pages,
            'search' => $search,
            'status_filter' => $statusFilter
        ];

        require_once __DIR__ . '/../views/staff/jobs_approval/list.php';
    }

    // Job Approval - View detail
    public function approvalDetail($id) {
        try {
            $job = $this->jobService->getJobById($id);
            
            if (!$job) {
                header('Location: /Job_poster/public/jobs/approval?error=' . urlencode('Job not found'));
                exit;
            }

            // Get previous review if exists
            $previousReview = $this->jobService->getLatestReview($id);
            
            $error = null;
            require_once __DIR__ . '/../views/staff/jobs_approval/detail.php';
        } catch (Exception $e) {
            header('Location: /Job_poster/public/jobs/approval?error=' . urlencode($e->getMessage()));
            exit;
        }
    }

    // Job Approval - Approve
    public function approveJob($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $reason = $_POST['reason'] ?? null; // Optional reason/notes
                $currentUserId = $this->getCurrentUserId();
                $success = $this->jobService->approveJobWithReview($id, $currentUserId, $reason);
                
                if ($success) {
                    header('Location: /Job_poster/public/jobs/approval?success=' . urlencode('Job approved successfully'));
                    exit;
                }
            } catch (Exception $e) {
                header('Location: /Job_poster/public/jobs/approval/detail/' . $id . '?error=' . urlencode($e->getMessage()));
                exit;
            }
        }
    }

    // Job Approval - Reject
    public function rejectJob($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $reason = $_POST['reason'] ?? '';
                if (empty($reason)) {
                    throw new Exception('Rejection reason is required');
                }

                $currentUserId = $this->getCurrentUserId();
                $success = $this->jobService->rejectJobWithReview($id, $currentUserId, $reason);
                
                if ($success) {
                    header('Location: /Job_poster/public/jobs/approval?success=' . urlencode('Job rejected successfully'));
                    exit;
                }
            } catch (Exception $e) {
                header('Location: /Job_poster/public/jobs/approval/detail/' . $id . '?error=' . urlencode($e->getMessage()));
                exit;
            }
        }
    }
}
