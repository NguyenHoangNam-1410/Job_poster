<?php
$pageTitle = 'Edit Job';

// Detect if this is being loaded in a modal
$isModal = (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
    || (!empty($_SERVER['HTTP_X_REQUESTED_WITH']));

if (!$isModal) {
    require_once __DIR__ . '/../../layouts/public_header.php';
}
require_once __DIR__ . '/../../../helpers/Icons.php';

$pageHeading = "{$job->getTitle()}";
$jobCategories = $job->getCategories();
$jobCategoryIds = array_column($jobCategories, 'id');

$status = $job->getStatus();
$role = $_SESSION['user']['role'] ?? 'Guest';

// Only draft and rejected can be edited (approved/overdue can only change status, not info)
$canEditInfo = ($role === 'Employer' && in_array($status, ['draft', 'rejected']));
$showDelete = false;
$deleteType = '';

// Employer delete logic
if ($role === 'Employer') {
    if (in_array($status, ['draft', 'pending'])) {
        $showDelete = true;
        $deleteType = 'hard';
    } elseif (in_array($status, ['approved', 'rejected', 'overdue'])) {
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
    if ($status === 'approved') {
        $statusButton = [
            'action' => 'mark_overdue',
            'label' => 'Close',
            'class' => 'bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg font-semibold',
            'icon' => Icons::clock('w-4 h-4 inline-block align-middle')
        ];
    } elseif ($status === 'overdue') {
        $statusButton = [
            'action' => 'reapprove',
            'label' => 'Open',
            'class' => 'bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold',
            'icon' => Icons::check('w-4 h-4 inline-block align-middle')
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
                    <span class="px-2.5 py-0.5 rounded-full text-sm font-medium <?= $job->getStatusColor() ?>"
                        id="currentStatusLabel">
                        <?= ucfirst(str_replace('_', ' ', $status)) ?>
                    </span>
                </div>

            </div>

            <div class="flex-shrink-0 mt-2 md:mt-0">
                <a href="/Worknest/public/my-jobs"
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

        <!-- Approved Notice -->
        <?php if ($status === 'approved' && !empty($jobReview)): ?>
            <div class="mb-6 p-4 rounded-md bg-green-50 border border-green-200">
                <div class="flex">
                    <div class="flex-shrink-0"><?= Icons::check('h-5 w-5 text-green-400') ?></div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-green-800">Job Approved</h3>
                        <p class="mt-1 text-sm text-green-700">
                            <strong>Review Note:</strong> <?= htmlspecialchars($jobReview) ?>
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
        <div id="changeWarning"
            class="hidden mb-5 p-4 bg-yellow-100 border-2 border-yellow-500 rounded-md text-yellow-900 font-semibold shadow">
            <span class="block">⚠️ Changes detected. The following will be updated when you press Save:</span>
            <ul id="changedFieldsList" class="list-disc list-inside mt-2"></ul>
        </div>

        <!-- FORM -->
        <form method="POST"
            action="/Worknest/public/my-jobs/update/<?= $job->getId() ?><?= $isModal ? '?ajax=1' : '' ?>" id="jobForm"
            class="space-y-8">
            <?php if ($isModal): ?>
                <input type="hidden" name="ajax" value="1">
            <?php endif; ?>
            <input type="hidden" name="status" id="newStatusInput" value="">

            <div class="form-grid">

                <div class="md:col-span-2">
                    <label class="form-label">Job Title <span class="required">*</span></label>
                    <input type="text" name="title" <?= $readonlyAttr ?> required data-field-name="Job Title"
                        value="<?= htmlspecialchars($job->getTitle()) ?>" class="form-input <?= $inputStateClass ?>">
                </div>

                <!-- LOCATION -->
                <div>
                    <label class="form-label">Location</label>
                    <input type="text" name="location" <?= $readonlyAttr ?> data-field-name="Location"
                        value="<?= htmlspecialchars($job->getLocation()) ?>" class="form-input <?= $inputStateClass ?>">
                </div>

                <!-- SALARY -->
                <div>
                    <label class="form-label">Salary (VND)</label>
                    <input type="number" name="salary" step="0.01" <?= $readonlyAttr ?> data-field-name="Salary"
                        value="<?= htmlspecialchars($job->getSalary()) ?>" class="form-input <?= $inputStateClass ?>">
                </div>

                <!-- DEADLINE -->
                <div>
                    <label class="form-label">Application Deadline</label>
                    <input type="datetime-local" name="deadline" <?= $readonlyAttr ?> data-field-name="Deadline"
                        value="<?= $job->getDeadline() ? date('Y-m-d\TH:i', strtotime($job->getDeadline())) : '' ?>"
                        class="form-input <?= $inputStateClass ?>">
                </div>

                <!-- CATEGORIES -->
                <div class="md:col-span-2">
                    <label class="form-label">Job Categories</label>
                    <select name="categories[]" id="jobCategories" multiple data-field-name="Categories"
                        <?= $readonlyAttr ?> class="form-input w-full" style="visibility: hidden; height: 42px;">
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category->getId() ?>" <?= in_array($category->getId(), $jobCategoryIds) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($category->getCategoryName()) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- DESCRIPTION -->
                <div class="md:col-span-2">
                    <label class="form-label">Job Description</label>
                    <textarea name="description" rows="6" <?= $readonlyAttr ?> data-field-name="Description"
                        class="form-input <?= $inputStateClass ?>"><?= htmlspecialchars($job->getDescription()) ?></textarea>
                </div>

                <!-- REQUIREMENTS -->
                <div class="md:col-span-2">
                    <label class="form-label">Requirements</label>
                    <textarea name="requirements" rows="6" <?= $readonlyAttr ?> data-field-name="Requirements"
                        class="form-input <?= $inputStateClass ?>"><?= htmlspecialchars($job->getRequirements()) ?></textarea>
                </div>
            </div>

            <div class="flex justify-between items-center pt-6 border-t">
                <div class="flex gap-3">
                    <?php if ($status === 'draft' && ($canEditInfo || $isModal)): ?>
                        <!-- SAVE AS DRAFT (Only for draft status) -->
                        <button type="submit" name="action" value="save_draft" id="saveDraftBtn"
                            class="flex items-center gap-2 px-6 py-3 bg-gray-500 text-white rounded-lg font-semibold shadow-md hover:scale-105 hover:bg-gray-600 active:scale-95 transition-all duration-200">
                            <?= Icons::save('w-5 h-5 inline-block align-middle') ?>
                            <span>Save as Draft</span>
                        </button>
                        <!-- POST JOB (Only for draft status) -->
                        <button type="submit" name="action" value="post_job" id="postJobBtn"
                            class="flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg font-semibold shadow-md hover:scale-105 hover:from-blue-600 hover:to-blue-700 active:scale-95 transition-all duration-200">
                            <?= Icons::send('w-5 h-5 inline-block align-middle') ?>
                            <span>Post Job</span>
                        </button>
                    <?php elseif ($status === 'rejected' && ($canEditInfo || $isModal)): ?>
                        <!-- RESUBMIT (For rejected status) -->
                        <button type="submit" name="action" value="post_job" id="postJobBtn"
                            class="flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg font-semibold shadow-md hover:scale-105 hover:from-blue-600 hover:to-blue-700 active:scale-95 transition-all duration-200">
                            <?= Icons::refresh('w-5 h-5 inline-block align-middle') ?>
                            <span>Resubmit</span>
                        </button>
                    <?php endif; ?>
                </div>

                <div class="flex gap-3">
                    <?php if ($statusButton): ?>
                        <!-- CLOSE/OPEN BUTTON -->
                        <button type="button" id="statusChangeBtn" data-action="<?= $statusButton['action'] ?>"
                            class="flex items-center gap-2 <?= $statusButton['class'] ?>">
                            <span><?= $statusButton['icon'] ?></span>
                            <span><?= $statusButton['label'] ?></span>
                        </button>
                    <?php endif; ?>

                    <!-- CANCEL -->
                    <a href="/Worknest/public/my-jobs" id="cancelBtn"
                        class="px-4 py-2 rounded-lg font-semibold bg-gray-300 hover:bg-gray-400 transition">
                        Cancel
                    </a>
                </div>
            </div>

        </form>
    </div>
</div>

<?php
$additionalJS = [
    '/Worknest/public/javascript/employer_jobs.js'
];
?>

<script>
    (function () {
        // Initialize Choices.js for categories multi-select
        function initCategorySelect() {
            const categorySelect = document.getElementById('jobCategories');

            if (!categorySelect) return;

            const choices = new Choices(categorySelect, {
                removeItemButton: true,
                searchEnabled: true,
                searchChoices: true,
                searchPlaceholderValue: 'Search categories...',
                placeholder: true,
                placeholderValue: 'Select categories',
                itemSelectText: 'Click to select',
                noResultsText: 'No categories found',
                noChoicesText: 'No categories available',
                maxItemCount: -1
            });

            categorySelect.style.visibility = 'visible';
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initCategorySelect);
        } else {
            setTimeout(initCategorySelect, 100);
        }
    })();

    // Handle form submission - works for both modal and regular page
    (function () {
        const form = document.getElementById('jobForm');
        if (!form) return;

        // Check if we're in a modal
        const hasAjaxInput = form.querySelector('input[name="ajax"]') !== null;
        const hasModalOverlay = document.querySelector('.modal-overlay') !== null;
        const hasModalContent = form.closest('.modal-content') !== null;
        const hasModalBackdrop = document.querySelector('.modal-backdrop') !== null;
        const formActionHasAjax = form.getAttribute('action').includes('ajax=1');

        const isInModal = hasAjaxInput || hasModalOverlay || hasModalContent || hasModalBackdrop || formActionHasAjax;

        // If in modal, attach click handlers directly to the buttons by ID
        if (isInModal) {
            // Wait a bit for DOM to be fully ready
            setTimeout(() => {
                const saveDraftBtn = document.getElementById('saveDraftBtn');
                const postJobBtn = document.getElementById('postJobBtn');

                const buttons = [saveDraftBtn, postJobBtn].filter(btn => btn !== null);
                if (buttons.length === 0) return;

                buttons.forEach(button => {

                    button.addEventListener('click', async function (e) {
                        e.preventDefault();
                        e.stopPropagation();
                        e.stopImmediatePropagation();

                        const action = button.value;

                        // Validation for categories
                        const categorySelect = document.getElementById('jobCategories');
                        if (categorySelect) {
                            const selectedOptions = Array.from(categorySelect.selectedOptions);
                            if (selectedOptions.length === 0) {
                                if (window.notyf) {
                                    window.notyf.error('Please select at least one job category.');
                                } else {
                                    alert('Please select at least one job category.');
                                }
                                return;
                            }
                        }

                        // Show loading state
                        button.disabled = true;
                        const originalText = button.innerHTML;
                        button.innerHTML = '<svg class="animate-spin h-5 w-5 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Processing...';

                        try {
                            const formData = new FormData(form);
                            // Make sure to include the action from the button
                            formData.set('action', action);
                            const formAction = form.getAttribute('action');

                            const response = await fetch(formAction, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            });

                            const responseText = await response.text();
                            let data;

                            try {
                                data = JSON.parse(responseText);
                            } catch (e) {
                                throw new Error('Server error: Invalid response format');
                            }

                            if (data.success) {
                                if (window.formModal) {
                                    window.formModal.close();
                                }

                                if (window.notyf) {
                                    window.notyf.success(data.message);
                                }

                                setTimeout(() => {
                                    window.location.reload();
                                }, 500);
                            } else {
                                throw new Error(data.message || 'Failed to update job');
                            }
                        } catch (error) {
                            if (window.notyf) {
                                window.notyf.error(error.message || 'Failed to update job. Please try again.');
                            } else {
                                alert(error.message || 'Failed to update job. Please try again.');
                            }

                            // Restore button state
                            button.disabled = false;
                            button.innerHTML = originalText;
                        }
                    }, true); // Use capture phase
                });
            }, 100); // Wait 100ms for DOM
        }
    })();

    // Handle status change button (Close Job / Open Job)
    (function () {
        const statusBtn = document.getElementById('statusChangeBtn');
        if (!statusBtn) return;

        statusBtn.addEventListener('click', async function () {
            const action = this.getAttribute('data-action');
            const jobId = <?= $job->getId() ?>;

            let confirmMessage = '';
            let successMessage = '';

            if (action === 'mark_overdue') {
                confirmMessage = 'Are you sure you want to close this job? It will be marked as overdue.';
                successMessage = 'Job closed successfully';
            } else if (action === 'reapprove') {
                confirmMessage = 'Are you sure you want to reopen this job? It will be marked as recruiting.';
                successMessage = 'Job reopened successfully';
            }

            const confirmed = await window.confirmModal.show('', confirmMessage, 'Confirm', 'Cancel');
            if (!confirmed) return;

            // Show loading state
            statusBtn.disabled = true;
            const originalHTML = statusBtn.innerHTML;
            statusBtn.innerHTML = '<svg class="animate-spin h-4 w-4 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Processing...';

            try {
                const response = await fetch(`/Worknest/public/my-jobs/status/${jobId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ action: action })
                });

                const data = await response.json();

                if (data.success) {
                    if (window.notyf) {
                        window.notyf.success(data.message || successMessage);
                    }
                    setTimeout(() => window.location.reload(), 500);
                } else {
                    throw new Error(data.message || 'Failed to update job status');
                }
            } catch (error) {
                if (window.notyf) {
                    window.notyf.error(error.message || 'Failed to update job status. Please try again.');
                } else {
                    alert(error.message || 'Failed to update job status. Please try again.');
                }
                statusBtn.disabled = false;
                statusBtn.innerHTML = originalHTML;
            }
        });
    })();
</script>

<?php
if (!$isModal) {
    include __DIR__ . '/../../layouts/auth_footer.php';
}
