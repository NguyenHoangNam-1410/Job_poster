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

// Discount CRUD Routes
} elseif ($path === '/discounts') {
    // List all discounts
    require_once '../app/controllers/DiscountController.php';
    $controller = new DiscountController();
    $controller->index();
    exit;
    
} elseif ($path === '/discounts/create') {
    // Show create form
    require_once '../app/controllers/DiscountController.php';
    $controller = new DiscountController();
    $controller->create();
    
} elseif ($path === '/discounts/store' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Store new discount
    require_once '../app/controllers/DiscountController.php';
    $controller = new DiscountController();
    $controller->store();
    
} elseif (preg_match('/^\/discounts\/edit\/(\d+)$/', $path, $matches)) {
    // Show edit form
    require_once '../app/controllers/DiscountController.php';
    $controller = new DiscountController();
    $controller->edit($matches[1]);
    
} elseif (preg_match('/^\/discounts\/update\/(\d+)$/', $path, $matches) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update discount
    require_once '../app/controllers/DiscountController.php';
    $controller = new DiscountController();
    $controller->update($matches[1]);
    
} elseif (preg_match('/^\/discounts\/delete\/(\d+)$/', $path, $matches)) {
    // Delete discount
    require_once '../app/controllers/DiscountController.php';
    $controller = new DiscountController();
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

} else {
    // Public routes
    switch ($path) {
        case '/':
        case '/home':
            include '../app/views/public/home.php';
            break;
        
        default:
            http_response_code(404);
            include '../app/views/public/404.php';
            break;
    }
}
?>