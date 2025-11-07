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

/* ===================== Fallback khi KHÔNG có mod_rewrite ===================== */
// 1) Hỗ trợ truy cập dạng /index.php?r=/jobs
if (isset($_GET['r']) && is_string($_GET['r'])) {
    $r = $_GET['r'];
    if ($r === '' || $r[0] !== '/') $r = '/' . ltrim($r, '/'); // đảm bảo bắt đầu bằng "/"
    $path = $r;
}
// 2) Hỗ trợ truy cập dạng /index.php/jobs hoặc /index.php
if (strpos($path, '/index.php') === 0) {
    $path = substr($path, strlen('/index.php')); // '/index.php/jobs' -> '/jobs'
    if ($path === '' || $path === false) $path = '/';
}
// 3) Chuẩn hoá dấu gạch chéo cuối (không áp dụng cho root)
if ($path !== '/' && substr($path, -1) === '/') {
    $path = rtrim($path, '/');
}
/* ============================================================================ */

// Route handling

// User CRUD Routes
if ($path === '/users') {
    require_once '../app/controllers/UserController.php';
    $controller = new UserController();
    $controller->index();
    exit;

} elseif ($path === '/users/create') {
    require_once '../app/controllers/UserController.php';
    $controller = new UserController();
    $controller->create();

} elseif ($path === '/users/store' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../app/controllers/UserController.php';
    $controller = new UserController();
    $controller->store();

} elseif (preg_match('/^\/users\/edit\/(\d+)$/', $path, $matches)) {
    require_once '../app/controllers/UserController.php';
    $controller = new UserController();
    $controller->edit($matches[1]);

} elseif (preg_match('/^\/users\/update\/(\d+)$/', $path, $matches) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../app/controllers/UserController.php';
    $controller = new UserController();
    $controller->update($matches[1]);

} elseif (preg_match('/^\/users\/delete\/(\d+)$/', $path, $matches)) {
    require_once '../app/controllers/UserController.php';
    $controller = new UserController();
    $controller->destroy($matches[1]);

// Discount CRUD Routes
} elseif ($path === '/discounts') {
    require_once '../app/controllers/DiscountController.php';
    $controller = new DiscountController();
    $controller->index();
    exit;

} elseif ($path === '/discounts/create') {
    require_once '../app/controllers/DiscountController.php';
    $controller = new DiscountController();
    $controller->create();

} elseif ($path === '/discounts/store' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../app/controllers/DiscountController.php';
    $controller = new DiscountController();
    $controller->store();

} elseif (preg_match('/^\/discounts\/edit\/(\d+)$/', $path, $matches)) {
    require_once '../app/controllers/DiscountController.php';
    $controller = new DiscountController();
    $controller->edit($matches[1]);

} elseif (preg_match('/^\/discounts\/update\/(\d+)$/', $path, $matches) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../app/controllers/DiscountController.php';
    $controller = new DiscountController();
    $controller->update($matches[1]);

} elseif (preg_match('/^\/discounts\/delete\/(\d+)$/', $path, $matches)) {
    require_once '../app/controllers/DiscountController.php';
    $controller = new DiscountController();
    $controller->destroy($matches[1]);

} else {
    // Public routes
    switch ($path) {
        case '/':
        case '/home':
            include '../app/views/public/home.php';
            break;

        // Jobs listing & Job detail (đã thêm)
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
