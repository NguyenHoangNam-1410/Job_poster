<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $pageTitle ?? 'WorkNest' ?></title>
  <link rel="icon" type="image/x-icon" href="images/favicon.png">

  <link rel="stylesheet" href="/css/tailwind.min.css">
  <link rel="stylesheet" href="/css/homepage.css">
    <link href="/css/style.css" rel="stylesheet">
    <!--Tailwind V3 version -->
  <?php if (isset($additionalCSS)): ?>
    <?php foreach ($additionalCSS as $css): ?>
      <link rel="stylesheet" href="<?= $css ?>">
    <?php endforeach; ?>
  <?php endif; ?>

  <style>
    /* 🔹 Header nhỏ gọn */
    header {
      transition: all 0.3s ease;
      padding-top: 0.6rem;
      padding-bottom: 0.6rem;
    }

    /* 🔹 Avatar mặc định */
    .avatar-btn {
      width: 38px;
      height: 38px;
      border-radius: 50%;
      overflow: hidden;
      cursor: pointer;
      transition: transform 0.2s ease;
      background-color: rgba(255,255,255,0.2);
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .avatar-btn:hover {
      transform: scale(1.05);
      background-color: rgba(255,255,255,0.3);
    }

    /* 🔹 Menu dropdown */
    .user-menu {
      display: none;
      position: absolute;
      right: 0;
      top: 3rem;
      background: white;
      color: #111;
      border-radius: 0.5rem;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      min-width: 150px;
      z-index: 100;
    }
    .user-menu a {
      display: block;
      padding: 10px 14px;
      font-size: 0.9rem;
      transition: background 0.2s;
    }
    .user-menu a:hover {
      background: #f3f4f6;
    }
  </style>
</head>

<body class="bg-gray-50">
  <?php require_once __DIR__ . '/../../helpers/Icons.php'; ?>

  <!-- Header -->
  <header class="gradient-bg text-white shadow-lg sticky top-0 z-50">
    <div class="container mx-auto px-4">
      <div class="flex justify-between items-center relative">
        <!-- Logo + Title (Left) -->
        <div class="flex items-center space-x-3">
          <a href="/" class="flex items-center space-x-2 hover:opacity-90 transition">
            <h1 class="text-2xl font-bold tracking-tight">Job Poster</h1>
          </a>
          <p class="hidden sm:block text-sm opacity-80">Find your dream job</p>
        </div>

        <!-- Navigation + Avatar (Right) -->
        <div class="flex items-center space-x-6">
          <!-- Navigation -->
          <nav class="hidden md:flex space-x-6 text-sm font-medium">
            <a href="/" class="hover:text-yellow-300 transition">Home</a>
            <a href="/jobs" class="hover:text-yellow-300 transition">Jobs</a>
            <a href="/auth/register" class="hover:text-yellow-300 transition">Register</a>
            <a href="/auth/login" class="hover:text-yellow-300 transition">Login</a>
          </nav>
        </div>
      </div>
    </div>
  </header>
