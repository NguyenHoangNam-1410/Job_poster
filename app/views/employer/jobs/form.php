<?php 
$pageTitle = 'Edit Job'; 
require_once __DIR__ . '/../../layouts/public_header.php';
require_once __DIR__ . '/../../../helpers/Icons.php';

$pageHeading = "{$job->getTitle()}";
$jobCategories = $job->getCategories();
$jobCategoryIds = array_column($jobCategories, 'id');

$status = $job->getStatus(); 
$role = $_SESSION['user']['role'];

$canEditInfo = ($role === 'Employer' && in_array($status, ['draft', 'pending', 'rejected']));
$showDelete = false;
$deleteType = '';

// Employer delete logic
if ($role === 'Employer') {
    if ($status === 'draft') {
        $showDelete = true;
        $deleteType = 'hard';
    } elseif (in_array($status, ['pending', 'approved', 'rejected', 'overdue'])) {
        $showDelete = true;
        $deleteType = 'soft';
    }
}

$inputStateClass = $canEditInfo
    ? 'bg-white focus:ring-blue-500 focus:border-blue-500' 
    : 'bg-gray-100 text-gray-500 cursor-not-allowed border-gray-200';

$readonlyAttr = $canEditInfo ? '' : 'disabled';

$statusButton = null;
if ($role === 'Employer') {
    if ($status === 'draft') {
        $statusButton = [
            'action' => 'post_job',
            'label' => 'Post Job',
            'class' => 'bg-blue-600 hover:bg-blue-700 text-sm text-white px-3 py-1',
            'icon' => Icons::send('w-4 h-4 inline-block align-middle')
        ];
    } elseif ($status === 'approved') {
        $statusButton = [
            'action' => 'mark_overdue',
            'label' => 'Mark as Overdue',
            'class' => 'bg-yellow-500 hover:bg-yellow-600 text-sm px-3 py-1',
            'icon' => Icons::clock('w-4 h-4 inline-block align-middle')
        ];
    } elseif ($status === 'overdue') {
        $statusButton = [
            'action' => 'reapprove',
            'label' => 'Re-approve',
            'class' => 'bg-green-600 hover:bg-green-700 text-sm text-white px-3 py-1',
            'icon' => Icons::check('w-4 h-4 inline-block align-middle')
        ];
    } elseif ($status === 'rejected') {
        $statusButton = [
            'action' => 'resubmit',
            'label' => 'Resubmit for Approval',
            'class' => 'bg-blue-600 hover:bg-blue-700 text-sm text-white px-3 py-1',
            'icon' => Icons::refresh('w-4 h-4 inline-block align-middle')
        ];
    }
}
?>

