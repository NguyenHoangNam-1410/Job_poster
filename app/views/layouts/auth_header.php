<?php
if (!isset($_SESSION['user']) || !isset($_SESSION['user']['role'])) {
    header('Location: /Worknest/public/auth/login');
    exit;
}

$userRole = $_SESSION['user']['role'];

// Role-based configuration
$roleConfig = [
    'Admin' => [
        'color' => 'admin',
        'title' => 'Admin Panel',
        'subtitle' => 'System Management',
        'homeUrl' => '/Worknest/public/statistics',
        'menuItems' => [
            ['section' => 'Dashboard'],
            ['url' => '/statistics', 'icon' => 'statistic', 'label' => 'Dashboard', 'tooltip' => 'Statistics'],

            ['section' => 'User Management'],
            ['url' => '/users', 'icon' => 'users', 'label' => 'Users', 'tooltip' => 'Users'],

            ['section' => 'Category Management'],
            ['url' => '/job-categories', 'icon' => 'tag', 'label' => 'Categories', 'tooltip' => 'Categories'],

            ['section' => 'Job Management'],
            ['url' => '/jobs-manage', 'icon' => 'briefcase', 'label' => 'Jobs', 'tooltip' => 'Jobs'],

            ['section' => 'Request Posting'],
            ['url' => '/approvals', 'icon' => 'checklist', 'label' => 'Request Posting', 'tooltip' => 'Request Posting'],

            ['section' => 'History'],
            ['url' => '/staff-actions', 'icon' => 'history', 'label' => 'History', 'tooltip' => 'History'],

            ['section' => 'Feedback'],
            ['url' => '/feedbacks', 'icon' => 'comment', 'label' => 'Feedback', 'tooltip' => 'Feedback'],
        ]
    ],
    'Staff' => [
        'color' => 'staff',
        'title' => 'Staff Panel',
        'subtitle' => 'Support & Review',
        'homeUrl' => '/Worknest/public/staff/home',
        'menuItems' => [
            ['section' => 'Dashboard'],
            ['url' => '/staff/home', 'icon' => 'home', 'label' => 'Dashboard', 'tooltip' => 'Dashboard'],

            ['section' => 'Job Management'],
            ['url' => '/jobs-manage', 'icon' => 'briefcase', 'label' => 'Jobs', 'tooltip' => 'Jobs'],

            ['section' => 'Request Posting'],
            ['url' => '/approvals', 'icon' => 'checklist', 'label' => 'Request Posting', 'tooltip' => 'Request Posting'],

            ['section' => 'Feedback'],
            ['url' => '/feedbacks', 'icon' => 'comment', 'label' => 'Feedback', 'tooltip' => 'Feedback'],
        ]
    ],
    'Employer' => [
        'color' => 'employer',
        'title' => 'Employer Panel',
        'subtitle' => 'Manage Your Jobs',
        'homeUrl' => '/Worknest/public/employer/home',
        'menuItems' => [
            ['section' => 'Dashboard'],
            ['url' => '/employer/home', 'icon' => 'home', 'label' => 'Dashboard', 'tooltip' => 'Dashboard'],

            ['section' => 'Company'],
            ['url' => '/company-profile', 'icon' => 'building', 'label' => 'Company Profile', 'tooltip' => 'Company Profile'],

            ['section' => 'Jobs'],
            ['url' => '/my-jobs', 'icon' => 'briefcase', 'label' => 'My Jobs', 'tooltip' => 'My Jobs'],

            ['section' => 'Feedback'],
            ['url' => '/my-feedbacks', 'icon' => 'comment', 'label' => 'Feedbacks', 'tooltip' => 'Feedbacks'],
        ]
    ]
];

// Get configuration for current role (fallback to Staff if role not found)
$config = $roleConfig[$userRole] ?? $roleConfig['Staff'];
$colorClass = $config['color'];

// Load Icons helper
if (!class_exists('Icons')) {
    require_once __DIR__ . '/../../helpers/Icons.php';
}

// Get current user's avatar from database
$currentUserId = $_SESSION['user']['id'] ?? null;
$userAvatar = '/Worknest/public/image/avatar/default.svg';
$userName = $_SESSION['user']['name'] ?? $userRole . ' User';

