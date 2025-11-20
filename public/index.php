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

// Helper variable for user role
$userRole = $_SESSION['user']['role'] ?? null;
$isLoggedIn = isset($_SESSION['user']);

// Define public routes
$publicRoutes = [
    '/auth/login', '/auth/register', '/auth/register/local', '/auth/login/local',
    '/auth/login/facebook', '/auth/login/facebook/callback', '/auth/login/google',
    '/auth/login/forgot-password', '/auth/login/forgot-password/send-otp',
    '/auth/login/forgot-password/input-otp', '/auth/login/forgot-password/verify-otp',
    '/auth/login/forgot-password/reset-password-form', '/auth/login/forgot-password/reset-password',
    '/auth/login/forgot-password/reset-expired', '/check-email',
    '/public/home', '/jobs', '/jobs/show/:id', '/jobs/apply', '/', '/contact',
    '/about', '/help-center', '/terms-of-service', '/privacy-policy',
    '/ajax/jobs_filters.php', '/ajax/jobs_list.php', '/ajax/jobs_related.php'
];

// Check if the route is public
$isPublic = false;
foreach ($publicRoutes as $route) {
    if (preg_match('#^' . preg_replace('#:[^/]+#', '[^/]+', $route) . '$#', $path)) {
        $isPublic = true;
        break;
    }
}

// Handle logout immediately (before access control checks)
if ($path === '/logout') {
    require_once '../app/controllers/AuthController.php';
    $controller = new AuthController();
    $controller->logout();
    exit;
}

// Access control: Redirect non-logged in users trying to access private routes
if (!$isLoggedIn && !$isPublic) {
    http_response_code(404);
    include '../app/views/public/404.php';
    exit;
}

// Redirect logged in users away from auth pages
if ($isLoggedIn && $isPublic && !in_array($path, ['/', '/public/home', '/jobs', '/contact', '/about'])) {
    header('Location: ' . BASE_URL . '/home');
    exit;
}

// ============================================================================
// ROUTING FUNCTIONS
// ============================================================================

function route($pattern, $callback, $methods = ['GET'], $roles = null) {
    global $path, $userRole, $isLoggedIn;
    
    // Check if method matches
    if (!in_array($_SERVER['REQUEST_METHOD'], $methods)) {
        return false;
    }
    
    // Check if role is required and matches
    if ($roles !== null && (!$isLoggedIn || !in_array($userRole, (array)$roles))) {
        return false;
    }
    
    // Check if path matches pattern
    if (preg_match($pattern, $path, $matches)) {
        array_shift($matches); // Remove full match
        call_user_func_array($callback, $matches);
        return true;
    }
    
    return false;
}

function controller($name, $method, ...$args) {
    require_once "../app/controllers/{$name}.php";
    $controller = new $name();
    return $controller->$method(...$args);
}

function view($viewPath) {
    include "../app/views/{$viewPath}.php";
}

// ============================================================================
// AUTHENTICATION ROUTES
// ============================================================================

// Login routes
route('#^/auth/login$#', fn() => controller('AuthController', 'loginForm'));
route('#^/auth/login/local$#', fn() => controller('AuthController', 'handleLocalLogin'), ['POST']);
route('#^/auth/login/facebook$#', fn() => controller('AuthController', 'handleFacebookLogin'));
route('#^/auth/login/facebook/callback$#', fn() => controller('AuthController', 'facebookCallback'));
route('#^/auth/login/google$#', fn() => controller('AuthController', 'handleGoogleLogin'));

// Registration routes
route('#^/auth/register$#', fn() => controller('AuthController', 'showRegisterForm'));
route('#^/auth/register/local$#', fn() => controller('AuthController', 'handleLocalRegister'), ['POST']);
route('#^/check-email$#', fn() => controller('AuthController', 'checkEmail'));

// Password reset routes
route('#^/auth/login/forgot-password$#', fn() => controller('AuthController', 'showForgotPasswordForm'));
route('#^/auth/login/forgot-password/send-otp$#', fn() => controller('AuthController', 'sendPasswordResetOTP'), ['POST']);
route('#^/auth/login/forgot-password/input-otp$#', fn() => controller('AuthController', 'showVerifyOTPForm'));
route('#^/auth/login/forgot-password/verify-otp$#', fn() => controller('AuthController', 'verifyPasswordResetOTP'), ['POST']);
route('#^/auth/login/forgot-password/reset-password-form$#', fn() => controller('AuthController', 'showResetPasswordForm'));
route('#^/auth/login/forgot-password/reset-password$#', fn() => controller('AuthController', 'resetPassword'), ['POST']);
route('#^/auth/login/forgot-password/reset-expired$#', fn() => controller('AuthController', 'showExpiredTokenOrOTPPage'));

