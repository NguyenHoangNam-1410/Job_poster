<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $pageTitle ?? 'WorkNest' ?></title>
  <link rel="icon" type="image/x-icon" href="/Worknest/public/images/favicon.png">

  <link rel="stylesheet" href="/Worknest/public/css/tailwind.min.css">
  <link rel="stylesheet" href="/Worknest/public/css/homepage.css">
  <link rel="stylesheet" href="/Worknest/public/css/style.css">

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
      overflow: visible !important; /* Cho phép dropdown hiển thị ngoài header */
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
      position: relative;
    }

    .y2k-nav-link:hover {
      border: 2px solid #b4ff39;
      background: rgba(180, 255, 57, 0.1);
    }

    .y2k-nav-link.active {
      border-bottom: 3px solid #b4ff39;
      background: rgba(180, 255, 57, 0.15);
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

    /* Profile Dropdown Styles */
    #profileDropdownMenu,
    #guestDropdownMenu {
      font-family: 'Courier New', monospace;
      border-radius: 0 !important;
      box-shadow: 6px 6px 0 rgba(6, 136, 180, 0.15) !important;
      opacity: 0;
      visibility: hidden;
      transform: translateY(-10px);
      transition: all 0.2s ease;
      position: absolute !important; /* Đảm bảo absolute positioning */
      top: 100% !important; /* Đặt ngay dưới button */
      right: 0 !important;
      margin-top: 0.5rem !important;
    }
    
    /* Đảm bảo dropdown container không làm header mở rộng */
    #guestDropdown,
    #profileDropdown {
      position: relative !important;
      display: inline-flex !important;
      align-items: center !important;
      overflow: visible !important;
      flex-shrink: 0 !important;
      height: auto !important;
      min-height: auto !important;
    }
    
    /* Đảm bảo header container không bị overflow */
    .y2k-header .container {
      overflow: visible !important;
      position: relative !important;
    }
    
    /* Đảm bảo nav không bị ảnh hưởng */
    .y2k-header nav {
      overflow: visible !important;
    }

    #profileDropdownMenu.show,
    #guestDropdownMenu.show {
      opacity: 1;
      visibility: visible;
      transform: translateY(0);
    }

    #profileDropdownMenu a,
    #guestDropdownMenu a {
      transition: all 0.2s ease;
    }

    #profileDropdownMenu a:hover,
    #guestDropdownMenu a:hover {
      background: #f8f8f8 !important;
      transform: translate(-1px, -1px);
      box-shadow: 2px 2px 0 rgba(0, 0, 0, 0.1);
    }
  </style>
  <script>
    // Profile Dropdown Toggle
    document.addEventListener('DOMContentLoaded', function() {
      // Guest dropdown
      const guestDropdownBtn = document.getElementById('guestDropdownBtn');
      const guestDropdownMenu = document.getElementById('guestDropdownMenu');
      
      if (guestDropdownBtn && guestDropdownMenu) {
        guestDropdownBtn.addEventListener('click', function(e) {
          e.stopPropagation();
          // Toggle show class thay vì hidden
          guestDropdownMenu.classList.toggle('show');
          // Đảm bảo hidden được remove khi show
          if (guestDropdownMenu.classList.contains('show')) {
            guestDropdownMenu.classList.remove('hidden');
          } else {
            guestDropdownMenu.classList.add('hidden');
          }
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
          if (!guestDropdownBtn.contains(e.target) && !guestDropdownMenu.contains(e.target)) {
            guestDropdownMenu.classList.remove('show');
            guestDropdownMenu.classList.add('hidden');
          }
        });
      }

      // Logged in user dropdown
      const dropdownBtn = document.getElementById('profileDropdownBtn');
      const dropdownMenu = document.getElementById('profileDropdownMenu');
      
      if (dropdownBtn && dropdownMenu) {
        dropdownBtn.addEventListener('click', function(e) {
          e.stopPropagation();
          // Toggle show class thay vì hidden
          dropdownMenu.classList.toggle('show');
          // Đảm bảo hidden được remove khi show
          if (dropdownMenu.classList.contains('show')) {
            dropdownMenu.classList.remove('hidden');
          } else {
            dropdownMenu.classList.add('hidden');
          }
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
          if (!dropdownBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
            dropdownMenu.classList.remove('show');
            dropdownMenu.classList.add('hidden');
          }
        });
      }
    });
  </script>