if ($currentUserId) {
    if (!class_exists('UserDAO')) {
        require_once __DIR__ . '/../../dao/UserDAO.php';
    }
    $userDAO = new UserDAO();
    $dbAvatar = $userDAO->getAvatar($currentUserId);
    if (!empty($dbAvatar)) {
        $userAvatar = $dbAvatar;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? $config['title'] ?></title>
    <link rel="icon" type="image/x-icon" href="/Worknest/public/images/favicon.png">
    <link href="/Worknest/public/css/tailwind.min.css" rel="stylesheet">
    <?php
    // Load role-specific CSS file
    $cssFile = strtolower($config['color']); // admin, staff, or employer
    ?>
    <link href="/Worknest/public/css/<?= $cssFile ?>.css?v=<?= time() ?>" rel="stylesheet">
    <link href="/Worknest/public/css/style.css" rel="stylesheet">

    <style>
        /* Navbar takes full width at top */
        .top-navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
        }

        /* Desktop sidebar - below navbar */
        @media (min-width: 768px) {
            .sidebar {
                position: fixed;
                top: 64px;
                /* Below navbar */
                left: 0;
                bottom: 0;
                width: 250px;
                display: flex;
                flex-direction: column;
                transition: width 0.3s ease;
            }

            .main-content {
                margin-top: 64px;
                /* Below navbar */
                margin-left: 250px;
                transition: margin-left 0.3s ease;
            }

            /* Minimized state on desktop */
            .sidebar-minimized-state .sidebar {
                width: 70px;
            }

            .sidebar-minimized-state .main-content {
                margin-left: 70px;
            }
        }

        /* Mobile sidebar - slides in from right like public header */
        @media (max-width: 767px) {
            .sidebar {
                position: fixed;
                top: 64px;
                /* Below navbar */
                right: -250px;
                width: 250px;
                height: calc(100% - 64px);
                z-index: 50;
                transition: right 0.3s ease;
                box-shadow: -4px 0 6px rgba(0, 0, 0, 0.1);
                display: flex;
                flex-direction: column;
            }

            .sidebar.active {
                right: 0;
            }

            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 64px;
                /* Below navbar */
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.3);
                z-index: 40;
            }

            .sidebar-overlay.active {
                display: block;
            }

            .main-content {
                margin-top: 64px;
                /* Below navbar */
                margin-left: 0;
            }

            .toggle-sidebar {
                display: inline-flex !important;
            }

            /* Hide desktop-only sidebar features on mobile */
            .sidebar-toggle-btn {
                display: none !important;
            }
        }

        /* Hide mobile toggle on desktop */
        @media (min-width: 768px) {
            .toggle-sidebar {
                display: none !important;
            }

            .sidebar-overlay {
                display: none !important;
            }
        }
    </style>
</head>

<body class="bg-gray-100 <?= $colorClass ?>-theme">
    <!-- Restore sidebar state immediately to prevent flicker (desktop only) -->
    <script>
        (function () {
            if (window.innerWidth >= 768) {
                const isMinimized = localStorage.getItem('sidebarMinimized') === 'true';
                if (isMinimized) {
                    document.documentElement.classList.add('sidebar-minimized-state');
                }
            }
        })();
    </script>

    <!-- Sidebar Overlay (Mobile) -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="header-content-wrapper">
                <div class="header-content">
                    <a href="<?= $config['homeUrl'] ?>" class="hover:opacity-80 transition">
                        <h2 class="text-xl font-bold sidebar-title"><?= $config['title'] ?></h2>
                    </a>
                    <p class="text-xs mt-1 opacity-75 sidebar-subtitle"><?= $config['subtitle'] ?></p>
                </div>
                <button class="sidebar-toggle-btn" onclick="toggleSidebarMinimize()" title="Toggle Sidebar">
                    <svg class="toggle-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
                    </svg>
                </button>
            </div>
        </div>

        <nav class="py-4">
            <?php foreach ($config['menuItems'] as $item): ?>
                <?php if (isset($item['section'])): ?>
                    <div class="menu-section"><?= $item['section'] ?></div>
                <?php else: ?>
                    <a href="/Worknest/public<?= $item['url'] ?>"
                        class="menu-item <?= strpos($_SERVER['REQUEST_URI'], $item['url']) !== false ? 'active' : '' ?>"
                        data-tooltip="<?= $item['tooltip'] ?>">
                        <?= Icons::{$item['icon']}() ?>
                        <span class="menu-text"><?= $item['label'] ?></span>
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>
        </nav>
    </aside>

    <!-- Top Navigation Bar -->
    <nav class="top-navbar">
        <div class="navbar-container">
            <!-- Mobile Toggle Button -->
            <button class="toggle-sidebar" onclick="toggleSidebar()">
                <?= Icons::menu('w-6 h-6') ?>
            </button>

            <!-- Left: Website Name -->
            <div class="navbar-brand">
                <a href="/Worknest/public/" class="brand-link flex items-center space-x-2">
                    <img src="/Worknest/public/images/logo.png" alt="WorkNest Logo" class="h-8 w-auto object-contain">
                    <span>WorkNest</span>
                </a>
            </div>

            <!-- Right: Navigation Links and User Profile -->
            <div class="navbar-right">
                <!-- Navigation Links -->
                <a href="/Worknest/public/" class="nav-link">
                    <?= Icons::home() ?>
                    <span>Home</span>
                </a>
                <a href="/Worknest/public/about" class="nav-link">
                    <?= Icons::info() ?>
                    <span>About Us</span>
                </a>

                <!-- User Profile Dropdown -->
                <div class="user-profile-container">
                    <button class="user-profile-button" onclick="toggleUserDropdown()">
                        <img src="<?= htmlspecialchars($userAvatar) ?>" alt="Avatar" class="user-avatar">
                        <span class="user-name"><?= htmlspecialchars($userName) ?></span>
                        <svg class="dropdown-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div class="user-dropdown" id="userDropdown">
                        <a href="/Worknest/public/profile" class="dropdown-item">
                            <?= Icons::settings() ?>
                            <span>Edit Profile</span>
                        </a>
                        <a href="/Worknest/public/logout" class="dropdown-item logout">
                            <?= Icons::logout() ?>
                            <span>Logout</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content" id="mainContent">