// ============================================================================
// HOME ROUTES (Role-based)
// ============================================================================

route('#^/public/home$#', fn() => controller('HomeController', 'index'));
route('#^/admin/home$#', fn() => controller('HomeController', 'adminIndex'), ['GET'], ['Admin']);
route('#^/staff/home$#', fn() => controller('HomeController', 'staffIndex'), ['GET'], ['Staff']);
route('#^/employer/home$#', fn() => controller('HomeController', 'employerIndex'), ['GET'], ['Employer']);

// ============================================================================
// USER MANAGEMENT ROUTES (Admin Only)
// ============================================================================

route('#^/users$#', fn() => controller('UserController', 'index'), ['GET'], ['Admin']);
route('#^/users/create$#', fn() => controller('UserController', 'create'), ['GET'], ['Admin']);
route('#^/users/store$#', fn() => controller('UserController', 'store'), ['POST'], ['Admin']);
route('#^/users/edit/(\d+)$#', fn($id) => controller('UserController', 'edit', $id), ['GET'], ['Admin']);
route('#^/users/update/(\d+)$#', fn($id) => controller('UserController', 'update', $id), ['POST'], ['Admin']);
route('#^/users/delete/(\d+)$#', fn($id) => controller('UserController', 'destroy', $id), ['GET', 'POST'], ['Admin']);

// ============================================================================
// JOB CATEGORY MANAGEMENT ROUTES (Admin Only)
// ============================================================================

route('#^/job-categories$#', fn() => controller('JobCategoryController', 'index'), ['GET'], ['Admin']);
route('#^/job-categories/create$#', fn() => controller('JobCategoryController', 'create'), ['GET'], ['Admin']);
route('#^/job-categories/store$#', fn() => controller('JobCategoryController', 'store'), ['POST'], ['Admin']);
route('#^/job-categories/edit/(\d+)$#', fn($id) => controller('JobCategoryController', 'edit', $id), ['GET'], ['Admin']);
route('#^/job-categories/update/(\d+)$#', fn($id) => controller('JobCategoryController', 'update', $id), ['POST'], ['Admin']);
route('#^/job-categories/delete/(\d+)$#', fn($id) => controller('JobCategoryController', 'destroy', $id), ['GET', 'POST'], ['Admin']);

// ============================================================================
// JOB MANAGEMENT ROUTES (Admin & Staff)
// ============================================================================

route('#^/jobs-manage$#', fn() => controller('JobController', 'index'), ['GET'], ['Admin', 'Staff']);
route('#^/jobs-manage/edit/(\d+)$#', fn($id) => controller('JobController', 'edit', $id), ['GET'], ['Admin', 'Staff']);
route('#^/jobs-manage/update/(\d+)$#', fn($id) => controller('JobController', 'update', $id), ['POST'], ['Admin', 'Staff']);
route('#^/jobs-manage/soft-delete/(\d+)$#', fn($id) => controller('JobController', 'softDelete', $id), ['GET', 'POST'], ['Admin', 'Staff']);
route('#^/jobs-manage/hard-delete/(\d+)$#', fn($id) => controller('JobController', 'hardDelete', $id), ['GET', 'POST'], ['Admin', 'Staff']);
route('#^/jobs-manage/restore/(\d+)$#', fn($id) => controller('JobController', 'restore', $id), ['GET', 'POST'], ['Admin', 'Staff']);
route('#^/jobs-manage/change-status/(\d+)$#', fn($id) => controller('JobController', 'changeStatus', $id), ['POST'], ['Admin', 'Staff']);

// ============================================================================
// JOB APPROVAL ROUTES (Admin & Staff)
// ============================================================================

route('#^/approvals$#', fn() => controller('JobController', 'approvalIndex'), ['GET'], ['Admin', 'Staff']);
route('#^/approvals/detail/(\d+)$#', fn($id) => controller('JobController', 'approvalDetail', $id), ['GET'], ['Admin', 'Staff']);
route('#^/approvals/approve/(\d+)$#', fn($id) => controller('JobController', 'approveJob', $id), ['POST'], ['Admin', 'Staff']);
route('#^/approvals/reject/(\d+)$#', fn($id) => controller('JobController', 'rejectJob', $id), ['POST'], ['Admin', 'Staff']);

// ============================================================================
// ADMIN-ONLY ROUTES
// ============================================================================

route('#^/staff-actions$#', fn() => controller('StaffActionController', 'index'), ['GET'], ['Admin']);
route('#^/feedbacks$#', fn() => controller('FeedbackController', 'index'), ['GET'], ['Admin']);
route('#^/statistics$#', fn() => controller('StatisticController', 'index'), ['GET'], ['Admin']);

// ============================================================================
// PROFILE ROUTES (All authenticated users)
// ============================================================================

