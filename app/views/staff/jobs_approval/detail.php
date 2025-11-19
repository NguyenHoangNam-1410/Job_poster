<?php $pageTitle = 'Job Detail - ' . $job->getTitle(); ?>
<?php 
require_once '../app/views/layouts/public_header.php';
require_once __DIR__ . '/../../../helpers/Icons.php';
?>

<div class="form-background">
    <div class="form-container max-w-5xl">
        <div class="form-header">
            <h1 class="form-title">Job Details - Review & Approval</h1>
            <a href="/Job_poster/public/approvals" class="form-back-link">← Back to Approval List</a>
        </div>

        <?php if (isset($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?= htmlspecialchars($error) ?></span>
            </div>
        <?php endif; ?>

        <!-- Job Information Card -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="border-b border-gray-200 pb-4 mb-4">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900"><?= htmlspecialchars($job->getTitle()) ?></h2>
                        <div class="mt-2 flex items-center gap-4">
                            <span class="px-3 py-1 rounded text-sm font-medium <?= $job->getStatusColor() ?>">
                                <?php 
                                    $status = $job->getStatus();
                                    echo $status === 'soft_deleted' ? 'Deleted' : ucfirst(str_replace('_', ' ', $status));
                                ?>
                            </span>
                            <span class="text-gray-500 text-sm">
                                Job ID: #<?= $job->getId() ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Basic Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Company Information</h3>
                    <div class="space-y-2">
                        <p class="text-gray-900">
                            <span class="font-medium">Company Name:</span> 
                            <?= htmlspecialchars($job->getCompanyName() ?? 'N/A') ?>
                        </p>
                        <p class="text-gray-900">
                            <span class="font-medium">Employer Name:</span> 
                            <?= htmlspecialchars($job->getEmployerName() ?? 'N/A') ?>
                        </p>
                        <p class="text-gray-900">
                            <span class="font-medium">Posted By:</span> 
                            <?= htmlspecialchars($job->getPostedByName() ?? 'N/A') ?>
                        </p>
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Job Details</h3>
                    <div class="space-y-2">
                        <p class="text-gray-900">
                            <span class="font-medium">Location:</span> 
                            <?= htmlspecialchars($job->getLocation() ?? 'N/A') ?>
                        </p>
                        <p class="text-gray-900">
                            <span class="font-medium">Salary:</span> 
                            <?php if ($job->getSalary()): ?>
                                <?= number_format($job->getSalary(), 0, ',', '.') ?> VND
                            <?php else: ?>
                                <span class="text-gray-400">Negotiable</span>
                            <?php endif; ?>
                        </p>
                        <p class="text-gray-900">
                            <span class="font-medium">Application Deadline:</span> 
                            <?php if ($job->getDeadline()): ?>
                                <?= date('Y-m-d H:i', strtotime($job->getDeadline())) ?>
                            <?php else: ?>
                                <span class="text-gray-400">N/A</span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Timestamps -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <p class="text-gray-900">
                    <span class="font-medium">Created At:</span> 
                    <?= date('Y-m-d H:i:s', strtotime($job->getCreatedAt())) ?>
                </p>
                <p class="text-gray-900">
                    <span class="font-medium">Last Updated:</span> 
                    <?= date('Y-m-d H:i:s', strtotime($job->getUpdatedAt())) ?>
                </p>
            </div>

            <!-- Categories -->
            <?php 
            $categories = $job->getCategories();
            if (!empty($categories)): 
            ?>
            <div class="mb-6">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Job Categories</h3>
                <div class="flex flex-wrap gap-2">
                    <?php foreach ($categories as $cat): ?>
                        <span class="px-3 py-1 text-sm rounded bg-blue-50 text-blue-700 border border-blue-200">
                            <?= htmlspecialchars($cat['name']) ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Job Description -->
            <?php if ($job->getDescription()): ?>
            <div class="mb-6">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Job Description</h3>
                <div class="prose max-w-none text-gray-900 bg-gray-50 p-4 rounded-md">
                    <?= nl2br(htmlspecialchars($job->getDescription())) ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Job Requirements -->
            <?php if ($job->getRequirements()): ?>
            <div class="mb-6">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Job Requirements</h3>
                <div class="prose max-w-none text-gray-900 bg-gray-50 p-4 rounded-md">
                    <?= nl2br(htmlspecialchars($job->getRequirements())) ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Previous Review (if rejected) -->
            <?php if (!empty($previousReview)): ?>
            <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-md">
                <h3 class="text-sm font-medium text-yellow-800 mb-2">Previous Review</h3>
                <div class="text-sm text-yellow-900">
                    <p><span class="font-medium">Action:</span> <?= ucfirst($previousReview['action']) ?></p>
                    <p><span class="font-medium">Reviewed By:</span> <?= htmlspecialchars($previousReview['reviewer_name']) ?></p>
                    <p><span class="font-medium">Date:</span> <?= date('Y-m-d H:i:s', strtotime($previousReview['created_at'])) ?></p>
                    <?php if ($previousReview['reason']): ?>
                        <p class="mt-2"><span class="font-medium">Reason:</span></p>
                        <p class="mt-1 p-2 bg-white rounded"><?= nl2br(htmlspecialchars($previousReview['reason'])) ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Action Buttons -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Review Actions</h3>
            
            <div class="flex gap-4">
                <!-- Approve Button -->
                <button 
                    onclick="showApproveConfirmation()"
                    class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-md transition">
                    <?= Icons::check('w-5 h-5 mr-2') ?>
                    Approve Job
                </button>

                <!-- Reject Button -->
                <button 
                    onclick="showRejectForm()"
                    class="inline-flex items-center px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-md transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Reject Job
                </button>

                <!-- Back Button -->
                <a href="/Job_poster/public/approvals"
                    class="inline-flex items-center px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-md transition">
                    Back to List
                </a>
            </div>

            <!-- Reject Form (Hidden by default) -->
            <div id="rejectForm" class="hidden mt-6 p-4 bg-red-50 border border-red-200 rounded-md">
                <h4 class="font-medium text-red-900 mb-3">Rejection Reason</h4>
                <form id="rejectionForm" method="POST" action="/Job_poster/public/approvals/reject/<?= $job->getId() ?>">
                    <textarea 
                        name="reason" 
                        rows="4" 
                        required
                        placeholder="Please provide a reason for rejecting this job posting..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                    ></textarea>
                    <div class="mt-3 flex gap-2">
                        <button 
                            type="submit"
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-md">
                            Confirm Rejection
                        </button>
                        <button 
                            type="button"
                            onclick="hideRejectForm()"
                            class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium rounded-md">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>

            <!-- Approve Form (Hidden by default) -->
            <div id="approveConfirmation" class="hidden mt-6 p-4 bg-green-50 border border-green-200 rounded-md">
                <h4 class="font-medium text-green-900 mb-3">Approval Reason/Notes</h4>
                <form method="POST" action="/Job_poster/public/approvals/approve/<?= $job->getId() ?>">
                    <textarea 
                        name="reason" 
                        rows="4" 
                        placeholder="Optional: Add approval notes or comments..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    ></textarea>
                    <div class="mt-3 flex gap-2">
                        <button 
                            type="submit"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-md">
                            Confirm Approval
                        </button>
                        <button 
                            type="button"
                            onclick="hideApproveConfirmation()"
                            class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium rounded-md">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function showRejectForm() {
    document.getElementById('rejectForm').classList.remove('hidden');
    document.getElementById('approveConfirmation').classList.add('hidden');
}

function hideRejectForm() {
    document.getElementById('rejectForm').classList.add('hidden');
}

function showApproveConfirmation() {
    document.getElementById('approveConfirmation').classList.remove('hidden');
    document.getElementById('rejectForm').classList.add('hidden');
}

function hideApproveConfirmation() {
    document.getElementById('approveConfirmation').classList.add('hidden');
}
</script>

<?php 
include __DIR__ . '/../../layouts/public_footer.php';
?>
