<?php
// filepath: c:\xampp\htdocs\Job_poster\public\index.php
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..'); // points to project root
$dotenv->load();

define('BASE_URL', '/Job_poster/public');
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Get the request URI
$request = $_SERVER['REQUEST_URI'];
$path = parse_url($request, PHP_URL_PATH);

// Remove the base path if Job_poster is in a subdirectory
$path = str_replace(BASE_URL, '', $path);

// Handle logout immediately (before access control checks)
if ($path === '/logout') {
    require_once '../app/controllers/AuthController.php';
    $controller = new AuthController();
    $controller->logout();
    exit;
}

$found = false;
$publicRoutes = ['/auth/login', '/auth/register',
                '/auth/register/local', '/auth/login/facebook', '/auth/login/facebook/callback',
                '/auth/login/google','/auth/login/forgot-password', '/auth/login/local', '/contact',
                '/auth/login/forgot-password/send-otp', '/auth/login/forgot-password/input-otp',
                '/auth/login/forgot-password/verify-otp', '/auth/login/forgot-password/reset-password-form',
                '/auth/login/forgot-password/reset-expired', '/check-email', '/public/home', '/jobs', '/jobs/show/:id',
                '/', '/auth/login/forgot-password/reset-password'];

$isPublic = false;
// Check if the route is public
foreach ($publicRoutes as $route) {
    if (preg_match('#^' . preg_replace('#:[^/]+#', '[^/]+', $route) . '$#', $path)) {
        $isPublic = true;
        break;
    }
}
// If not logged in and accessing private route
if (!isset($_SESSION['user']) && !$isPublic) {
    http_response_code(404);
    include '../app/views/public/404.php';
    exit;
} 

// If logged in and accessing public route (direct to home page)
if (isset($_SESSION['user']) && $isPublic) {
    if ($path !== '/public/home' && $path !== '/') {
        header('Location: ' . BASE_URL . '/home');
        exit;
    }
}

// Route handling
if ($path === '/auth/login/facebook') {
    require_once '../app/controllers/AuthController.php';
    $controller = new AuthController();
    $controller->handleFacebookLogin();
} elseif ($path === '/auth/login/facebook/callback') {
    require_once '../app/controllers/AuthController.php';
    $controller = new AuthController();
    $controller->facebookCallback();
} elseif ($path === '/auth/login/google') {
    require_once '../app/controllers/AuthController.php';
    $controller = new AuthController();
    $controller->handleGoogleLogin();
} elseif($path === '/auth/login') {
    require_once '../app/controllers/AuthController.php';
    $controller = new AuthController();
    $controller->loginForm();
} elseif ($path === '/auth/login/local') {
    require_once '../app/controllers/AuthController.php';
    $controller = new AuthController();
    $controller->handleLocalLogin();
} elseif($path === '/auth/register') {
    require_once '../app/controllers/AuthController.php';
    $controller = new AuthController();
    $controller->showRegisterForm();
} elseif ($path === '/auth/register/local') {
    require_once '../app/controllers/AuthController.php';
    $controller = new AuthController();
    $controller->handleLocalRegister();
} elseif ($path === '/auth/login/forgot-password') {
    require_once '../app/controllers/AuthController.php';
    $controller = new AuthController();
    $controller->showForgotPasswordForm();
} elseif($path == '/auth/login/forgot-password/send-otp') {
    require_once '../app/controllers/AuthController.php';
    $controller = new AuthController();
    $controller->sendPasswordResetOTP();
} elseif ($path === '/auth/login/forgot-password/input-otp') {
    require_once '../app/controllers/AuthController.php';
    $controller = new AuthController();
    $controller->showVerifyOTPForm();
} elseif ($path === '/auth/login/forgot-password/verify-otp') {
    require_once '../app/controllers/AuthController.php';
    $controller = new AuthController();
    $controller->verifyPasswordResetOTP();
} elseif ($path === '/auth/login/forgot-password/reset-password-form') {
    require_once '../app/controllers/AuthController.php';
    $controller = new AuthController();
    $controller->showResetPasswordForm();
} elseif ($path === '/auth/login/forgot-password/reset-password') {
    require_once '../app/controllers/AuthController.php';
    $controller = new AuthController();
    $controller->resetPassword();
} elseif($path === '/auth/login/forgot-password/reset-expired') {
    require_once '../app/controllers/AuthController.php';
    $controller = new AuthController();
    $controller->showExpiredTokenOrOTPPage();
} elseif ($path === '/check-email') {
    require_once '../app/controllers/AuthController.php';
    $controller = new AuthController();
    $controller->checkEmail();
}