route('#^/profile$#', fn() => controller('UserController', 'profile'), ['GET'], ['Admin', 'Staff', 'Employer']);
route('#^/profile/update$#', fn() => controller('UserController', 'updateProfile'), ['POST'], ['Admin', 'Staff', 'Employer']); 

// ============================================================================
// COMPANY PROFILE ROUTES (Employer Only)
// ============================================================================

route('#^/company-profile$#', fn() => controller('CompanyController', 'index'), ['GET'], ['Employer']);
route('#^/company-profile/update$#', fn() => controller('CompanyController', 'updateCompanyProfile'), ['POST'], ['Employer']);

// ============================================================================
// MY JOBS ROUTES (Employer Only)
// ============================================================================

route('#^/my-jobs$#', fn() => controller('JobController', 'myJobs'), ['GET'], ['Employer']);
route('#^/my-jobs/show/(\d+)$#', fn($id) => controller('JobController', 'myJobDetail', $id), ['GET'], ['Employer']);
route('#^/my-jobs/create$#', fn() => controller('JobController', 'myJobCreate'), ['GET'], ['Employer']);
route('#^/my-jobs/store$#', fn() => controller('JobController', 'myJobStore'), ['POST'], ['Employer']);
route('#^/my-jobs/add$#', fn() => controller('JobController', 'createNewJob'), ['POST'], ['Employer']);
route('#^/my-jobs/edit/(\d+)$#', fn($id) => controller('JobController', 'myJobEdit', $id), ['GET'], ['Employer']);
route('#^/my-jobs/update/(\d+)$#', fn($id) => controller('JobController', 'myJobUpdate', $id), ['POST'], ['Employer']);
route('#^/my-jobs/soft-delete/(\d+)$#', fn($id) => controller('JobController', 'myJobSoftDelete', $id), ['GET', 'POST'], ['Employer']);
route('#^/my-jobs/hard-delete/(\d+)$#', fn($id) => controller('JobController', 'myJobHardDelete', $id), ['GET', 'POST'], ['Employer']);

// ============================================================================
// MY FEEDBACKS ROUTES (Employer Only)
// ============================================================================

route('#^/my-feedbacks$#', fn() => controller('FeedbackController', 'myFeedbacks'), ['GET'], ['Employer']);
route('#^/my-feedbacks/create$#', fn() => controller('FeedbackController', 'createMyFeedback'), ['GET'], ['Employer']);
route('#^/my-feedbacks/store$#', fn() => controller('FeedbackController', 'storeMyFeedback'), ['POST'], ['Employer']);
route('#^/my-feedbacks/edit/(\d+)$#', fn($id) => controller('FeedbackController', 'edit', $id), ['GET'], ['Employer']);
route('#^/my-feedbacks/update/(\d+)$#', fn($id) => controller('FeedbackController', 'update', $id), ['POST'], ['Employer']);
route('#^/my-feedbacks/delete/(\d+)$#', fn($id) => controller('FeedbackController', 'destroy', $id), ['GET', 'POST'], ['Employer']);

// ============================================================================
// PUBLIC JOB ROUTES
// ============================================================================

if (route('#^/jobs/show/(\d+)$#', fn($id) => controller('PublicJobController', 'show', $id))) exit;
if (route('#^/jobs/(\d+)$#', fn($id) => controller('PublicJobController', 'show', $id))) exit;
if (route('#^/jobs/apply$#', fn() => controller('PublicJobController', 'apply'), ['POST'])) exit;
if (route('#^/jobs$#', fn() => controller('PublicJobController', 'index'))) exit;

// ============================================================================
// AJAX ENDPOINTS
// ============================================================================

if (route('#^/ajax/jobs_filters\.php$#', function() {
    require __DIR__ . '/ajax/jobs_filters.php';
    exit;
})) exit;

if (route('#^/ajax/jobs_list\.php$#', function() {
    require __DIR__ . '/ajax/jobs_list.php';
    exit;
})) exit;

if (route('#^/ajax/jobs_related\.php$#', function() {
    require __DIR__ . '/ajax/jobs_related.php';
    exit;
})) exit;

// ============================================================================
// PUBLIC STATIC PAGES
// ============================================================================

if (route('#^/$#', fn() => view('public/home'))) exit;
if (route('#^/about$#', fn() => view('public/about'))) exit;
if (route('#^/contact$#', fn() => view('public/contact'))) exit;
if (route('#^/help-center$#', fn() => view('public/help-center'))) exit;
if (route('#^/terms-of-service$#', fn() => view('public/terms-of-service'))) exit;
if (route('#^/privacy-policy$#', fn() => view('public/privacy-policy'))) exit;

// ============================================================================
// 404 NOT FOUND
// ============================================================================

http_response_code(404);
include '../app/views/public/404.php';