<div class="form-background">
    <div class="form-container">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6 flex-wrap">
        <div class="flex flex-col flex-1 min-w-0">
            <h1 class="text-4xl font-extrabold text-left truncate">
                <?= htmlspecialchars($pageHeading) ?>
            </h1>

            <div class="flex items-center gap-3 mt-2 flex-wrap">
                <span class="text-sm text-gray-500">Current Status:</span>
                <span class="px-2.5 py-0.5 rounded-full text-sm font-medium <?= $job->getStatusColor() ?>" id="currentStatusLabel">
                    <?= ucfirst(str_replace('_', ' ', $status)) ?>
                </span>
                <?php if ($statusButton): ?>
                <button type="button" id="statusChangeBtn"
                    data-action="<?= $statusButton['action'] ?>"
                    class="flex rounded-md items-center gap-1 <?= $statusButton['class'] ?>">
                    <span><?= $statusButton['icon'] ?></span>
                    <span><?= $statusButton['label'] ?></span>
                </button>
                <?php endif; ?>
            </div>

        </div>

        <div class="flex-shrink-0 mt-2 md:mt-0">
            <a href="/Job_poster/public/my-jobs"
            class="inline-flex items-center bg-gray-200 text-gray-800 font-semibold px-4 py-2 rounded-lg hover:bg-gray-300 transition-all duration-200 whitespace-nowrap">
            <?= Icons::arrowLeft('w-4 h-4 mr-2') ?> Return to List
            </a>
        </div>

    </div>

    <!-- Rejected Alert -->
    <?php if ($status === 'rejected'): ?>
    <div class="mb-6 p-4 rounded-md bg-red-50 border border-red-200">
        <div class="flex">
            <div class="flex-shrink-0"><?= Icons::exclamationCircle('h-5 w-5 text-red-400') ?></div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">Submission Rejected</h3>
                <p class="mt-1 text-sm text-red-700">
                    <strong>Reason:</strong> <?= htmlspecialchars($jobReview ?? 'No reason provided.') ?>
                </p>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Editing Locked -->
    <?php if (!$canEditInfo): ?>
    <div class="mb-6 p-4 rounded-md bg-yellow-50 border border-yellow-200">
        <div class="flex">
            <div class="flex-shrink-0"><?= Icons::infoCircle('h-5 w-5 text-yellow-400') ?></div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-yellow-800">Editing Locked</h3>
                <p class="mt-1 text-sm text-yellow-700">
                    You cannot modify job details while status is <strong><?= $status ?></strong>.
                </p>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- CHANGE WARNING -->
    <div id="changeWarning" class="hidden mb-5 p-4 bg-yellow-100 border-2 border-yellow-500 rounded-md text-yellow-900 font-semibold shadow">
        <span class="block">⚠️ Changes detected. The following will be updated when you press Save:</span>
        <ul id="changedFieldsList" class="list-disc list-inside mt-2"></ul>
    </div>

    <!-- FORM -->
    <form method="POST" action="/Job_poster/public/my-jobs/update/<?= $job->getId() ?>" id="jobForm" class="space-y-8">
        <input type="hidden" name="status" id="newStatusInput" value="">

        <div class="form-grid">

            <div class="md:col-span-2">
                <label class="form-label">Job Title <span class="required">*</span></label>
                <input type="text" name="title" <?= $readonlyAttr ?> required
                    data-field-name="Job Title"
                    value="<?= htmlspecialchars($job->getTitle()) ?>"
                    class="form-input <?= $inputStateClass ?>">
            </div>

            <!-- LOCATION -->
            <div>
                <label class="form-label">Location</label>
                <input type="text" name="location" <?= $readonlyAttr ?>
                    data-field-name="Location"
                    value="<?= htmlspecialchars($job->getLocation()) ?>"
                    class="form-input <?= $inputStateClass ?>">
            </div>

            <!-- SALARY -->
            <div>
                <label class="form-label">Salary (VND)</label>
                <input type="number" name="salary" step="0.01" <?= $readonlyAttr ?>
                    data-field-name="Salary"
                    value="<?= htmlspecialchars($job->getSalary()) ?>"
                    class="form-input <?= $inputStateClass ?>">
            </div>

            <!-- DEADLINE -->
            <div>
                <label class="form-label">Application Deadline</label>
                <input type="datetime-local" name="deadline" <?= $readonlyAttr ?>
                    data-field-name="Deadline"
                    value="<?= $job->getDeadline() ? date('Y-m-d\TH:i', strtotime($job->getDeadline())) : '' ?>"
                    class="form-input <?= $inputStateClass ?>">
            </div>

            <!-- CATEGORIES -->
            <div class="md:col-span-2">
                <label class="form-label">Job Categories</label>
                <div class="flex flex-wrap gap-2 p-3 border rounded-md bg-gray-50">
                    <?php foreach ($categories as $category): ?>
                    <label class="inline-flex items-center gap-2 <?= $canEditInfo ? 'cursor-pointer' : 'cursor-not-allowed' ?>">
                        <input type="checkbox" name="categories[]" value="<?= $category->getId() ?>"
                            data-field-name="Categories"
                            <?= $readonlyAttr ?>
                            class="rounded text-blue-600 border-gray-300"
                            <?= in_array($category->getId(), $jobCategoryIds) ? 'checked' : '' ?>>
                        <span class="text-sm"><?= htmlspecialchars($category->getCategoryName()) ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- DESCRIPTION -->
            <div class="md:col-span-2">
                <label class="form-label">Job Description</label>
                <textarea name="description" rows="6" <?= $readonlyAttr ?>
                        data-field-name="Description"
                        class="form-input <?= $inputStateClass ?>"><?= htmlspecialchars($job->getDescription()) ?></textarea>
            </div>

            <!-- REQUIREMENTS -->
            <div class="md:col-span-2">
                <label class="form-label">Requirements</label>
                <textarea name="requirements" rows="6" <?= $readonlyAttr ?>
                        data-field-name="Requirements"
                        class="form-input <?= $inputStateClass ?>"><?= htmlspecialchars($job->getRequirements()) ?></textarea>
            </div>
        </div>

        <div class="flex justify-between items-center pt-6 border-t">

            <!-- SAVE BUTTON -->
            <button type="submit" name="action" value="save_changes"
                class="flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg font-semibold shadow-md hover:scale-105 hover:from-blue-600 hover:to-blue-700 active:scale-95 transition-all duration-200">
                <?= Icons::save('w-5 h-5 inline-block align-middle') ?>
                <span>Save Changes</span>
            </button>
            <?php if ($showDelete): ?>
                <a href="/Job_poster/public/my-jobs/<?= $deleteType ?>-delete/<?= $job->getId() ?>?type=<?= $deleteType ?>"
                    onclick="return confirmDelete('<?= $deleteType ?>');"
                    class="flex items-center gap-2 px-6 py-3 bg-red-600 text-white rounded-lg font-semibold shadow-md hover:scale-105 hover:bg-red-700 active:scale-95 transition-all duration-200">
                    <?= Icons::delete('w-5 h-5 inline-block align-middle') ?>
                    <span>Delete Job</span>
                </a>
            <?php endif; ?>
            <!-- CANCEL -->
            <a href="/Job_poster/public/my-jobs/details/<?= $job->getId() ?>" id="cancelBtn"
                class="px-4 py-2 rounded-lg font-semibold bg-gray-300 hover:bg-gray-400 transition">
                Cancel
            </a>
        </div>

    </form>
    </div>
</div>

<?php
$additionalJS = [
    '/Job_poster/public/javascript/employer_jobs.js'
];
include __DIR__ . '/../../layouts/public_footer.php'; ?>
