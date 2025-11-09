<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../helpers/Auth.php';

$reason = $_GET['reason'] ?? 'login';
// $isLoggedIn = Auth::isLoggedIn();
// $isGuest = Auth::isGuest();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>401 - Unauthorized</title>
    <link href="/Job_poster/public/css/tailwind.min.css" rel="stylesheet">
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .float-animation {
            animation: float 3s ease-in-out infinite;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-red-50 to-orange-100 min-h-screen flex items-center justify-center p-4">
    <?php require_once __DIR__ . '/../../helpers/Icons.php'; ?>
    <div class="max-w-2xl w-full text-center">
        <!-- 401 Number -->
        <div class="mb-8">
            <h1 class="text-9xl font-bold text-red-600 float-animation">401</h1>
        </div>

        <!-- Error Icon -->
        <div class="mb-6">
            <?= Icons::lock('w-32 h-32 mx-auto text-red-400') ?>
        </div>

        <!-- Message -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">
                <?php if ($reason === 'forbidden' && $isGuest): ?>
                    Admin Access Only
                <?php else: ?>
                    Unauthorized Access
                <?php endif; ?>
            </h2>
            <p class="text-lg text-gray-600 mb-2">
                <?php 
                if ($reason === 'expired') {
                    echo "Your session has expired. Please log in again.";
                } elseif ($reason === 'forbidden' && $isGuest) {
                    echo "This area is restricted to administrators only.";
                } elseif ($reason === 'forbidden') {
                    echo "You don't have permission to access this page.";
                } else {
                    echo "You need to be logged in to access this page.";
                }
                ?>
            </p>
            <p class="text-gray-500">
                <?php if ($reason === 'forbidden' && $isGuest): ?>
                    If you need admin access, please contact the system administrator.
                <?php else: ?>
                    Please authenticate to continue.
                <?php endif; ?>
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <?php if ($reason === 'forbidden' && $isLoggedIn): ?>
                <!-- Guest trying to access admin area - show home button -->
                <a href="/Job_poster/public/" 
                   class="inline-flex items-center px-6 py-3 bg-red-600 text-white font-semibold rounded-lg shadow-lg hover:bg-red-700 transform hover:scale-105 transition-all duration-200">
                    <?= Icons::home('w-5 h-5 mr-2') ?>
                    Go to Store
                </a>
            <?php else: ?>
                <!-- Not logged in or session expired - show login -->
                <!-- <a href="/Job_poster/public/login" 
                   class="inline-flex items-center px-6 py-3 bg-red-600 text-white font-semibold rounded-lg shadow-lg hover:bg-red-700 transform hover:scale-105 transition-all duration-200">
                    <?= Icons::login('w-5 h-5 mr-2') ?>
                    Login
                </a> -->
                <a href="/Job_poster/public/" 
                   class="inline-flex items-center px-6 py-3 bg-white text-red-600 font-semibold rounded-lg shadow-lg hover:bg-gray-50 transform hover:scale-105 transition-all duration-200 border-2 border-red-600">
                    <?= Icons::home('w-5 h-5 mr-2') ?>
                    Go Home
                </a>
            <?php endif; ?>
        </div>

        <!-- Additional Help -->
        <div class="mt-12 text-gray-500 text-sm">
            <?php if (!$isLoggedIn): ?>
                <p>Don't have an account? <a href="/Job_poster/public/signup" class="text-red-600 hover:underline">Sign up here</a></p>
            <?php elseif ($isGuest && $reason === 'forbidden'): ?>
                <p>Need help finding something? <a href="/Job_poster/public/shop" class="text-red-600 hover:underline">Browse our products</a></p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
