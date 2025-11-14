<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $pageTitle ?? 'WorkNest' ?></title>
  <link rel="icon" type="image/x-icon" href="images/favicon.png">

  <link rel="stylesheet" href="/Job_poster/public/css/tailwind.min.css">
  <link rel="stylesheet" href="/Job_poster/public/css/homepage.css">
    <link href="/Job_poster/public/css/style.css" rel="stylesheet">
    <!--Tailwind V3 version -->
  <?php if (isset($additionalCSS)): ?>
    <?php foreach ($additionalCSS as $css): ?>
      <link rel="stylesheet" href="<?= $css ?>">
    <?php endforeach; ?>
  <?php endif; ?>

  <style>
    /* Y2K Retro Header */
    .y2k-header {
      background: linear-gradient(180deg, #0a4d5c 0%, #1a8a9d 100%);
      border-bottom: 4px solid #0a4d5c;
      box-shadow: 0 4px 0px rgba(10, 77, 92, 0.3);
      transition: all 0.3s ease;
      padding-top: 1rem;
      padding-bottom: 1rem;
    }

    /* Y2K Logo Style */
    .y2k-logo {
      font-weight: 900;
      font-size: 1.75rem;
      letter-spacing: -0.02em;
      text-transform: uppercase;
      text-shadow: 2px 2px 0px rgba(180, 255, 57, 0.3);
      transition: all 0.2s ease;
    }
    .y2k-logo:hover {
      text-shadow: 3px 3px 0px rgba(180, 255, 57, 0.5);
    }

    /* Y2K Navigation Links */
    .y2k-nav-link {
      font-weight: 900;
      font-size: 0.875rem;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      padding: 0.5rem 1rem;
      border: 2px solid transparent;
      transition: all 0.2s ease;
      position: relative;
    }
    .y2k-nav-link:hover {
      border: 2px solid #b4ff39;
      background: rgba(180, 255, 57, 0.1);
      transform: translateY(-2px);
    }

    /* Y2K Avatar Button */
    .y2k-avatar {
      width: 44px;
      height: 44px;
      background: white;
      border: 3px solid #0a4d5c;
      box-shadow: 3px 3px 0px #0a4d5c;
      cursor: pointer;
      transition: all 0.2s ease;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .y2k-avatar:hover {
      transform: translate(-2px, -2px);
      box-shadow: 5px 5px 0px #0a4d5c;
    }
    .y2k-avatar:active {
      transform: translate(1px, 1px);
      box-shadow: 2px 2px 0px #0a4d5c;
    }

    /* Y2K Dropdown Menu */
    .y2k-menu {
      display: none;
      position: absolute;
      right: 0;
      top: 4rem;
      background: white;
      border: 3px solid #0a4d5c;
      box-shadow: 6px 6px 0px rgba(10, 77, 92, 0.3);
      min-width: 180px;
      z-index: 100;
    }
    .y2k-menu a {
      display: block;
      padding: 12px 16px;
      font-size: 0.875rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      color: #0a4d5c;
      border-bottom: 2px solid #e5e7eb;
      transition: all 0.2s;
    }
    .y2k-menu a:last-child {
      border-bottom: none;
    }
    .y2k-menu a:hover {
      background: linear-gradient(135deg, #b4ff39 0%, #2db8ac 100%);
      color: #0a4d5c;
    }

    /* Y2K Tagline */
    .y2k-tagline {
      font-weight: 700;
      font-size: 0.75rem;
      text-transform: uppercase;
      letter-spacing: 0.1em;
      opacity: 0.9;
    }
  </style>
</head>


<body class="bg-gray-50">
  <?php require_once __DIR__ . '/../../helpers/Icons.php'; ?>

  <!-- Y2K Header -->
  <header class="y2k-header text-white sticky top-0 z-50">
    <div class="container mx-auto px-4">
      <div class="flex justify-between items-center relative">
        <!-- Logo + Tagline -->
        <div class="flex items-center space-x-4">
          <a href="/Job_poster/public/" class="y2k-logo">
            Job Poster
          </a>
          <span class="hidden sm:block y2k-tagline">Find Your Dream Job</span>
        </div>

        <!-- Navigation -->
        <nav class="hidden md:flex space-x-2">
          <a href="/Job_poster/public/" class="y2k-nav-link">Home</a>
          <a href="/Job_poster/public/jobs" class="y2k-nav-link">Jobs</a>
          <a href="/Job_poster/public/employer/post-job" class="y2k-nav-link">Employer</a>
        </nav>

        <!-- Avatar / Dropdown -->
        <div class="relative">
          <!-- Y2K Avatar -->
          <div class="y2k-avatar" id="avatarBtn" title="Login or Register">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                 stroke-width="2.5" stroke="#0a4d5c" class="w-6 h-6">
              <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15.75 9a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
              <path stroke-linecap="round" stroke-linejoin="round"
                    d="M4.5 19.5a8.25 8.25 0 0115 0" />
            </svg>
          </div>

          <!-- Y2K Menu -->
          <div id="userMenu" class="y2k-menu">
            <a href="/Job_poster/public/auth/login">🔑 Login</a>
            <a href="/Job_poster/public/auth/register">📝 Register</a>
          </div>
        </div>
      </div>
    </div>
  </header>

  <script>
    const avatarBtn = document.getElementById('avatarBtn');
    const userMenu = document.getElementById('userMenu');

    avatarBtn.addEventListener('click', () => {
      userMenu.style.display = userMenu.style.display === 'block' ? 'none' : 'block';
    });

    // Hide menu when clicking outside
    document.addEventListener('click', (e) => {
      if (!avatarBtn.contains(e.target) && !userMenu.contains(e.target)) {
        userMenu.style.display = 'none';
      }
    });
  </script>
