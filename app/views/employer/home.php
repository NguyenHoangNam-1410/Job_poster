<?php
require_once '../app/views/layouts/auth_header.php';

// Fetch recent jobs
require_once __DIR__ . '/../../dao/JobDAO.php';
require_once __DIR__ . '/../../dao/EmployerDAO.php';
$jobDAO = new JobDAO();
$employerDAO = new EmployerDAO();

// Get the employer ID from the user ID
$userId = $_SESSION['user']['id'];
$employer = $employerDAO->getEmployerByUserId($userId);
$employerId = $employer ? $employer->getId() : null;

// If no employer profile exists, redirect to create one
if (!$employerId) {
    header('Location: /Job_poster/public/company-profile');
    exit;
}

// Get most recent approved job
$approvedJobs = $jobDAO->getJobsByEmployer($employerId, '', '', '', 'approved', '', '', 1, 0);
$recentApproved = !empty($approvedJobs) ? $approvedJobs[0] : null;

// Get most recent rejected job
$rejectedJobs = $jobDAO->getJobsByEmployer($employerId, '', '', '', 'rejected', '', '', 1, 0);
$recentRejected = !empty($rejectedJobs) ? $rejectedJobs[0] : null;

// Get most recent pending job
$pendingJobs = $jobDAO->getJobsByEmployer($employerId, '', '', '', 'pending', '', '', 1, 0);
$recentPending = !empty($pendingJobs) ? $pendingJobs[0] : null;

$hasAnyJobs = $recentApproved || $recentRejected || $recentPending;
?>

<div class="list-container">
    <div class="list-content">
        <!-- Welcome Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">Hi,
                <?= htmlspecialchars($_SESSION['user']['name'] ?? 'Employer'); ?>!</h1>
            <p class="text-gray-600">Here's your job posting overview</p>
        </div>

        <?php if (!$hasAnyJobs): ?>
            <!-- No Jobs - Show Post New Job -->
            <div class="bg-white rounded-lg shadow-lg p-12 text-center">
                <div class="text-gray-400 mb-6">
                    <svg class="w-24 h-24 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">No Job Postings Yet</h2>
                <p class="text-gray-600 mb-6">Start by creating your first job posting to attract talented candidates</p>
                <button onclick="window.formModal.loadForm('/Job_poster/public/my-jobs/create', 'Create New Job')"
                    class="inline-flex items-center px-6 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Post New Job
                </button>
            </div>
        <?php else: ?>
            <!-- Recent Jobs Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Recent Approved Job -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-green-500 to-green-600 p-4">
                        <h3 class="text-white font-bold text-lg flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Recently Approved
                        </h3>
                    </div>
                    <?php if ($recentApproved): ?>
                        <button
                            onclick="window.formModal.loadForm('/Job_poster/public/my-jobs/show/<?= $recentApproved->getId() ?>', 'Job Details')"
                            class="block p-6 hover:bg-gray-50 transition text-left w-full">
                            <h4 class="font-bold text-xl text-gray-800 mb-2">
                                <?= htmlspecialchars($recentApproved->getTitle()) ?></h4>
                            <p class="text-sm text-gray-600 mb-2">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <?= htmlspecialchars($recentApproved->getLocation()) ?>
                            </p>
                            <p class="text-sm text-green-600 font-semibold mb-3">
                                Deadline: <?= date('M d, Y', strtotime($recentApproved->getDeadline())) ?>
                            </p>
                            <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                Approved
                            </span>
                        </button>
                    <?php else: ?>
                        <div class="p-6 text-center text-gray-500">
                            <p class="mb-4">No approved jobs yet</p>
                            <button onclick="window.formModal.loadForm('/Job_poster/public/my-jobs/create', 'Create New Job')"
                                class="text-green-600 hover:text-green-700 font-semibold text-sm">
                                Post a Job →
                            </button>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Recent Rejected Job -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-red-500 to-red-600 p-4">
                        <h3 class="text-white font-bold text-lg flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Recently Rejected
                        </h3>
                    </div>
                    <?php if ($recentRejected): ?>
                        <button
                            onclick="window.formModal.loadForm('/Job_poster/public/my-jobs/show/<?= $recentRejected->getId() ?>', 'Job Details')"
                            class="block p-6 hover:bg-gray-50 transition text-left w-full">
                            <h4 class="font-bold text-xl text-gray-800 mb-2">
                                <?= htmlspecialchars($recentRejected->getTitle()) ?></h4>
                            <p class="text-sm text-gray-600 mb-2">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <?= htmlspecialchars($recentRejected->getLocation()) ?>
                            </p>
                            <p class="text-sm text-gray-600 mb-3">
                                Deadline: <?= date('M d, Y', strtotime($recentRejected->getDeadline())) ?>
                            </p>
                            <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                Rejected
                            </span>
                        </button>
                    <?php else: ?>
                        <div class="p-6 text-center text-gray-500">
                            <p class="mb-4">No rejected jobs</p>
                            <a href="/Job_poster/public/my-jobs" class="text-red-600 hover:text-red-700 font-semibold text-sm">
                                View All Jobs →
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Recent Pending Job -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 p-4">
                        <h3 class="text-white font-bold text-lg flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Recently Pending
                        </h3>
                    </div>
                    <?php if ($recentPending): ?>
                        <button
                            onclick="window.formModal.loadForm('/Job_poster/public/my-jobs/show/<?= $recentPending->getId() ?>', 'Job Details')"
                            class="block p-6 hover:bg-gray-50 transition text-left w-full">
                            <h4 class="font-bold text-xl text-gray-800 mb-2"><?= htmlspecialchars($recentPending->getTitle()) ?>
                            </h4>
                            <p class="text-sm text-gray-600 mb-2">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <?= htmlspecialchars($recentPending->getLocation()) ?>
                            </p>
                            <p class="text-sm text-gray-600 mb-3">
                                Deadline: <?= date('M d, Y', strtotime($recentPending->getDeadline())) ?>
                            </p>
                            <span
                                class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Pending Review
                            </span>
                        </button>
                    <?php else: ?>
                        <div class="p-6 text-center text-gray-500">
                            <p class="mb-4">No pending jobs</p>
                            <button onclick="window.formModal.loadForm('/Job_poster/public/my-jobs/create', 'Create New Job')"
                                class="text-yellow-600 hover:text-yellow-700 font-semibold text-sm">
                                Post a Job →
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- View All Jobs Button -->
            <div class="mt-8 text-center">
                <a href="/Job_poster/public/my-jobs"
                    class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    View All My Jobs
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
require_once '../app/views/layouts/auth_footer.php';
?>