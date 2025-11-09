<?php
// filepath: c:\xampp\htdocs\Job_poster\public\index.php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Get the request URI
$request = $_SERVER['REQUEST_URI'];
$path = parse_url($request, PHP_URL_PATH);

// Remove the base path if Job_poster is in a subdirectory
$path = str_replace('/Job_poster/public', '', $path);

// Route handling

// User CRUD Routes
if ($path === '/users') {
    // List all users
    require_once '../app/controllers/UserController.php';
    $controller = new UserController();
    $controller->index();
    exit;
    
} elseif ($path === '/users/create') {
    // Show create form
    require_once '../app/controllers/UserController.php';
    $controller = new UserController();
    $controller->create();
    
} elseif ($path === '/users/store' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Store new user
    require_once '../app/controllers/UserController.php';
    $controller = new UserController();
    $controller->store();
    
} elseif (preg_match('/^\/users\/edit\/(\d+)$/', $path, $matches)) {
    // Show edit form
    require_once '../app/controllers/UserController.php';
    $controller = new UserController();
    $controller->edit($matches[1]);
    
} elseif (preg_match('/^\/users\/update\/(\d+)$/', $path, $matches) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update user
    require_once '../app/controllers/UserController.php';
    $controller = new UserController();
    $controller->update($matches[1]);
    
} elseif (preg_match('/^\/users\/delete\/(\d+)$/', $path, $matches)) {
    // Delete user
    require_once '../app/controllers/UserController.php';
    $controller = new UserController();
    $controller->destroy($matches[1]);

// Job Category CRUD Routes
} elseif ($path === '/job-categories') {
    // List all job categories
    require_once '../app/controllers/JobCategoryController.php';
    $controller = new JobCategoryController();
    $controller->index();
    exit;
    
} elseif ($path === '/job-categories/create') {
    // Show create form
    require_once '../app/controllers/JobCategoryController.php';
    $controller = new JobCategoryController();
    $controller->create();
    
} elseif ($path === '/job-categories/store' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Store new job category
    require_once '../app/controllers/JobCategoryController.php';
    $controller = new JobCategoryController();
    $controller->store();
    
} elseif (preg_match('/^\/job-categories\/edit\/(\d+)$/', $path, $matches)) {
    // Show edit form
    require_once '../app/controllers/JobCategoryController.php';
    $controller = new JobCategoryController();
    $controller->edit($matches[1]);
    
} elseif (preg_match('/^\/job-categories\/update\/(\d+)$/', $path, $matches) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update job category
    require_once '../app/controllers/JobCategoryController.php';
    $controller = new JobCategoryController();
    $controller->update($matches[1]);
    
} elseif (preg_match('/^\/job-categories\/delete\/(\d+)$/', $path, $matches)) {
    // Delete job category
    require_once '../app/controllers/JobCategoryController.php';
    $controller = new JobCategoryController();
    $controller->destroy($matches[1]);

// Job Management Routes (Staff/Admin)
} elseif ($path === '/jobs-manage') {
    // List all jobs
    require_once '../app/controllers/JobController.php';
    $controller = new JobController();
    $controller->index();
    
} elseif (preg_match('/^\/jobs\/edit\/(\d+)$/', $path, $matches)) {
    // Show edit form
    require_once '../app/controllers/JobController.php';
    $controller = new JobController();
    $controller->edit($matches[1]);
    
} elseif (preg_match('/^\/jobs\/update\/(\d+)$/', $path, $matches) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update job
    require_once '../app/controllers/JobController.php';
    $controller = new JobController();
    $controller->update($matches[1]);
    
} elseif (preg_match('/^\/jobs\/soft-delete\/(\d+)$/', $path, $matches)) {
    // Soft delete job
    require_once '../app/controllers/JobController.php';
    $controller = new JobController();
    $controller->softDelete($matches[1]);
    
} elseif (preg_match('/^\/jobs\/hard-delete\/(\d+)$/', $path, $matches)) {
    // Hard delete job
    require_once '../app/controllers/JobController.php';
    $controller = new JobController();
    $controller->hardDelete($matches[1]);
    
} elseif (preg_match('/^\/jobs\/restore\/(\d+)$/', $path, $matches)) {
    // Restore soft deleted job
    require_once '../app/controllers/JobController.php';
    $controller = new JobController();
    $controller->restore($matches[1]);
    
} elseif (preg_match('/^\/jobs\/change-status\/(\d+)$/', $path, $matches) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Change job status
    require_once '../app/controllers/JobController.php';
    $controller = new JobController();
    $controller->changeStatus($matches[1]);

// Job Approval Routes (Staff/Admin)
} elseif ($path === '/approvals') {
    // List jobs pending approval
    require_once '../app/controllers/JobController.php';
    $controller = new JobController();
    $controller->approvalIndex();
    
} elseif (preg_match('/^\/approvals\/detail\/(\d+)$/', $path, $matches)) {
    // Show job detail for approval
    require_once '../app/controllers/JobController.php';
    $controller = new JobController();
    $controller->approvalDetail($matches[1]);
    
} elseif (preg_match('/^\/approvals\/approve\/(\d+)$/', $path, $matches) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Approve job
    require_once '../app/controllers/JobController.php';
    $controller = new JobController();
    $controller->approveJob($matches[1]);
    
} elseif (preg_match('/^\/approvals\/reject\/(\d+)$/', $path, $matches) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Reject job
    require_once '../app/controllers/JobController.php';
    $controller = new JobController();
    $controller->rejectJob($matches[1]);


// Staff Action Logs Routes (Admin)
} elseif ($path === '/staff-actions') {
    // List staff actions
    require_once '../app/controllers/StaffActionController.php';
    $controller = new StaffActionController();
    $controller->index();
} 

