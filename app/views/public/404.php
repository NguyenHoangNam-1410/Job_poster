<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
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
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center p-4">
    <?php require_once __DIR__ . '/../../helpers/Icons.php'; ?>
    <div class="max-w-2xl w-full text-center">
        <!-- 404 Number -->
        <div class="mb-8">
            <h1 class="text-9xl font-bold text-indigo-600 float-animation">404</h1>
        </div>

        <!-- Error Icon -->
        <div class="mb-6">
            <?= Icons::sadFace('w-32 h-32 mx-auto text-indigo-400') ?>
        </div>

        <!-- Message -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Oops! Page Not Found</h2>
            <p class="text-lg text-gray-600 mb-2">
                The page you are looking for doesn't exist or has been moved.
            </p>
            <p class="text-gray-500">
                Let's get you back on track!
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <a href="/Job_poster/public/" 
               class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg shadow-lg hover:bg-indigo-700 transform hover:scale-105 transition-all duration-200">
                <?= Icons::home('w-5 h-5 mr-2') ?>
                Go Home
            </a>

            <button onclick="window.history.back()" 
                    class="inline-flex items-center px-6 py-3 bg-white text-indigo-600 font-semibold rounded-lg shadow-lg hover:bg-gray-50 transform hover:scale-105 transition-all duration-200 border-2 border-indigo-600">
                <?= Icons::arrowLeft('w-5 h-5 mr-2') ?>
                Go Back
            </button>
        </div>

        <!-- Additional Help -->
        <div class="mt-12 text-gray-500 text-sm">
            <p>Need help? Contact our support team or visit our help center.</p>
        </div>
    </div>
</body>
</html>
