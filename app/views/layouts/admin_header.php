<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Admin Dashboard' ?></title>
    <link href="/Job_poster/public/css/tailwind.min.css" rel="stylesheet">
    <link href="/Job_poster/public/css/admin.css" rel="stylesheet">
    <link href="/Job_poster/public/css/style.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <?php require_once __DIR__ . '/../../helpers/Icons.php'; ?>
    <!-- Mobile Toggle Button -->
    <button class="toggle-sidebar" onclick="toggleSidebar()">
        <?= Icons::menu('w-6 h-6') ?>
    </button>

    <!-- Sidebar Overlay (Mobile) -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="/Job_poster/public/" class="flex items-center space-x-3 hover:opacity-80 transition">
                <h2 class="text-xl font-bold">Admin Panel</h2>
            </a>
            <p class="text-xs mt-1 opacity-75">System Management</p>

        </div>

        <nav class="py-4">
            <!-- Dashboard Section -->
            <div class="menu-section">Dashboard</div>
            <a href="/Job_poster/public/"
                class="menu-item <?= strpos($_SERVER['REQUEST_URI'], '/Job_poster/public/') !== false && strpos($_SERVER['REQUEST_URI'], '/users') === false && strpos($_SERVER['REQUEST_URI'], '/products') === false ? 'active' : '' ?>">
                <?= Icons::home() ?>
                Homepage
            </a>

            <!-- User Management -->
            <div class="menu-section">User Management</div>
            <a href="/Job_poster/public/users"
                class="menu-item <?= strpos($_SERVER['REQUEST_URI'], '/users') !== false ? 'active' : '' ?>">
                <?= Icons::users() ?>
                Users
            </a>

            <!-- Category Management -->
            <div class="menu-section">Category Management</div>
            <a href="/Job_poster/public/job-categories"
                class="menu-item <?= strpos($_SERVER['REQUEST_URI'], '/job-categories') !== false ? 'active' : '' ?>">
                <?= Icons::tag() ?>
                    Categories
            </a>

            <!-- Add more menu items here easily -->
            <!-- Example:
            <div class="menu-section">Báo cáo</div>
            <a href="/Job_poster/public/orders" class="menu-item">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Đơn hàng
            </a>
            -->
        </nav>

        <div
            style="position: absolute; bottom: 0; left: 0; right: 0; padding: 20px; background: rgba(0, 0, 0, 0.2); border-top: 1px solid rgba(255, 255, 255, 0.1);">
            <div class="flex items-center">
                <div class="w-10 h-10 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                    <span class="text-sm font-bold">AD</span>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-semibold">Admin User</p>
                    <a href="#" class="text-xs opacity-75 hover:opacity-100">Log out</a>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content" id="mainContent">