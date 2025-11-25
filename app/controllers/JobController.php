<?php
require_once __DIR__ . '/../services/JobService.php';
require_once __DIR__ . '/../services/CompanyService.php';

class JobController
{
    private $jobService;
    private $companyService;

    public function __construct()
    {
        $this->jobService = new JobService();
        $this->companyService = new CompanyService();
    }

    private function getCurrentUserId()
    {
        return $_SESSION['user']['id'] ?? null;
    }

    public function index()
    {
        $search = $_GET['search'] ?? '';
        $categoryFilter = $_GET['category'] ?? '';
        $locationFilter = $_GET['location'] ?? '';
        $statusFilter = $_GET['status'] ?? '';
        $per_page = isset($_GET['per_page']) ? (int) $_GET['per_page'] : 10;
        $current_page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

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

    public function edit($id)
    {
        try {
            $job = $this->jobService->getJobById($id);

            if (!$job) {
                header('Location: /Worknest/public/jobs-manage?error=' . urlencode('Job not found'));
                exit;
            }

            // Get categories for the form
            $categories = $this->jobService->getAllCategories();
            $error = null;

            require_once __DIR__ . '/../views/staff/jobs/form.php';
        } catch (Exception $e) {
            header('Location: /Worknest/public/jobs-manage?error=' . urlencode($e->getMessage()));
            exit;
        }
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

            try {
                $currentUserId = $this->getCurrentUserId();
                $success = $this->jobService->updateJob($id, $_POST, $currentUserId);
                if ($success) {
                    if ($isAjax) {
                        header('Content-Type: application/json');
                        http_response_code(200);
                        echo json_encode([
                            'success' => true,
                            'message' => 'Job updated successfully'
                        ]);
                        exit;
                    } else {
                        header('Location: /Worknest/public/jobs-manage?success=' . urlencode('Job updated successfully'));
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
                    $job = $this->jobService->getJobById($id);
                    $categories = $this->jobService->getAllCategories();
                    require_once __DIR__ . '/../views/staff/jobs/form.php';
                }
            }
        }
    }

    // Soft delete
    public function softDelete($id)
    {
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

        try {
            $currentUserId = $this->getCurrentUserId();
            $result = $this->jobService->softDeleteJob($id, $currentUserId);
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
                    header('Location: /Worknest/public/jobs-manage?success=' . urlencode('Job soft deleted successfully'));
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
                header('Location: /Worknest/public/jobs-manage?error=' . urlencode($e->getMessage()));
                exit;
            }
        }
    }

