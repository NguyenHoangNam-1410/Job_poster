<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Admin Dashboard' ?></title>
    <link href="/css/tailwind.min.css" rel="stylesheet">
    <link href="/css/admin.css?v=<?= time() ?>" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <!-- Restore sidebar state immediately to prevent flicker -->
    <script>
        (function() {
            const isMinimized = localStorage.getItem('sidebarMinimized') === 'true';
            if (isMinimized) {
                document.documentElement.classList.add('sidebar-minimized-state');
            }
        })();
    </script>
    
    <?php 
    require_once __DIR__ . '/../../helpers/Icons.php';
    
    // Get current user's avatar from database
    $currentUserId = $_SESSION['user_id'] ?? 1;
    $userAvatar = '/Job_poster/public/image/avatar/default.svg'; // Default
    $userName = $_SESSION['user']['Name'] ?? 'Admin';
    
    if ($currentUserId) {
        require_once __DIR__ . '/../../dao/UserDAO.php';
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
                    <a href="/Job_poster/public/" class="hover:opacity-80 transition">
                        <h2 class="text-xl font-bold sidebar-title">Admin Panel</h2>
                    </a>
                    <p class="text-xs mt-1 opacity-75 sidebar-subtitle">System Management</p>
                </div>
                <button class="sidebar-toggle-btn" onclick="toggleSidebarMinimize()" title="Toggle Sidebar">
                    <svg class="toggle-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
                    </svg>
                </button>
            </div>
        </div>

        <nav class="py-4">
            <!-- User Management -->
            <div class="menu-section">User Management</div>
            <a href="/Job_poster/public/users"
                class="menu-item <?= strpos($_SERVER['REQUEST_URI'], '/users') !== false ? 'active' : '' ?>"
                data-tooltip="Users">
                <?= Icons::users() ?>
                <span class="menu-text">Users</span>
            </a>

            <!-- Category Management -->
            <div class="menu-section">Category Management</div>
            <a href="/Job_poster/public/job-categories"
                class="menu-item <?= strpos($_SERVER['REQUEST_URI'], '/job-categories') !== false ? 'active' : '' ?>"
                data-tooltip="Categories">
                <?= Icons::tag() ?>
                <span class="menu-text">Categories</span>
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

            <!-- History -->
            <div class="menu-section">History</div>
            <a href="/Job_poster/public/staff-actions"
                class="menu-item <?= strpos($_SERVER['REQUEST_URI'], '/staff-actions') !== false ? 'active' : '' ?>"
                data-tooltip="History">
                <?= Icons::history() ?>
                <span class="menu-text">History</span>
            </a>

            <!-- Statistics -->
            <div class="menu-section">Statistics</div>
            <a href="/Job_poster/public/statistics"
                class="menu-item <?= strpos($_SERVER['REQUEST_URI'], '/statistics') !== false ? 'active' : '' ?>"
                data-tooltip="Statistics">
                <?= Icons::statistic() ?>
                <span class="menu-text">Statistics</span>
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
        <div class="sidebar-footer px-5 py-4 bg-black bg-opacity-20 border-t border-white border-opacity-10">
            <div class="flex items-center">
                <div class="w-10 h-10 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                    <img src="<?= $_SESSION['user']['avatar'] ?? '/images/profile.png' ?>" 
                         alt="Admin Avatar" 
                         class="w-full h-full rounded-full">
                </div>
                <div class="ml-3">
                    <p class="text-sm font-semibold"><?= $_SESSION['user']['name'] ?? 'Admin User' ?></p>
                    <a href="/logout" class="text-xs opacity-75 hover:opacity-100">Log out</a>
                </div>
            </div>
        </div>
    </aside>
    <!-- Main Content -->
    <main class="main-content" id="mainContent">