</head>

<body class="bg-gray-50">

<?php
// Safely get user role to avoid "Undefined array key" errors
$userRole = $_SESSION['user']['role'] ?? null;
?>

  <!-- Header -->
  <header class="y2k-header text-white">
    <div class="container mx-auto px-4 flex items-center justify-between relative" style="overflow: visible;">
      <div class="flex items-center space-x-4 ml-4 flex-shrink-0">
        <a href="/Worknest/public/" class="flex items-center space-x-2">
          <img src="/Worknest/public/images/logo.png" alt="WorkNest Logo" class="h-10 w-auto object-contain">
          <span class="y2k-logo whitespace-nowrap">WorkNest</span>
        </a>
        <span class="hidden sm:block y2k-tagline">Find Your Dream Job</span>
      </div>

      <!-- Desktop Nav - Centered -->
      <nav class="hidden md:flex items-center absolute left-1/2 transform -translate-x-1/2">
        <?php if (!isset($_SESSION['user'])): ?>
          <a href="/Worknest/public/" class="y2k-nav-link <?= $_SERVER['REQUEST_URI'] === '/Worknest/public/' || $_SERVER['REQUEST_URI'] === '/Worknest/public' ? 'active' : '' ?>">Home</a>
          <a href="/Worknest/public/jobs" class="y2k-nav-link <?= strpos($_SERVER['REQUEST_URI'], '/jobs') !== false ? 'active' : '' ?>">Jobs</a>
          <a href="/Worknest/public/auth/register?redirect=/employer/home" class="y2k-nav-link <?= strpos($_SERVER['REQUEST_URI'], '/auth/register') !== false ? 'active' : '' ?>">Job Posting</a>
        <?php else: ?>
          <a href="/Worknest/public/"
            class="y2k-nav-link <?= $_SERVER['REQUEST_URI'] === '/Worknest/public/' ? 'active' : '' ?>">Home</a>
          <?php if ($userRole == 'Employer'): ?>
            <a href="/Worknest/public/jobs" class="y2k-nav-link <?= strpos($_SERVER['REQUEST_URI'], '/jobs') !== false && strpos($_SERVER['REQUEST_URI'], '/my-jobs') === false && strpos($_SERVER['REQUEST_URI'], '/jobs-manage') === false ? 'active' : '' ?>">Jobs</a>
            <a href="/Worknest/public/employer/home" class="y2k-nav-link <?= strpos($_SERVER['REQUEST_URI'], '/employer') !== false || strpos($_SERVER['REQUEST_URI'], '/my-jobs') !== false || strpos($_SERVER['REQUEST_URI'], '/company-profile') !== false || strpos($_SERVER['REQUEST_URI'], '/my-feedbacks') !== false ? 'active' : '' ?>">Job Posting</a>
          <?php elseif ($userRole == 'Admin'): ?>
            <a href="/Worknest/public/jobs" class="y2k-nav-link <?= strpos($_SERVER['REQUEST_URI'], '/jobs') !== false && strpos($_SERVER['REQUEST_URI'], '/jobs-manage') === false ? 'active' : '' ?>">Jobs</a>
            <a href="/Worknest/public/statistics" class="y2k-nav-link <?= strpos($_SERVER['REQUEST_URI'], '/admin') !== false || strpos($_SERVER['REQUEST_URI'], '/users') !== false || strpos($_SERVER['REQUEST_URI'], '/job-categories') !== false || strpos($_SERVER['REQUEST_URI'], '/jobs-manage') !== false || strpos($_SERVER['REQUEST_URI'], '/approvals') !== false || strpos($_SERVER['REQUEST_URI'], '/staff-actions') !== false || strpos($_SERVER['REQUEST_URI'], '/statistics') !== false || strpos($_SERVER['REQUEST_URI'], '/feedbacks') !== false ? 'active' : '' ?>">Admin Panel</a>
          <?php elseif ($userRole == 'Staff'): ?>
            <a href="/Worknest/public/jobs" class="y2k-nav-link <?= strpos($_SERVER['REQUEST_URI'], '/jobs') !== false && strpos($_SERVER['REQUEST_URI'], '/jobs-manage') === false && strpos($_SERVER['REQUEST_URI'], '/approvals') === false ? 'active' : '' ?>">Jobs</a>
            <a href="/Worknest/public/staff/home" class="y2k-nav-link <?= strpos($_SERVER['REQUEST_URI'], '/staff') !== false || strpos($_SERVER['REQUEST_URI'], '/jobs-manage') !== false || strpos($_SERVER['REQUEST_URI'], '/approvals') !== false || strpos($_SERVER['REQUEST_URI'], '/feedbacks') !== false ? 'active' : '' ?>">Staff Panel</a>
          <?php endif; ?>
        <?php endif; ?>
      </nav>

      <!-- Profile Section with Dropdown - Right Side -->
      <?php if (!isset($_SESSION['user'])): ?>
        <!-- Guest User Avatar Dropdown -->
        <div class="relative ml-auto" id="guestDropdown">
          <button type="button" class="flex items-center space-x-2 y2k-nav-link transition p-2 rounded-lg cursor-pointer" id="guestDropdownBtn">
            <img src="/Worknest/public/image/avatar/default.svg"
              alt="Guest Avatar" class="w-10 h-10 rounded-full border-2 border-white shadow">
            <svg class="w-4 h-4 text-white ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
          </button>
          
          <!-- Dropdown Menu -->
          <div id="guestDropdownMenu" class="hidden absolute right-0 mt-2 w-48 bg-white border-2 border-black shadow-lg rounded-none z-50">
            <a href="/Worknest/public/auth/login" class="block px-4 py-3 text-sm font-semibold text-black hover:bg-gray-100 uppercase font-mono border-b-2 border-black">
              Login
            </a>
            <a href="/Worknest/public/auth/register" class="block px-4 py-3 text-sm font-semibold text-black hover:bg-gray-100 uppercase font-mono">
              Register
            </a>
          </div>
        </div>
      <?php else: ?>
        <div class="relative ml-auto" id="profileDropdown">
          <button type="button" class="flex items-center space-x-2 y2k-nav-link transition p-2 rounded-lg cursor-pointer" id="profileDropdownBtn">
            <img src="<?= $_SESSION['user']['avatar'] ?? '/Worknest/public/image/avatar/default.svg' ?>"
              alt="User Avatar" class="w-10 h-10 rounded-full border-2 border-white shadow">
            <div class="flex flex-col">
              <span class="text-sm font-semibold text-white"><?= $_SESSION['user']['name'] ?? 'User' ?></span>
              <span class="text-xs text-gray-200"><?= $userRole ?? 'User' ?></span>
            </div>
            <svg class="w-4 h-4 text-white ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
          </button>
          
          <!-- Dropdown Menu -->
          <div id="profileDropdownMenu" class="hidden absolute right-0 top-full mt-2 w-48 bg-white border-2 border-black shadow-lg rounded-none z-50" style="position: absolute !important; top: 100% !important; right: 0 !important;">
            <?php if ($userRole == 'Admin'): ?>
              <a href="/Worknest/public/statistics" class="block px-4 py-3 text-sm font-semibold text-black hover:bg-gray-100 uppercase font-mono border-b-2 border-black">
                Admin Panel
              </a>
            <?php elseif ($userRole == 'Staff'): ?>
              <a href="/Worknest/public/staff/home" class="block px-4 py-3 text-sm font-semibold text-black hover:bg-gray-100 uppercase font-mono border-b-2 border-black">
                Staff Panel
              </a>
            <?php elseif ($userRole == 'Employer'): ?>
              <a href="/Worknest/public/employer/home" class="block px-4 py-3 text-sm font-semibold text-black hover:bg-gray-100 uppercase font-mono border-b-2 border-black">
                Job posting 
              </a>
            <?php endif; ?>
            <a href="/Worknest/public/logout" class="block px-4 py-3 text-sm font-semibold text-red-600 hover:bg-red-50 uppercase font-mono">
              Logout
            </a>
          </div>
        </div>
      <?php endif; ?>


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
          <a href="/Worknest/public/" class="menu-item">Home</a>
          <a href="/Worknest/public/jobs" class="menu-item">Jobs</a>
          <a href="/Worknest/public/auth/login" class="menu-item">Login</a>
          <a href="/Worknest/public/auth/register" class="menu-item">Register</a>
        <?php else: ?>
          <a href="/Worknest/public/"
            class="menu-item <?= $_SERVER['REQUEST_URI'] === '/Worknest/public/' ? 'active' : '' ?>"
            data-tooltip="Home">
            <?= Icons::home() ?>
            <span class="menu-text">Home</span>
          </a>
          <?php if ($userRole == 'Employer'): ?>
            <div class="menu-section">Company Management</div>
            <a href="/Worknest/public/company-profile"
              class="menu-item <?= strpos($_SERVER['REQUEST_URI'], '/company-profile') !== false ? 'active' : '' ?>"
              data-tooltip="Company Profile">
              <?= Icons::profile() ?>
              <span class="menu-text">My Company Profile</span>
            </a>
            <div class="menu-section">Job Management</div>
            <a href="/Worknest/public/my-jobs"
              class="menu-item <?= strpos($_SERVER['REQUEST_URI'], '/my-jobs') !== false ? 'active' : '' ?>"
              data-tooltip="Jobs">
              <?= Icons::briefcase() ?>
              <span class="menu-text">My Jobs</span>
            </a>
            <div class="menu-section">Feedback Management</div>
            <a href="/Worknest/public/my-feedbacks"
              class="menu-item <?= strpos($_SERVER['REQUEST_URI'], '/my-feedbacks') !== false ? 'active' : '' ?>"
              data-tooltip="Feedback">
              <?= Icons::comment() ?>
              <span class="menu-text">My Feedbacks</span>
            </a>
          <?php elseif ($userRole == 'Admin'): ?>
            <a href="/Worknest/public/jobs"
              class="menu-item <?= strpos($_SERVER['REQUEST_URI'], '/jobs') !== false && strpos($_SERVER['REQUEST_URI'], '/jobs-manage') === false ? 'active' : '' ?>"
              data-tooltip="Jobs">
              <?= Icons::briefcase() ?>
              <span class="menu-text">Jobs</span>
            </a>
            <a href="/Worknest/public/statistics"
              class="menu-item <?= strpos($_SERVER['REQUEST_URI'], '/admin') !== false || strpos($_SERVER['REQUEST_URI'], '/users') !== false || strpos($_SERVER['REQUEST_URI'], '/job-categories') !== false || strpos($_SERVER['REQUEST_URI'], '/jobs-manage') !== false || strpos($_SERVER['REQUEST_URI'], '/approvals') !== false || strpos($_SERVER['REQUEST_URI'], '/staff-actions') !== false || strpos($_SERVER['REQUEST_URI'], '/statistics') !== false || strpos($_SERVER['REQUEST_URI'], '/feedbacks') !== false ? 'active' : '' ?>"
              data-tooltip="Admin Panel">
              <?= Icons::users() ?>
              <span class="menu-text">Admin Panel</span>
            </a>

            <!-- Staff Views -->
          <?php elseif ($userRole == 'Staff'): ?>
            <div class="menu-section">Job Requests</div>
            <a href="/Worknest/public/approvals"
              class="menu-item <?= strpos($_SERVER['REQUEST_URI'], '/approvals') !== false ? 'active' : '' ?>">
              <?= Icons::checklist() ?>
              Request Approval
            </a>
            <div class="menu-section">Reports & Complaints</div>
            <a href="/Worknest/public/reports"
              class="menu-item <?= strpos($_SERVER['REQUEST_URI'], '/reports') !== false ? 'active' : '' ?>">
              <?= Icons::flag() ?>
              Review Reports
            </a>
            <div class="menu-section">Job Management</div>
            <a href="/Worknest/public/jobs-manage"
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
          <a href="/Worknest/public/logout" class="text-xs opacity-75 hover:opacity-100">Log
            out<?= Icons::logout() ?></a>
        <?php endif; ?>
      </div>
    </div>
  </aside>

  <!-- Main Content -->
  <main class="main-content" id="mainContent">
    <!-- Your page content here -->