    // Hard delete
    public function hardDelete($id)
    {
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

        try {
            $currentUserId = $this->getCurrentUserId();
            $result = $this->jobService->hardDeleteJob($id, $currentUserId);
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
                    header('Location: /Worknest/public/jobs-manage?success=' . urlencode('Job permanently deleted'));
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
                header('Location: /Worknest/public/jobs-manage?error=' . urlencode($e->getMessage()));
                exit;
            }
        }
    }

    // Change status
    public function changeStatus($id)
    {
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
                        header('Location: /Worknest/public/jobs-manage?success=' . urlencode('Job status changed successfully'));
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
                    header('Location: /Worknest/public/jobs-manage?error=' . urlencode($e->getMessage()));
                    exit;
                }
            }
        }
    }

    // Restore soft deleted job
    public function restore($id)
    {
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

        try {
            $currentUserId = $this->getCurrentUserId();
            $result = $this->jobService->restoreJob($id, $currentUserId);
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
                    header('Location: /Worknest/public/jobs-manage?success=' . urlencode('Job restored successfully'));
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
                header('Location: /Worknest/public/jobs-manage?error=' . urlencode($e->getMessage()));
                exit;
            }
        }
    }

    // Job Approval - List pending and rejected jobs
    public function approvalIndex()
    {
        $search = $_GET['search'] ?? '';
        $statusFilter = $_GET['status'] ?? '';
        $per_page = isset($_GET['per_page']) ? (int) $_GET['per_page'] : 10;
        $current_page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

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
    public function approvalDetail($id)
    {
        try {
            $job = $this->jobService->getJobById($id);

            if (!$job) {
                header('Location: /Worknest/public/approvals?error=' . urlencode('Job not found'));
                exit;
            }

            // Get previous review if exists
            $previousReview = $this->jobService->getLatestReview($id);

            $error = null;
            require_once __DIR__ . '/../views/staff/jobs_approval/detail.php';
        } catch (Exception $e) {
            header('Location: /Worknest/public/approvals?error=' . urlencode($e->getMessage()));
            exit;
        }
    }

    // Job Approval - Approve
    public function approveJob($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $reason = $_POST['reason'] ?? null; // Optional reason/notes
                $currentUserId = $this->getCurrentUserId();
                $success = $this->jobService->approveJobWithReview($id, $currentUserId, $reason);

                if ($success) {
                    header('Location: /Worknest/public/approvals?success=' . urlencode('Job approved successfully'));
                    exit;
                }
            } catch (Exception $e) {
                header('Location: /Worknest/public/approvals/detail/' . $id . '?error=' . urlencode($e->getMessage()));
                exit;
            }
        }
    }

    // Job Approval - Reject
    public function rejectJob($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $reason = $_POST['reason'] ?? '';
                if (empty($reason)) {
                    throw new Exception('Rejection reason is required');
                }

                $currentUserId = $this->getCurrentUserId();
                $success = $this->jobService->rejectJobWithReview($id, $currentUserId, $reason);

                if ($success) {
                    header('Location: /Worknest/public/approvals?success=' . urlencode('Job rejected successfully'));
                    exit;
                }
            } catch (Exception $e) {
                header('Location: /Worknest/public/approvals/detail/' . $id . '?error=' . urlencode($e->getMessage()));
                exit;
            }
        }
    }

    public function myJobs()
    {
        $userId = $this->getCurrentUserId();
        
        // Use EmployerDAO to get employer (same pattern as employer home)
        require_once __DIR__ . '/../dao/EmployerDAO.php';
        require_once __DIR__ . '/../models/Employer.php';
        $employerDAO = new EmployerDAO();
        $employer = $employerDAO->getEmployerByUserId($userId);

        // If no employer record exists, create one automatically
        if (!$employer) {
            $newEmployer = new Employer(
                null,  // id (auto-generated)
                null,  // company_name
                null,  // website
                null,  // logo
                null,  // contact_phone
                null,  // contact_email
                null,  // contact_person
                null,  // description
                $userId  // user_id
            );
            $createdId = $employerDAO->create($newEmployer);
            if (!$createdId) {
                throw new Exception("Failed to create employer profile.");
            }
            $employer = $employerDAO->getEmployerByUserId($userId);
            if (!$employer) {
                throw new Exception("Failed to retrieve employer profile after creation.");
            }
        }

        $employerId = $employer->getId();

        // Filters
        $search = $_GET['search'] ?? '';
        $categoryFilter = $_GET['category'] ?? '';
        $locationFilter = $_GET['location'] ?? '';
        $statusFilter = $_GET['status'] ?? '';
        $dateFrom = $_GET['date_from'] ?? '';
        $dateTo = $_GET['date_to'] ?? '';
        $per_page = isset($_GET['per_page']) ? (int) $_GET['per_page'] : 10;
        $current_page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

        if (!in_array($per_page, [10, 25, 50]))
            $per_page = 10;
        $offset = ($current_page - 1) * $per_page;

        $allowedStatuses = ['draft', 'pending', 'approved', 'overdue', 'rejected', 'soft_deleted'];
        if (!empty($statusFilter) && !in_array($statusFilter, $allowedStatuses)) {
            $statusFilter = '';
        }
        // If no status filter is selected, default to show all statuses EXCEPT soft_deleted
        $defaultStatuses = ['draft', 'pending', 'approved', 'overdue', 'rejected'];
        $statusesToQuery = !empty($statusFilter) ? $statusFilter : implode(',', $defaultStatuses);

        $total_records = $this->jobService->getTotalCountByEmployer(
            $employerId,
            $search,
            $categoryFilter,
            $locationFilter,
            $statusesToQuery,
            $dateFrom,
            $dateTo
        );

        $total_pages = ceil($total_records / $per_page);

        $jobs = $this->jobService->getJobsByEmployer(
            $employerId,
            $search,
            $categoryFilter,
            $locationFilter,
            $statusesToQuery,
            $dateFrom,
            $dateTo,
            $per_page,
            $offset
        );

        // Filters options
        $categories = $this->jobService->getAllCategories();
        $locations = $this->jobService->getUniqueLocationsByEmployerId($employerId);

        $pagination = [
            'current_page' => $current_page,
            'per_page' => $per_page,
            'total_records' => $total_records,
            'total_pages' => $total_pages,
            'search' => $search,
            'category_filter' => $categoryFilter,
            'location_filter' => $locationFilter,
            'status_filter' => $statusFilter,
            'date_from' => $dateFrom,
            'date_to' => $dateTo
        ];

        require_once __DIR__ . '/../views/employer/jobs/list.php';
    }

    public function myJobDetail($id)
    {
        try {
            $job = $this->jobService->getJobById($id);
            $jobReview = $this->jobService->getLatestReview($id)['reason'] ?? null;

            if (!$job) {
                header('Location: /Worknest/public/my-jobs?error=' . urlencode('Job not found'));
                exit;
            }

            require_once __DIR__ . '/../views/employer/jobs/details.php';
        } catch (Exception $e) {
            header('Location: /Worknest/public/my-jobs?error=' . urlencode($e->getMessage()));
            exit;
        }
    }

    public function myJobCreate()
    {
        $employer = $this->companyService->getEmployerByUserId($this->getCurrentUserId());
        if (
            !$employer || !$employer->getLogo() || !$employer->getCompanyName() ||
            !$employer->getContactPerson() || !$employer->getContactEmail()
            || !$employer->getContactPhone() || !$employer->getWebsite() || !$employer->getDescription()
        ) {
            // No company profile => direct to update company profile
            $_SESSION['error_profile'] = "You need to complete your company profile before posting a job!";
            $_SESSION['job_posting_flow'] = true; // Mark that we're in job posting flow

            // Check if this is an AJAX request (modal)
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                // Load company profile form in modal instead
                header('Location: /Worknest/public/company-profile');
                exit;
            }

            header('Location: /Worknest/public/company-profile');
            exit;
        }

        // Always load categories before loading the view
        $categories = $this->jobService->getAllCategories();

        require_once __DIR__ . '/../views/employer/jobs/newJob.php';
    }

    public function myJobStore()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $currentUserId = $this->getCurrentUserId();
                $employer = $this->companyService->getEmployerByUserId($currentUserId);
                if (!$employer) {
                    throw new Exception("You need a company profile to post a job.");
                }
                $employerId = $employer->getId();

                // Determine status: post_job = pending, save_draft = draft
                $action = $_POST['action'] ?? 'save_draft';
                $status = ($action === 'post_job') ? 'pending' : 'draft';
                
                // Debug: Log the action and status
                error_log("DEBUG myJobStore - Action: " . $action . ", Status: " . $status);
                
                // Force override: if action is post_job, ALWAYS set status to pending
                if ($action === 'post_job') {
                    $status = 'pending';
                } else {
                    $status = 'draft';
                }
                
                error_log("DEBUG myJobStore - Final Status: " . $status);

                $data = [
                    'employer_id' => $employerId,
                    'title' => $_POST['title'] ?? '',
                    'location' => $_POST['location'] ?? null,
                    'salary' => $_POST['salary'] ?? null,
                    'deadline' => $_POST['deadline'] ?? null,
                    'description' => $_POST['description'] ?? null,
                    'requirements' => $_POST['requirements'] ?? null,
                    'categories' => $_POST['categories'] ?? [],
                    'status' => $status
                ];

                // Validate required fields if posting
                if ($status === 'pending') {
                    if (empty($data['title']))
                        throw new Exception("Job title is required.");
                    if (empty($data['description']))
                        throw new Exception("Job description is required.");
                    if (empty($data['requirements']))
                        throw new Exception("Job requirements are required.");
                    if (empty($data['categories']))
                        throw new Exception("At least one job category must be selected.");
                }

                $jobId = $this->jobService->createJob($data, $currentUserId);

                if ($jobId) {
                    $message = ($status === 'pending') ? 'Job posted successfully' : 'Draft saved successfully';

                    // Check if this is an AJAX request (modal)
                    $headers = function_exists('getallheaders') ? getallheaders() : [];
                    $isAjax = (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
                        || (isset($headers['X-Requested-With']) && strtolower($headers['X-Requested-With']) === 'xmlhttprequest')
                        || isset($_GET['ajax'])
                        || isset($_POST['ajax']);

                    if ($isAjax) {
                        header('Content-Type: application/json');
                        echo json_encode([
                            'success' => true,
                            'message' => $message
                        ]);
                        exit;
                    }

                    header('Location: /Worknest/public/my-jobs?success=' . urlencode($message));
                    exit;
                } else {
                    throw new Exception("Failed to create job.");
                }
            } catch (Exception $e) {
                // Check if this is an AJAX request (modal)
                $headers = function_exists('getallheaders') ? getallheaders() : [];
                $isAjax = (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
                    || (isset($headers['X-Requested-With']) && strtolower($headers['X-Requested-With']) === 'xmlhttprequest')
                    || isset($_GET['ajax'])
                    || isset($_POST['ajax']);

                if ($isAjax) {
                    header('Content-Type: application/json');
                    echo json_encode([
                        'success' => false,
                        'message' => $e->getMessage()
                    ]);
                    exit;
                }

                $error = $e->getMessage();
                $categories = $this->jobService->getAllCategories();
                require_once __DIR__ . '/../views/employer/jobs/newJob.php';
            }
        }
    }

    public function myJobEdit($id)
    {
        try {
            $job = $this->jobService->getJobById($id);
            $jobReview = $this->jobService->getLatestReview($id)['reason'] ?? null;
            if (!$job) {
                header('Location: /Worknest/public/my-jobs?error=' . urlencode('Job not found'));
                exit;
            }

            // Get categories for the form
            $categories = $this->jobService->getAllCategories();

            require_once __DIR__ . '/../views/employer/jobs/form.php';
        } catch (Exception $e) {
            header('Location: /Worknest/public/my-jobs?error=' . urlencode($e->getMessage()));
            exit;
        }
    }

    public function myJobUpdate($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $job = $this->jobService->getJobById($id);
                if (!$job) {
                    $headers = function_exists('getallheaders') ? getallheaders() : [];
                    $isAjax = (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
                        || (isset($headers['X-Requested-With']) && strtolower($headers['X-Requested-With']) === 'xmlhttprequest')
                        || isset($_GET['ajax'])
                        || isset($_POST['ajax']);

                    if ($isAjax) {
                        header('Content-Type: application/json');
                        echo json_encode(['success' => false, 'message' => 'Job not found']);
                        exit;
                    }
                    header("Location: /Worknest/public/my-jobs?error=" . urlencode("Job not found"));
                    exit;
                }
                // Determine status based on action
                $action = $_POST['action'] ?? 'save_draft';
                $newStatus = $job->getStatus(); // Keep current status by default

                if ($action === 'post_job') {
                    $newStatus = 'pending'; // Submit for approval
                } elseif ($action === 'save_draft') {
                    $newStatus = 'draft'; // Keep as draft
                } elseif ($action === 'save_changes') {
                    $newStatus = $job->getStatus(); // Keep current status (rejected, approved, overdue)
                }

                // Collect form data
                $data = [
                    'title' => $_POST['title'] ?? $job->getTitle(),
                    'location' => $_POST['location'] ?? $job->getLocation(),
                    'salary' => $_POST['salary'] ?? $job->getSalary(),
                    'deadline' => $_POST['deadline'] ?? $job->getDeadline(),
                    'description' => $_POST['description'] ?? $job->getDescription(),
                    'requirements' => $_POST['requirements'] ?? $job->getRequirements(),
                    'categories' => $_POST['categories'] ?? $job->getCategories(),
                    'status' => $newStatus
                ];
                $updatedJob = $this->jobService->updateJob($id, $data, $this->getCurrentUserId());
                if (!$updatedJob) {
                    throw new Exception("Failed to update job.");
                }

                // Check if this is an AJAX request
                $headers = function_exists('getallheaders') ? getallheaders() : [];
                $isAjax = (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
                    || (isset($headers['X-Requested-With']) && strtolower($headers['X-Requested-With']) === 'xmlhttprequest')
                    || isset($_GET['ajax'])
                    || isset($_POST['ajax']);

                $successMessage = ($action === 'post_job') ? 'Job posted successfully' : (($action === 'save_draft') ? 'Job saved as draft' : 'Job updated successfully');

                if ($isAjax) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => true, 'message' => $successMessage]);
                    exit;
                }

                header('Location: /Worknest/public/my-jobs?success=' . urlencode($successMessage));
                exit;
            } catch (Exception $e) {
                // Check if this is an AJAX request
                $headers = function_exists('getallheaders') ? getallheaders() : [];
                $isAjax = (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
                    || (isset($headers['X-Requested-With']) && strtolower($headers['X-Requested-With']) === 'xmlhttprequest')
                    || isset($_GET['ajax'])
                    || isset($_POST['ajax']);

                if ($isAjax) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                    exit;
                }

                header('Location: /Worknest/public/my-jobs?error=' . urlencode($e->getMessage()));
                exit;
            }
        }
    }

    // Only soft delete for employer's own jobs if it's not a draft
    public function myJobSoftDelete($id)
    {
        try {
            $job = $this->jobService->getJobById($id);

            if (!$job) {
                header('Location: /Worknest/public/my-jobs?error=' . urlencode('Job not found'));
                exit;
            }

            $status = ['approved', 'pending', 'rejected', 'overdue'];

            if (!in_array($job->getStatus(), $status)) {
                throw new Exception("Only jobs in 'approved', 'pending', 'rejected', or 'overdue' status can be soft deleted.");
            }

            $this->jobService->softDeleteJob($id);
            header('Location: /Worknest/public/my-jobs?success=' . urlencode('Job deleted successfully'));
            exit;
        } catch (Exception $e) {
            header('Location: /Worknest/public/my-jobs?error=' . urlencode($e->getMessage()));
            exit;
        }
    }

    // Only hard delete for employer's own jobs if it's a draft
    public function myJobHardDelete($id)
    {
        try {
            $job = $this->jobService->getJobById($id);

            if (!$job) {
                header('Location: /Worknest/public/my-jobs?error=' . urlencode('Job not found'));
                exit;
            }

            if ($job->getStatus() !== 'draft') {
                throw new Exception("Only jobs in 'draft' status can be hard deleted.");
            }
            $this->jobService->hardDeleteJob($id);
            header('Location: /Worknest/public/my-jobs?success=' . urlencode('Job deleted successfully'));
            exit;
        } catch (Exception $e) {
            header('Location: /Worknest/public/my-jobs?error=' . urlencode($e->getMessage()));
            exit;
        }
    }

    public function createNewJob()
    {
        require_once __DIR__ . '/../views/employer/jobs/form.php';
    }

    // Handle status-only changes (approved <-> overdue)
    public function myJobStatusChange($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $job = $this->jobService->getJobById($id);
                if (!$job) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'Job not found']);
                    exit;
                }

                // Get JSON body
                $input = file_get_contents('php://input');
                $data = json_decode($input, true);
                $action = $data['action'] ?? '';

                $currentStatus = $job->getStatus();
                $newStatus = '';
                $message = '';

                if ($action === 'mark_overdue' && $currentStatus === 'approved') {
                    $newStatus = 'overdue';
                    $message = 'Job closed successfully';
                } elseif ($action === 'reapprove' && $currentStatus === 'overdue') {
                    $newStatus = 'approved';
                    $message = 'Job reopened successfully';
                } else {
                    throw new Exception('Invalid action or status combination');
                }

                // Update only the status
                $this->jobService->updateJobStatus($id, $newStatus);

                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => $message]);
                exit;
            } catch (Exception $e) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                exit;
            }
        }
    }
}
