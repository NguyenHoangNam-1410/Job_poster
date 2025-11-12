<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Employer Dashboard' ?></title>
    <link href="/css/tailwind.min.css" rel="stylesheet">
    <link href="/css/admin.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
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
                <h2 class="text-xl font-bold">Employer Panel</h2>
            </a>
            <p class="text-xs mt-1 opacity-75">Company Management</p>
        </div>

        <nav class="py-4">
            <!-- Dashboard Section -->
            <div class="menu-section">Dashboard</div>
            <a href="/Job_poster/public/"
                class="menu-item <?= rtrim($_SERVER['REQUEST_URI'], '/') === '/Job_poster/public' ? 'active' : '' ?>">
                <?= Icons::home() ?>
                Homepage
            </a>

            <!-- Company Profile -->
            <div class="menu-section">Company Profile</div>
            <a href="/Job_poster/public/company"
                class="menu-item <?= strpos($_SERVER['REQUEST_URI'], '/company') !== false ? 'active' : '' ?>">
                <?= Icons::building() ?>
                My Company
            </a>

            <!-- Job Management -->
            <div class="menu-section">Job Management</div>
            <a href="/Job_poster/public/my-jobs"
                class="menu-item <?= strpos($_SERVER['REQUEST_URI'], '/my-jobs') !== false ? 'active' : '' ?>">
                <?= Icons::briefcase() ?>
                Manage My Jobs
            </a>

            <!-- Report Posting -->
            <div class="menu-section">Reports</div>
            <a href="/Job_poster/public/report"
                class="menu-item <?= strpos($_SERVER['REQUEST_URI'], '/report') !== false ? 'active' : '' ?>">
                <?= Icons::flag() ?>
                Report Issue
            </a>
        </nav>

        <div
            style="position: absolute; bottom: 0; left: 0; right: 0; padding: 20px; background: rgba(0, 0, 0, 0.2); border-top: 1px solid rgba(255, 255, 255, 0.1);">
            <div class="flex items-center">
                <div class="w-10 h-10 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                    <img src="<?= $_SESSION['user']['avatar']?? '/images/profile.png'?>" alt="Employer Avatar" class="w-full h-full rounded-full"> <!-- Employer Avatar -->
                </div>
                <div class="ml-3">
                    <p class="text-sm font-semibold"><?= $_SESSION['user']['name'] ?? 'Employer User' ?></p>
                    <a href="/logout" class="text-xs opacity-75 hover:opacity-100">Log out</a>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content" id="mainContent">