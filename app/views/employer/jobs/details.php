<?php
// Check if this is an AJAX request (modal view)
$isModal = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';

if (!$isModal) {
    $pageTitle = 'Job Details';
    require_once __DIR__ . '/../../layouts/auth_header.php';
}
require_once __DIR__ . '/../../../helpers/Icons.php';

$pageHeading = "{$job->getTitle()}";
$jobCategories = $job->getCategories();
$jobCategoryIds = array_column($jobCategories, 'id');
?>

<?php if (!$isModal): ?>
    <div class="form-background">
        <div class="form-container">
            <div class="form-header">
                <h1 class="form-title"><?= $pageHeading ?></h1>
                <a href="/Worknest/public/my-jobs"
                    class="inline-flex items-center bg-blue-100 text-blue-800 font-semibold text-lg px-4 py-2 rounded-lg hover:bg-blue-200 hover:text-blue-900 transition-all duration-200">
                    <?= Icons::arrowLeft('w-5 h-5 mr-2') ?> Return to List
                </a>
            </div>
        <?php else: ?>
            <h2 class="text-2xl font-bold text-gray-900 mb-6"><?= $pageHeading ?></h2>
        <?php endif; ?>

        <!-- Wrap in a form element for modal compatibility -->
        <form class="space-y-6">
            <!-- Basic Information Section -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-3 border-b border-gray-200">Basic Information</h3>
                <div class="form-grid">
                    <!-- Location -->
                    <div>
                        <label class="form-label">Location</label>
                        <div class="form-readonly"><?= htmlspecialchars($job->getLocation() ?? 'N/A') ?></div>
                    </div>

                    <!-- Salary -->
                    <div>
                        <label class="form-label">Salary (VND)</label>
                        <div class="form-readonly">
                            <?= $job->getSalary() ? number_format($job->getSalary(), 0, ',', '.') . ' VND' : '<span class="text-gray-400">Negotiable</span>' ?>
                        </div>
                    </div>

                    <!-- Deadline -->
                    <div>
                        <label class="form-label">Application Deadline</label>
                        <div class="form-readonly">
                            <?= $job->getDeadline() ? date('M j, Y H:i', strtotime($job->getDeadline())) : 'N/A' ?>
                        </div>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="form-label">Current Status</label>
                        <span class="px-2 py-1 rounded text-sm <?= $job->getStatusColor() ?>">
                            <?= ucfirst(str_replace('_', ' ', $job->getStatus())) ?>
                        </span>
                    </div>

                    <!-- Categories -->
                    <div class="md:col-span-2">
                        <label class="form-label">Job Categories</label>
                        <div class="flex flex-wrap gap-2">
                            <?php foreach ($jobCategories as $category): ?>
                                <span
                                    class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                                    <?= htmlspecialchars($category['name'] ?? 'No Name') ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Approval/Rejection Status Section -->
            <?php if ($job->getStatus() === 'approved'): ?>
                <div class="bg-green-50 rounded-lg border border-green-200 p-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0"><?= Icons::checkCircle('h-6 w-6 text-green-500') ?></div>
                        <div class="ml-4 flex-1">
                            <h3 class="font-semibold text-green-900 mb-2">Job Approved</h3>
                            <p class="text-sm text-green-800 mb-3">
                                <strong>Approved At:</strong> <?= $job->getApprovedAt() ? date('M j, Y H:i', strtotime($job->getApprovedAt())) : 'N/A' ?>
                            </p>
                            <?php if (!empty($jobReview)): ?>
                                <p class="text-sm text-green-800">
                                    <strong>Reason:</strong> <?= htmlspecialchars($jobReview) ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php elseif ($job->getStatus() === 'rejected'): ?>
                <div class="bg-red-50 rounded-lg border border-red-200 p-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0"><?= Icons::exclamationCircle('h-6 w-6 text-red-500') ?></div>
                        <div class="ml-4 flex-1">
                            <h3 class="font-semibold text-red-900 mb-2">Job Rejected</h3>
                            <p class="text-sm text-red-800 mb-3">
                                <strong>Rejected At:</strong> <?= $job->getRejectedAt() ? date('M j, Y H:i', strtotime($job->getRejectedAt())) : 'N/A' ?>
                            </p>
                            <?php if (!empty($jobReview)): ?>
                                <p class="text-sm text-red-800">
                                    <strong>Reason:</strong> <?= htmlspecialchars($jobReview) ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Job Description Section -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-3 border-b border-gray-200">Job Description</h3>
                <p class="text-gray-700 whitespace-pre-wrap"><?= htmlspecialchars($job->getDescription() ?? 'N/A') ?></p>
            </div>

            <!-- Requirements Section -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-3 border-b border-gray-200">Requirements</h3>
                <p class="text-gray-700 whitespace-pre-wrap"><?= htmlspecialchars($job->getRequirements() ?? 'N/A') ?></p>
            </div>

            <!-- Meta Information Section -->
            <div class="bg-gray-50 rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-3 border-b border-gray-200">Meta Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-600"><strong>Employer:</strong></p>
                        <p class="text-gray-900"><?= htmlspecialchars($job->getEmployerName() ?? 'N/A') ?></p>
                    </div>
                    <div>
                        <p class="text-gray-600"><strong>Posted By:</strong></p>
                        <p class="text-gray-900"><?= htmlspecialchars($job->getPostedByName() ?? 'N/A') ?></p>
                    </div>
                    <div>
                        <p class="text-gray-600"><strong>Created:</strong></p>
                        <p class="text-gray-900"><?= $job->getCreatedAt() ? date('M j, Y H:i', strtotime($job->getCreatedAt())) : 'N/A' ?></p>
                    </div>
                    <div>
                        <p class="text-gray-600"><strong>Last Updated:</strong></p>
                        <p class="text-gray-900"><?= $job->getUpdatedAt() ? date('M j, Y H:i', strtotime($job->getUpdatedAt())) : 'N/A' ?></p>
                    </div>
                </div>
            </div>

            <?php if (!$isModal): ?>
                <div class="form-actions">
                    <button type="button"
                        onclick="window.formModal.loadForm('/Worknest/public/my-jobs/edit/<?= $job->getId() ?>', 'Edit Job')"
                        class="btn-submit">
                        <?= Icons::edit('btn-icon') ?> Edit Job
                    </button>
                    <a href="/Worknest/public/my-jobs"
                        class="inline-flex items-center gap-2 px-5 py-3 font-semibold text-white bg-gradient-to-r from-gray-700 to-gray-900 rounded-lg shadow-lg hover:from-gray-800 hover:to-black transition-all duration-200">
                        <?= Icons::arrowLeft('w-5 h-5') ?> Return to List
                    </a>
                </div>
            <?php endif; ?>
        </form>
        <?php if (!$isModal): ?>
        </div>
    </div>

    <?php include __DIR__ . '/../../layouts/auth_footer.php'; ?>
<?php endif; ?>

<?php if ($isModal): ?>
    <!-- Modal Footer Buttons -->
    <div class="flex justify-end gap-3 mt-6">
        <button type="button"
            onclick="window.formModal.loadForm('/Worknest/public/my-jobs/edit/<?= $job->getId() ?>', 'Edit Job')"
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
            <?= Icons::edit('w-4 h-4') ?> Edit Job
        </button>
        <button type="button" onclick="window.formModal.close()"
            class="px-5 py-2.5 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">
            Close
        </button>
    </div>
<?php endif; ?>