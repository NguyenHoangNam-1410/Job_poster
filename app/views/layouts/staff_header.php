<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Staff Dashboard' ?></title>
    <link href="/Job_poster/public/css/tailwind.min.css" rel="stylesheet">
    <link href="/Job_poster/public/css/staff.css?v=<?= time() ?>" rel="stylesheet">
    <link href="/Job_poster/public/css/style.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <!-- Restore sidebar state immediately to prevent flicker -->
    <script>
        (function () {
            const isMinimized = localStorage.getItem('sidebarMinimized') === 'true';
            if (isMinimized) {
                document.documentElement.classList.add('sidebar-minimized-state');
            }
        })();
    </script>

    <?php
    require_once __DIR__ . '/../../helpers/Icons.php';

    // Get current user's avatar from database
    $currentUserId = $_SESSION['user']['id'] ?? null;
    $userAvatar = '/Job_poster/public/image/avatar/default.svg'; // Default
    $userName = $_SESSION['user']['name'] ?? 'Staff User';

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

    <!-- Mobile Toggle Button -->
    <button class="toggle-sidebar" onclick="toggleSidebar()">
        <?= Icons::menu('w-6 h-6') ?>
    </button>

    <!-- Sidebar Overlay (Mobile) -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="header-content-wrapper">
                <div class="header-content">
                    <a href="/Job_poster/public/staff/home" class="hover:opacity-80 transition">
                        <h2 class="text-xl font-bold sidebar-title">Staff Panel</h2>
                    </a>
                    <p class="text-xs mt-1 opacity-75 sidebar-subtitle">Support & Review</p>
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
            <!-- Dashboard Section -->
            <div class="menu-section">Dashboard</div>
            <a href="/Job_poster/public/staff/home"
                class="menu-item <?= strpos($_SERVER['REQUEST_URI'], '/staff/home') !== false ? 'active' : '' ?>"
                data-tooltip="Dashboard">
                <?= Icons::home() ?>
                <span class="menu-text">Dashboard</span>
            </a>

            <!-- Job Management -->
            <div class="menu-section">Job Management</div>
            <a href="/Job_poster/public/jobs-manage"
                class="menu-item <?= strpos($_SERVER['REQUEST_URI'], '/jobs') !== false ? 'active' : '' ?>"
                data-tooltip="Jobs">
                <?= Icons::briefcase() ?>
                <span class="menu-text">Jobs</span>
            </a>

            <!-- Request Posting -->
            <div class="menu-section">Request Posting</div>
            <a href="/Job_poster/public/approvals"
                class="menu-item <?= strpos($_SERVER['REQUEST_URI'], '/approvals') !== false ? 'active' : '' ?>"
                data-tooltip="Request Posting">
                <?= Icons::checklist() ?>
                <span class="menu-text">Request Posting</span>
            </a>

            <!-- Feedback -->
            <div class="menu-section">Feedback</div>
            <a href="/Job_poster/public/feedbacks"
                class="menu-item <?= strpos($_SERVER['REQUEST_URI'], '/feedbacks') !== false ? 'active' : '' ?>"
                data-tooltip="Feedback">
                <?= Icons::comment() ?>
                <span class="menu-text">Feedback</span>
            </a>
        </nav>
    </aside>

    <!-- Top Navigation Bar -->
    <nav class="top-navbar">
        <div class="navbar-container">
            <!-- Left: Website Name -->
            <div class="navbar-brand">
                <a href="/Job_poster/public/" class="brand-link">
                    Job Poster
                </a>
            </div>

            <!-- Right: Navigation Links and User Profile -->
            <div class="navbar-right">
                <!-- Navigation Links -->
                <a href="/Job_poster/public/" class="nav-link">
                    <?= Icons::home() ?>
                    <span>Home</span>
                </a>
                <a href="/Job_poster/public/about" class="nav-link">
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
                        <a href="/Job_poster/public/profile" class="dropdown-item">
                            <?= Icons::settings() ?>
                            <span>Edit Profile</span>
                        </a>
                        <a href="/Job_poster/public/logout" class="dropdown-item logout">
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