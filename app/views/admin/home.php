<?php 
require_once '../app/views/layouts/admin_header.php';
?>

<div class="list-container">
    <div class="list-content">
        <!-- Welcome Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">Hi, <?= htmlspecialchars($_SESSION['user']['name'] ?? 'Admin'); ?>!</h1>
            <p class="text-gray-600">Welcome to your Admin Dashboard</p>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <a href="/Job_poster/public/statistics" class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                <div class="text-blue-600 mb-2"><?= Icons::statistic('w-8 h-8') ?></div>
                <h3 class="font-bold text-lg">Statistics</h3>
                <p class="text-sm text-gray-600">View system statistics</p>
            </a>
            
            <a href="/Job_poster/public/users" class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                <div class="text-green-600 mb-2"><?= Icons::users('w-8 h-8') ?></div>
                <h3 class="font-bold text-lg">Users</h3>
                <p class="text-sm text-gray-600">Manage system users</p>
            </a>
            
            <a href="/Job_poster/public/jobs-manage" class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                <div class="text-purple-600 mb-2"><?= Icons::briefcase('w-8 h-8') ?></div>
                <h3 class="font-bold text-lg">Jobs</h3>
                <p class="text-sm text-gray-600">Manage job listings</p>
            </a>
            
            <a href="/Job_poster/public/approvals" class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                <div class="text-yellow-600 mb-2"><?= Icons::checklist('w-8 h-8') ?></div>
                <h3 class="font-bold text-lg">Approvals</h3>
                <p class="text-sm text-gray-600">Review job requests</p>
            </a>
        </div>
    </div>
</div>

<?php
require_once '../app/views/layouts/admin_footer.php';
?>
