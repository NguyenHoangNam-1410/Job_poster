<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Job Poster' ?></title>
    <link rel="stylesheet" href="/Job_poster/public/css/tailwind.min.css">
    <link rel="stylesheet" href="/Job_poster/public/css/homepage.css">
    <?php if (isset($additionalCSS)): ?>
        <?php foreach ($additionalCSS as $css): ?>
            <link rel="stylesheet" href="<?= $css ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body class="bg-gray-50">
    <?php require_once __DIR__ . '/../../helpers/Icons.php'; ?>
    
    <!-- Header -->
    <header class="gradient-bg text-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4 py-6">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <a href="/Job_poster/public/" class="flex items-center space-x-3 hover:opacity-80 transition">
                        <h1 class="text-3xl font-bold">Job Poster</h1>
                    </a>
                    <p class="text-sm opacity-90">Find your dream job</p>
                </div>
                <nav class="hidden md:flex space-x-6">
                    <a href="/Job_poster/public/" class="hover:text-yellow-300 transition">Home</a>
                    <a href="/Job_poster/public/jobs-manage" class="hover:text-yellow-300 transition">Jobs</a>
                    <a href="/Job_poster/public/users" class="hover:text-yellow-300 transition">Admin</a>
                </nav>
            </div>
        </div>
    </header>
