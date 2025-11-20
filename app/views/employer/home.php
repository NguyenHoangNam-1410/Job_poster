<?php 
require_once '../app/views/layouts/employer_header.php';
?>

<div class="list-container">
    <div class="list-content">
        <!-- Welcome Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">Hi, <?= htmlspecialchars($_SESSION['user']['name'] ?? 'Employer'); ?>!</h1>
            <p class="text-gray-600">Welcome to your Employer Dashboard</p>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <a href="/Job_poster/public/company-profile" class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                <div class="text-green-600 mb-2"><?= Icons::building('w-8 h-8') ?></div>
                <h3 class="font-bold text-lg">Company Profile</h3>
                <p class="text-sm text-gray-600">Manage your company information</p>
            </a>
            
            <a href="/Job_poster/public/my-jobs" class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                <div class="text-blue-600 mb-2"><?= Icons::briefcase('w-8 h-8') ?></div>
                <h3 class="font-bold text-lg">My Jobs</h3>
                <p class="text-sm text-gray-600">View and manage your job postings</p>
            </a>
            
            <a href="/Job_poster/public/my-feedbacks" class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                <div class="text-purple-600 mb-2"><?= Icons::comment('w-8 h-8') ?></div>
                <h3 class="font-bold text-lg">Feedbacks</h3>
                <p class="text-sm text-gray-600">View your feedback submissions</p>
            </a>
        </div>
    </div>
</div>

<?php
require_once '../app/views/layouts/employer_footer.php';
?>