// User CRUD Routes
if ($path === '/users' && $_SESSION['user']['role'] == 'Admin') {
    // List all users
    require_once '../app/controllers/UserController.php';
    $controller = new UserController();
    $controller->index();
    exit;
    
} elseif ($path === '/users/create' && $_SESSION['user']['role'] == 'Admin') {
    // Show create form
    require_once '../app/controllers/UserController.php';
    $controller = new UserController();
    $controller->create();
    
} elseif ($path === '/users/store' && $_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['user']['role'] == 'Admin') {
    // Store new user
    require_once '../app/controllers/UserController.php';
    $controller = new UserController();
    $controller->store();
    
} elseif (preg_match('/^\/users\/edit\/(\d+)$/', $path, $matches ) && $_SESSION['user']['role'] == 'Admin') {
    // Show edit form
    require_once '../app/controllers/UserController.php';
    $controller = new UserController();
    $controller->edit($matches[1]);
    
} elseif (preg_match('/^\/users\/update\/(\d+)$/', $path, $matches) && $_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['user']['role'] == 'Admin') {
    // Update user
    require_once '../app/controllers/UserController.php';
    $controller = new UserController();
    $controller->update($matches[1]);
    
} elseif (preg_match('/^\/users\/delete\/(\d+)$/', $path, $matches) && $_SESSION['user']['role'] == 'Admin') {
    // Delete user
    require_once '../app/controllers/UserController.php';
    $controller = new UserController();
    $controller->destroy($matches[1]);

// Job Category CRUD Routes
} elseif ($path === '/job-categories' && $_SESSION['user']['role'] == 'Admin')  {
    // List all job categories
    require_once '../app/controllers/JobCategoryController.php';
    $controller = new JobCategoryController();
    $controller->index();
    exit;
    
} elseif ($path === '/job-categories/create' && $_SESSION['user']['role'] == 'Admin') {
    // Show create form
    require_once '../app/controllers/JobCategoryController.php';
    $controller = new JobCategoryController();
    $controller->create();
    
} elseif ($path === '/job-categories/store' && $_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['user']['role'] == 'Admin') {
    // Store new job category
    require_once '../app/controllers/JobCategoryController.php';
    $controller = new JobCategoryController();
    $controller->store();
    
} elseif (preg_match('/^\/job-categories\/edit\/(\d+)$/', $path, $matches) && $_SESSION['user']['role'] == 'Admin') {
    // Show edit form
    require_once '../app/controllers/JobCategoryController.php';
    $controller = new JobCategoryController();
    $controller->edit($matches[1]);
    
} elseif (preg_match('/^\/job-categories\/update\/(\d+)$/', $path, $matches) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update job category
    require_once '../app/controllers/JobCategoryController.php';
    $controller = new JobCategoryController();
    $controller->update($matches[1]);
    
} elseif (preg_match('/^\/job-categories\/delete\/(\d+)$/', $path, $matches) && $_SESSION['user']['role'] == 'Admin') {
    // Delete job category
    require_once '../app/controllers/JobCategoryController.php';
    $controller = new JobCategoryController();
    $controller->destroy($matches[1]);

// Job Management Routes (Staff/Admin)
} elseif ($path === '/jobs-manage' && ($_SESSION['user']['role'] == 'Admin' || $_SESSION['user']['role'] == 'Staff')) {
    // List all jobs
    require_once '../app/controllers/JobController.php';
    $controller = new JobController();
    $controller->index();
    
} elseif (preg_match('/^\/jobs-manage\/edit\/(\d+)$/', $path, $matches) && ($_SESSION['user']['role'] == 'Admin' || $_SESSION['user']['role'] == 'Staff')) {
    // Show edit form
    require_once '../app/controllers/JobController.php';
    $controller = new JobController();
    $controller->edit($matches[1]);
    
} elseif (preg_match('/^\/jobs-manage\/update\/(\d+)$/', $path, $matches) && $_SERVER['REQUEST_METHOD'] === 'POST' && ($_SESSION['user']['role'] == 'Admin' || $_SESSION['user']['role'] == 'Staff')) {
    // Update job
    require_once '../app/controllers/JobController.php';
    $controller = new JobController();
    $controller->update($matches[1]);
    
} elseif (preg_match('/^\/jobs-manage\/soft-delete\/(\d+)$/', $path, $matches) && ($_SESSION['user']['role'] == 'Admin' || $_SESSION['user']['role'] == 'Staff')) {
    // Soft delete job
    require_once '../app/controllers/JobController.php';
    $controller = new JobController();
    $controller->softDelete($matches[1]);
    
} elseif (preg_match('/^\/jobs-manage\/hard-delete\/(\d+)$/', $path, $matches) && ($_SESSION['user']['role'] == 'Admin' || $_SESSION['user']['role'] == 'Staff')) {
    // Hard delete job
    require_once '../app/controllers/JobController.php';
    $controller = new JobController();
    $controller->hardDelete($matches[1]);
    
} elseif (preg_match('/^\/jobs-manage\/restore\/(\d+)$/', $path, $matches) && ($_SESSION['user']['role'] == 'Admin' || $_SESSION['user']['role'] == 'Staff')) {
    // Restore soft deleted job
    require_once '../app/controllers/JobController.php';
    $controller = new JobController();
    $controller->restore($matches[1]);
    
} elseif (preg_match('/^\/jobs-manage\/change-status\/(\d+)$/', $path, $matches) && $_SERVER['REQUEST_METHOD'] === 'POST' && ($_SESSION['user']['role'] == 'Admin' || $_SESSION['user']['role'] == 'Staff')) {
    // Change job status
    require_once '../app/controllers/JobController.php';
    $controller = new JobController();
    $controller->changeStatus($matches[1]);

// Job Approval Routes (Staff/Admin)
} elseif ($path === '/approvals' && ($_SESSION['user']['role'] == 'Admin' || $_SESSION['user']['role'] == 'Staff')) {
    // List jobs pending approval
    require_once '../app/controllers/JobController.php';
    $controller = new JobController();
    $controller->approvalIndex();
    
} elseif (preg_match('/^\/approvals\/detail\/(\d+)$/', $path, $matches) && ($_SESSION['user']['role'] == 'Admin' || $_SESSION['user']['role'] == 'Staff')) {
    // Show job detail for approval
    require_once '../app/controllers/JobController.php';
    $controller = new JobController();
    $controller->approvalDetail($matches[1]);
    
} elseif (preg_match('/^\/approvals\/approve\/(\d+)$/', $path, $matches) && $_SERVER['REQUEST_METHOD'] === 'POST' && ($_SESSION['user']['role'] == 'Admin' || $_SESSION['user']['role'] == 'Staff')) {
    // Approve job
    require_once '../app/controllers/JobController.php';
    $controller = new JobController();
    $controller->approveJob($matches[1]);
    
} elseif (preg_match('/^\/approvals\/reject\/(\d+)$/', $path, $matches) && $_SERVER['REQUEST_METHOD'] === 'POST' && ($_SESSION['user']['role'] == 'Admin' || $_SESSION['user']['role'] == 'Staff')) {
    // Reject job
    require_once '../app/controllers/JobController.php';
    $controller = new JobController();
    $controller->rejectJob($matches[1]);


// Staff Action Logs Routes (Admin)
} elseif ($path === '/staff-actions' && $_SESSION['user']['role'] == 'Admin') {
    // List staff actions
    require_once '../app/controllers/StaffActionController.php';
    $controller = new StaffActionController();
    $controller->index();
} 