// Staff Action Logs Routes (Admin)
elseif ($path === '/feedbacks') {
    // List feedbacks
    require_once '../app/controllers/FeedbackController.php';
    $controller = new FeedbackController();
    $controller->index();
} 

// Statistics Routes (Admin)
elseif ($path === '/statistics') {
    // Show statistics dashboard
    require_once '../app/controllers/StatisticController.php';
    $controller = new StatisticController();
    $controller->index();
} 

// Profile Routes
elseif ($path === '/profile') {
    // Show profile edit form
    require_once '../app/controllers/UserController.php';
    $controller = new UserController();
    $controller->profile();
    
} elseif ($path === '/profile/update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update profile
    require_once '../app/controllers/UserController.php';
    $controller = new UserController();
    $controller->updateProfile();
} 

else {

    
    // Public routes

    if (preg_match('#^/jobs/show/(\d+)$#', $path, $m) || preg_match('#^/jobs/(\d+)$#', $path, $m)) {
        require_once '../app/controllers/PublicJobController.php';
        (new PublicJobController())->show((int)$m[1]);
        exit;
    }

    if ($path === '/jobs') {
        require_once '../app/controllers/PublicJobController.php';
        (new PublicJobController())->index();
        exit;
    }

    switch ($path) {

 /* ==== AJAX ENDPOINTS (thêm 2 case này) ==== */
 case '/ajax/jobs_filters.php':
    require __DIR__ . '/ajax/jobs_filters.php';
    exit;

case '/ajax/jobs_list.php':
    require __DIR__ . '/ajax/jobs_list.php';
    exit;

case '/ajax/jobs_related.php':
    require __DIR__ . '/ajax/jobs_related.php';
    exit;
/* ========================================= */

        case '/':
        case '/home':
            include '../app/views/public/home.php';
            break;

            case '/jobs':
                include '../app/views/public/jobs/jobslisting.php';
                break;
    
            case '/jobs/show':
                include '../app/views/public/jobs/show.php';
                break;
    
        
        default:
            http_response_code(404);
            include '../app/views/public/404.php';
            break;
    }
}
?>