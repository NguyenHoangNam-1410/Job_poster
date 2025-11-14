<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Staff Dashboard' ?></title>
    <link href="/Job_poster/public/css/
tailwind.min.css" rel="stylesheet">
    <link href="/Job_poster/public/css/
admin.css" rel="stylesheet">
    <link href="/Job_poster/public/css/
style.css" rel="stylesheet">
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
                <h2 class="text-xl font-bold">Staff Panel</h2>
            </a>
            <p class="text-xs mt-1 opacity-75">Support & Review</p>
        </div>

        <nav class="py-4">
            <!-- Dashboard Section -->
            <div class="menu-section">Dashboard</div>
            <a href="/Job_poster/public/"
                class="menu-item <?= rtrim($_SERVER['REQUEST_URI'], '/') === '/Job_poster/public' ? 'active' : '' ?>">
                <?= Icons::home() ?>
                Homepage
            </a>

            <!-- Job Request Management -->
            <div class="menu-section">Job Requests</div>
            <a href="/Job_poster/public/approvals"
                class="menu-item <?= strpos($_SERVER['REQUEST_URI'], '/approvals') !== false ? 'active' : '' ?>">
                <?= Icons::checklist() ?>
                Request Approval
            </a>

            <!-- Report Management -->
            <div class="menu-section">Reports & Complaints</div>
            <a href="/Job_poster/public/reports"
                class="menu-item <?= strpos($_SERVER['REQUEST_URI'], '/reports') !== false ? 'active' : '' ?>">
                <?= Icons::flag() ?>
                Review Reports
            </a>

            <!-- Job Management -->
            <div class="menu-section">Job Management</div>
            <a href="/Job_poster/public/jobs-manage"
                class="menu-item <?= strpos($_SERVER['REQUEST_URI'], '/jobs') !== false ? 'active' : '' ?>">
                <?= Icons::briefcase() ?>
                Jobs
            </a>
        </nav>

        <div
            style="position: absolute; bottom: 0; left: 0; right: 0; padding: 20px; background: rgba(0, 0, 0, 0.2); border-top: 1px solid rgba(255, 255, 255, 0.1);">
            <div class="flex items-center">
                <div class="w-10 h-10 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                    <img src="<?= $_SESSION['user']['avatar']?? '/images/profile.png'?>" alt="Staff Avatar" class="w-full h-full rounded-full">
                </div>
                <div class="ml-3">
                    <p class="text-sm font-semibold"><?= $_SESSION['user']['name'] ?? 'Staff User' ?></p>
                    <a href="/Job_poster/public/logout" class="text-xs opacity-75 hover:opacity-100">Log out</a>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content" id="mainContent">