// Staff Action Logs Routes (Admin)
elseif ($path === '/feedbacks' && $_SESSION['user']['role'] == 'Admin') {
    // List feedbacks
    require_once '../app/controllers/FeedbackController.php';
    $controller = new FeedbackController();
    $controller->index();
} 

// Statistics Routes (Admin)
elseif ($path === '/statistics' && $_SESSION['user']['role'] == 'Admin') {
    // Show statistics dashboard
    require_once '../app/controllers/StatisticController.php';
    $controller = new StatisticController();
    $controller->index();
} 

// Profile Routes (All users)
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

// Company Profile (Employer)
elseif ($path === '/company-profile' && $_SESSION['user']['role'] == 'Employer') {
    // Show company profile edit form
    require_once '../app/controllers/CompanyController.php';
    $controller = new CompanyController();
    $controller->index();
    
} elseif ($path === '/company-profile/update' && $_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['user']['role'] == 'Employer') {
    // Update company profile
    require_once '../app/controllers/CompanyController.php';
    $controller = new CompanyController();
    $controller->updateCompanyProfile();
}

// My Jobs Routes (Employer)
elseif ($path === '/my-jobs' && $_SESSION['user']['role'] == 'Employer') {
    // List my jobs
    require_once '../app/controllers/JobController.php';
    $controller = new JobController();
    $controller->myJobs();
} elseif(preg_match('/^\/my-jobs\/show\/(\d+)$/', $path, $matches) && $_SESSION['user']['role'] == 'Employer') {
    // Show my job detail
    require_once '../app/controllers/JobController.php';
    $controller = new JobController();
    $controller->myJobDetail($matches[1]);
} elseif ($path === '/my-jobs/create' && $_SESSION['user']['role'] == 'Employer') {
    // Show create new job form
    require_once '../app/controllers/JobController.php';
    $controller = new JobController();
    $controller->myJobCreate();
} elseif($path === '/my-jobs/store' && $_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['user']['role'] == 'Employer') {
    // Store new job
    require_once '../app/controllers/JobController.php';
    $controller = new JobController();
    $controller->myJobStore();
}
elseif(preg_match('/^\/my-jobs\/edit\/(\d+)$/', $path, $matches) && $_SESSION['user']['role'] == 'Employer') {
    // Show edit form for my job
    require_once '../app/controllers/JobController.php';
    $controller = new JobController();
    $controller->myJobEdit($matches[1]);
} elseif (preg_match('/^\/my-jobs\/update\/(\d+)$/', $path, $matches) && $_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['user']['role'] == 'Employer') {
    // Update my job
    require_once '../app/controllers/JobController.php';
    $controller = new JobController();
    $controller->myJobUpdate($matches[1]);
} elseif(preg_match('/^\/my-jobs\/soft-delete\/(\d+)$/', $path, $matches) && $_SESSION['user']['role'] == 'Employer') {
    // Soft delete my job
    require_once '../app/controllers/JobController.php';
    $controller = new JobController();
    $controller->myJobSoftDelete($matches[1]);
} elseif(preg_match('/^\/my-jobs\/hard-delete\/(\d+)$/', $path, $matches) && $_SESSION['user']['role'] == 'Employer') {
    // Hard delete my job
    require_once '../app/controllers/JobController.php';
    $controller = new JobController();
    $controller->myJobHardDelete($matches[1]);
} elseif ($path === '/my-jobs/add' && $_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['user']['role'] == 'Employer') {
    // Create new job
    require_once '../app/controllers/JobController.php';
    $controller = new JobController();
    $controller->createNewJob();
}

