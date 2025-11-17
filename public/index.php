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
    header("Location: /public/home");
    exit;
} elseif (isset($_SESSION['user']) && in_array($path, ['/auth/register','/auth/login'])) {
    header("Location: /");
    exit;
}
// If logged in and accessing public route
elseif (isset($_SESSION['user']) && $isPublic) {
    $userRole = $_SESSION['user']['role'] ?? null;
    if($userRole == 'Admin') {
        header("Location: /admin/home");
        exit;
    } elseif($userRole == 'Staff') {
        header("Location: /staff/home");
        exit;
    } elseif($userRole == 'Employer') {
        header("Location: /employer/home");
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
    $controller->index();
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

//Home route after logged in with roles
if ($path === '/public/home') {
    require_once '../app/controllers/HomeController.php';
    $controller = new HomeController();
    $controller->index();
    exit;
} elseif($path === '/admin/home' && $_SESSION['user']['role'] == 'Admin') {
    require_once '../app/controllers/HomeController.php';
    $controller = new HomeController();
    $controller->adminIndex();
    exit;
} elseif($path === '/staff/home' && $_SESSION['user']['role'] == 'Staff') {
    require_once '../app/controllers/HomeController.php';
    $controller = new HomeController();
    $controller->staffIndex();
    exit;
} elseif($path === '/employer/home' && $_SESSION['user']['role'] == 'Employer') {
    require_once '../app/controllers/HomeController.php';
    $controller = new HomeController();
    $controller->employerIndex();
    exit;
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