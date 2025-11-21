<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $pageTitle ?? 'WorkNest' ?></title>
  <link rel="icon" type="image/x-icon" href="images/favicon.png">

  <link rel="stylesheet" href="/Job_poster/public/css/tailwind.min.css">
  <link rel="stylesheet" href="/Job_poster/public/css/homepage.css">
  <link rel="stylesheet" href="/Job_poster/public/css/style.css">

  <?php if (isset($additionalCSS)): ?>
    <?php foreach ($additionalCSS as $css): ?>
      <link rel="stylesheet" href="<?= $css ?>">
    <?php endforeach; ?>
  <?php endif; ?>
  <?php require_once __DIR__ . '/../../helpers/Icons.php'; ?>
  <style>
    .container {
      max-width: 100% !important;
    }

    .y2k-header {
      background: linear-gradient(180deg, #0a4d5c 0%, #1a8a9d 100%);
      border-bottom: 4px solid #0a4d5c;
      box-shadow: 0 4px 0 rgba(10, 77, 92, 0.3);
      padding: 1rem 0;
      position: sticky;
      top: 0;
      z-index: 50;
    }

    .y2k-logo {
      font-weight: 900;
      font-size: 1.75rem;
      text-transform: uppercase;
      letter-spacing: -0.02em;
      text-shadow: 2px 2px 0 rgba(180, 255, 57, 0.3);
    }

    .y2k-tagline {
      font-weight: 700;
      font-size: 0.75rem;
      text-transform: uppercase;
      letter-spacing: 0.1em;
      opacity: 0.9;
    }

    .y2k-nav-link {
      font-weight: 900;
      font-size: 0.875rem;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      padding: 0.5rem 0.8rem;
      transition: all 0.2s ease;
      border: 2px solid transparent;
    }

    .y2k-nav-link:hover {
      border: 2px solid #b4ff39;
      background: rgba(180, 255, 57, 0.1);
    }

    /* Sidebar (mobile) */
    .sidebar-overlay {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.3);
      z-index: 40;
    }

    .sidebar {
      position: fixed;
      top: 0;
      right: -250px;
      width: 250px;
      height: 100%;
      background: white;
      z-index: 50;
      transition: right 0.3s ease;
      box-shadow: -4px 0 6px rgba(0, 0, 0, 0.1);
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    /* Scrollable sidebar */
    .sidebar>div:first-child {
      overflow-y: auto;
      flex-grow: 1;
    }

    .sidebar.active {
      right: 0;
    }

    .sidebar-overlay.active {
      display: block;
    }

    .sidebar-header {
      padding: 1rem;
      border-bottom: 1px solid #eee;
    }

    .menu-section {
      font-weight: 700;
      padding: 0.75rem 1rem;
      color: #555;
      text-transform: uppercase;
      font-size: 0.75rem;
    }

    .menu-item {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.75rem 1rem;
      text-decoration: none;
      color: #0a4d5c;
      font-weight: 600;
    }

    .menu-item.active {
      background: #b4ff39;
      color: #0a4d5c;
    }

    .sidebar-footer {
      padding: 1rem;
      border-top: 1px solid #eee;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    /* Mobile toggle button */
    .toggle-sidebar {
      display: inline-flex;
      padding: 0.5rem;
      margin-left: auto;
      background: white;
      border-radius: 0.25rem;
    }

    @media(min-width:768px) {

      .toggle-sidebar,
      .sidebar,
      .sidebar-overlay {
        display: none !important;
      }
    }
  </style>
</head>

<body class="bg-gray-50">


  <!-- Header -->
  <header class="y2k-header text-white">
    <div class="container mx-auto px-4 flex items-center justify-between">
      <div class="flex items-center space-x-4 ml-4 flex-shrink-0">
        <a href="/Job_poster/public/" class="y2k-logo whitespace-nowrap">Job Poster</a>
        <span class="hidden sm:block y2k-tagline">Find Your Dream Job</span>
      </div>

      <!-- Desktop Nav -->
      <nav class="hidden md:flex items-center ml-auto">
        <?php if (!isset($_SESSION['user'])): ?>
          <a href="/Job_poster/public/" class="y2k-nav-link">Home</a>
          <a href="/Job_poster/public/jobs" class="y2k-nav-link">Jobs</a>
          <a href="/Job_poster/public/auth/login" class="y2k-nav-link">Login</a>
          <a href="/Job_poster/public/auth/register" class="y2k-nav-link">Register</a>
        <?php else: ?>
          <a href="/Job_poster/public/"
            class="y2k-nav-link <?= $_SERVER['REQUEST_URI'] === '/Job_poster/public/' ? 'active' : '' ?>">Home</a>
          <?php if ($_SESSION['user']['role'] == 'Employer'): ?>
            <a href="/Job_poster/public/company-profile"
              class="y2k-nav-link <?= strpos($_SERVER['REQUEST_URI'], '/company-profile') !== false ? 'active' : '' ?>">Company
              Profile</a>
            <a href="/Job_poster/public/my-jobs"
              class="y2k-nav-link <?= strpos($_SERVER['REQUEST_URI'], '/my-jobs') !== false ? 'active' : '' ?>">My Jobs</a>
            <a href="/Job_poster/public/my-feedbacks"
              class="y2k-nav-link <?= strpos($_SERVER['REQUEST_URI'], '/my-feedbacks') !== false ? 'active' : '' ?>">Feedbacks</a>
          <?php elseif ($_SESSION['user']['role'] == 'Admin'): ?>
            <a href="/Job_poster/public/users" class="y2k-nav-link">Users</a>
            <a href="/Job_poster/public/job-categories" class="y2k-nav-link">Categories</a>
            <a href="/Job_poster/public/jobs-manage" class="y2k-nav-link">Jobs</a>
            <a href="/Job_poster/public/approvals" class="y2k-nav-link">Requests</a>
            <a href="/Job_poster/public/staff-actions" class="y2k-nav-link">History</a>
            <a href="/Job_poster/public/statistics" class="y2k-nav-link">Statistics</a>
            <a href="/Job_poster/public/feedbacks" class="y2k-nav-link">Feedbacks</a>
          <?php elseif ($_SESSION['user']['role'] == 'Staff'): ?>
            <a href="/Job_poster/public/approvals" class="y2k-nav-link">Request Approval</a>
            <a href="/Job_poster/public/reports" class="y2k-nav-link">Reports</a>
            <a href="/Job_poster/public/jobs-manage" class="y2k-nav-link">Jobs</a>
          <?php endif; ?>
        <?php endif; ?>

        <!-- Profile Section -->
        <?php if (isset($_SESSION['user'])): ?>
          <a href="/Job_poster/public/profile" class="flex items-center space-x-2 y2k-nav-link transition p-2 rounded-lg">
            <img src="<?= $_SESSION['user']['avatar'] ?? '/Job_poster/public/image/avatar/default.svg' ?>"
              alt="User Avatar" class="w-10 h-10 rounded-full border-2 border-white shadow">
            <div class="flex flex-col">
              <span class="text-sm font-semibold text-white"><?= $_SESSION['user']['name'] ?? 'User' ?></span>
              <span class="text-xs text-gray-200"><?= $_SESSION['user']['role'] ?? 'User' ?></span>
            </div>
          </a>
          <a href="/Job_poster/public/logout"
            class="flex items-center space-x-1 text-sm font-semibold text-red-400 hover:text-red-600 hover:bg-red-100/20 px-3 py-1 rounded-lg transition">
            <span>Logout</span>
            <?= Icons::logout('w-4 h-4') ?>
          </a>
        <?php endif; ?>
      </nav>


      <button class="toggle-sidebar md:hidden text-black" onclick="toggleSidebar()">
        <?= Icons::menu('w-6 h-6') ?>
      </button>
    </div>
  </header>

  <!-- Sidebar Overlay -->
  <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
  <!-- Sidebar -->
  <aside class="sidebar" id="sidebar">
    <div>
      <div class="sidebar-header">
        <h2 class="text-xl font-bold">Menu</h2>
      </div>

      <nav>
        <?php if (!isset($_SESSION['user'])): ?>
          <a href="/Job_poster/public/" class="menu-item">Home</a>
          <a href="/Job_poster/public/jobs" class="menu-item">Jobs</a>
          <a href="/Job_poster/public/auth/login" class="menu-item">Login</a>
          <a href="/Job_poster/public/auth/register" class="menu-item">Register</a>
        <?php else: ?>
          <a href="/Job_poster/public/"
            class="menu-item <?= $_SERVER['REQUEST_URI'] === '/Job_poster/public/' ? 'active' : '' ?>"
            data-tooltip="Home">
            <?= Icons::home() ?>
            <span class="menu-text">Home</span>
          </a>
          <?php if ($_SESSION['user']['role'] == 'Employer'): ?>
            <div class="menu-section">Company Management</div>
            <a href="/Job_poster/public/company-profile"
              class="menu-item <?= strpos($_SERVER['REQUEST_URI'], '/company-profile') !== false ? 'active' : '' ?>"
              data-tooltip="Company Profile">
              <?= Icons::profile() ?>
              <span class="menu-text">My Company Profile</span>
            </a>
            <div class="menu-section">Job Management</div>
            <a href="/Job_poster/public/my-jobs"
              class="menu-item <?= strpos($_SERVER['REQUEST_URI'], '/my-jobs') !== false ? 'active' : '' ?>"
              data-tooltip="Jobs">
              <?= Icons::briefcase() ?>
              <span class="menu-text">My Jobs</span>
            </a>
            <div class="menu-section">Feedback Management</div>
            <a href="/Job_poster/public/my-feedbacks"
              class="menu-item <?= strpos($_SERVER['REQUEST_URI'], '/my-feedbacks') !== false ? 'active' : '' ?>"
              data-tooltip="Feedback">
              <?= Icons::comment() ?>
              <span class="menu-text">My Feedbacks</span>
            </a>
          <?php elseif ($_SESSION['user']['role'] == 'Admin'): ?>
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

            <!-- Staff Views -->
          <?php elseif ($_SESSION['user']['role'] == 'Staff'): ?>
            <div class="menu-section">Job Requests</div>
            <a href="/Job_poster/public/approvals"
              class="menu-item <?= strpos($_SERVER['REQUEST_URI'], '/approvals') !== false ? 'active' : '' ?>">
              <?= Icons::checklist() ?>
              Request Approval
            </a>
            <div class="menu-section">Reports & Complaints</div>
            <a href="/Job_poster/public/reports"
              class="menu-item <?= strpos($_SERVER['REQUEST_URI'], '/reports') !== false ? 'active' : '' ?>">
              <?= Icons::flag() ?>
              Review Reports
            </a>
            <div class="menu-section">Job Management</div>
            <a href="/Job_poster/public/jobs-manage"
              class="menu-item <?= strpos($_SERVER['REQUEST_URI'], '/jobs-manage') !== false ? 'active' : '' ?>">
              <?= Icons::briefcase() ?>
              Jobs
            </a>
          <?php endif; ?>
        <?php endif; ?>
      </nav>
    </div>

    <div class="sidebar-footer">
      <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center">
        <img src="<?= $_SESSION['user']['avatar'] ?? 'image/avatar/default.svg' ?>" alt="User"
          class="w-full h-full rounded-full">
      </div>
      <div>
        <p class="text-sm font-semibold"><?= $_SESSION['user']['name'] ?? 'Guest' ?></p>
        <?php if (isset($_SESSION['user'])): ?>
          <a href="/Job_poster/public/logout" class="text-xs opacity-75 hover:opacity-100">Log
            out<?= Icons::logout() ?></a>
        <?php endif; ?>
      </div>
    </div>
  </aside>

  <!-- Main Content -->
  <main class="main-content" id="mainContent">
    <!-- Your page content here -->