// My feedback Routes (Employer)
elseif ($path === '/my-feedbacks' && $_SESSION['user']['role'] == 'Employer') {
    // List my feedbacks
    require_once '../app/controllers/FeedbackController.php';
    $controller = new FeedbackController();
    $controller->myFeedbacks();
} elseif($path === '/my-feedbacks/create' && $_SESSION['user']['role'] == 'Employer') {
    // Show create form for my feedback
    require_once '../app/controllers/FeedbackController.php';
    $controller = new FeedbackController();
    $controller->createMyFeedback();
} elseif($path === '/my-feedbacks/store' && $_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['user']['role'] == 'Employer') {
    // Store my feedback
    require_once '../app/controllers/FeedbackController.php';
    $controller = new FeedbackController();
    $controller->storeMyFeedback();
} 
elseif (preg_match('/^\/my-feedbacks\/delete\/(\d+)$/', $path, $matches) && $_SESSION['user']['role'] == 'Employer') {
    // Delete my feedback
    require_once '../app/controllers/FeedbackController.php';
    $controller = new FeedbackController();
    $controller->deleteMyFeedback($matches[1]);
} elseif (preg_match('/^\/my-feedbacks\/edit\/(\d+)$/', $path, $matches) && $_SESSION['user']['role'] == 'Employer') {
    // Show edit form for my feedback
    require_once '../app/controllers/FeedbackController.php';
    $controller = new FeedbackController();
    $controller->editMyFeedback($matches[1]);
} elseif (preg_match('/^\/my-feedbacks\/update\/(\d+)$/', $path, $matches) && $_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['user']['role'] == 'Employer') {
    // Update my feedback
    require_once '../app/controllers/FeedbackController.php';
    $controller = new FeedbackController();
    $controller->updateMyFeedback($matches[1]);

}

else {
    // Public routes

    if (preg_match('/^\/jobs\/show\/(\d+)$/', $path, $m) || preg_match('#^/jobs/(\d+)$#', $path, $m)) {
        require_once '../app/controllers/PublicJobController.php';
        (new PublicJobController())->show($m[1]);
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
        case '/public/home':
            include '../app/views/public/home.php';
            break;

        case '/jobs':
            include '../app/views/public/jobs/jobslisting.php';
            break;

        case '/jobs/show':
            include '../app/views/public/jobs/show.php';
            break;

        case '/about':
            include '../app/views/public/about.php';
            break;

        case '/contact':
            include '../app/views/public/contact.php';
            break;

        case '/help-center':
            include '../app/views/public/help-center.php';
            break;

        case '/terms-of-service':
            include '../app/views/public/terms-of-service.php';
            break;

        case '/privacy-policy':
            include '../app/views/public/privacy-policy.php';
            break;
        
        default:
            http_response_code(404);
            include '../app/views/public/404.php';
            break;
    }
}

?>