<?php $pageTitle = 'Job Details'; ?>
<?php 
require_once __DIR__ . '/../../layouts/public_header.php';
require_once __DIR__ . '/../../../helpers/Icons.php';
?>

<?php
$pageHeading = "{$job->getTitle()}";
$jobCategories = $job->getCategories();
$jobCategoryIds = array_column($jobCategories, 'id');
?>

<div class="form-background">
    <div class="form-container">
        <div class="form-header">
            <h1 class="form-title"><?= $pageHeading ?></h1>
            <a href="/Job_poster/public/my-jobs" 
            class="inline-flex items-center bg-blue-100 text-blue-800 font-semibold text-lg px-4 py-2 rounded-lg hover:bg-blue-200 hover:text-blue-900 transition-all duration-200">
                <?= Icons::arrowLeft('w-5 h-5 mr-2') ?> Return to List
            </a>
        </div>

        <div class="space-y-6">
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

                <!-- Approved At -->
                <?php if ($job->getStatus() === 'approved'): ?>
                    <div>
                        <label class="form-label">Approved At</label>
                        <div class="form-readonly">
                            <?= $job->getApprovedAt() ? date('M j, Y H:i', strtotime($job->getApprovedAt())) : 'N/A' ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Rejected At -->
                <?php if ($job->getStatus() === 'rejected'): ?>
                    <div>
                        <label class="form-label">Rejected At</label>
                        <div class="form-readonly">
                            <?= $job->getRejectedAt() ? date('M j, Y H:i', strtotime($job->getRejectedAt())) : 'N/A' ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Approval Reason -->
                <?php if ($job->getStatus() === 'approved' && !empty($jobReview)): ?>
                    <div class="mb-6 p-4 rounded-md bg-green-50 border border-green-200">
                        <div class="flex">
                            <div class="flex-shrink-0"><?= Icons::checkCircle('h-5 w-5 text-green-400') ?></div>
                            <div class="ml-3">
                                <p class="mt-1 text-sm text-green-700">
                                    <strong>Reason:</strong> <?= htmlspecialchars($jobReview ?? 'No reason provided.') ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Rejection Reason -->
                <?php if ($job->getStatus() === 'rejected' && !empty($jobReview)): ?>
                    <div class="mb-6 p-4 rounded-md bg-red-50 border border-red-200">
                        <div class="flex">
                            <div class="flex-shrink-0"><?= Icons::exclamationCircle('h-5 w-5 text-red-400') ?></div>
                            <div class="ml-3">
                                <p class="mt-1 text-sm text-red-700">
                                    <strong>Reason:</strong> <?= htmlspecialchars($jobReview ?? 'No reason provided.') ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Categories -->
                <div class="md:col-span-2">
                    <label class="form-label">Job Categories</label>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach ($jobCategories as $category): ?>
                            <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                                <?= htmlspecialchars($category['name'] ?? 'No Name') ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label class="form-label">Job Description</label>
                    <p class="form-readonly"><?= nl2br(htmlspecialchars($job->getDescription() ?? 'N/A')) ?></p>
                </div>

                <!-- Requirements -->
                <div class="md:col-span-2">
                    <label class="form-label">Requirements</label>
                    <p class="form-readonly"><?= nl2br(htmlspecialchars($job->getRequirements() ?? 'N/A')) ?></p>
                </div>
            </div>

            <!-- Info Box -->
            <div class="form-info-box">
                <?= Icons::infoCircle('form-info-icon') ?>
                <div>
                    <p class="form-info-title">Meta Information</p>
                    <p class="form-info-text">
                        <strong>Employer:</strong> <?= htmlspecialchars($job->getEmployerName() ?? 'N/A') ?><br>
                        <strong>Posted By:</strong> <?= htmlspecialchars($job->getPostedByName() ?? 'N/A') ?><br>
                        <strong>Created:</strong> <?= $job->getCreatedAt() ? date('Y-m-d H:i', strtotime($job->getCreatedAt())) : 'N/A' ?><br>
                        <strong>Last Updated:</strong> <?= $job->getUpdatedAt() ? date('Y-m-d H:i', strtotime($job->getUpdatedAt())) : 'N/A' ?>
                    </p>
                </div>
            </div>

            <div class="form-actions">
                <a href="/Job_poster/public/my-jobs/edit/<?= $job->getId() ?>" class="btn-submit">
                    <?= Icons::edit('btn-icon') ?> Edit Job
                </a>
                <a href="/Job_poster/public/my-jobs"
                class="inline-flex items-center gap-2 px-5 py-3 font-semibold text-white bg-gradient-to-r from-gray-700 to-gray-900 rounded-lg shadow-lg hover:from-gray-800 hover:to-black transition-all duration-200">
                    <?= Icons::arrowLeft('w-5 h-5') ?> Return to List
                </a>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/public_footer.php'; ?>
