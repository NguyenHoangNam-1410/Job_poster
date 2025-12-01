<?php $pageTitle = 'Edit Job'; ?>
<?php
require_once __DIR__ . '/../../layouts/public_header.php';
require_once __DIR__ . '/../../../helpers/Icons.php';
?>

<?php
$formAction = "/Worknest/public/jobs-manage/update/{$job->getId()}";
$pageHeading = "Edit Job #{$job->getId()} - {$job->getTitle()}";
$jobCategories = $job->getCategories();
$jobCategoryIds = array_column($jobCategories, 'id');
?>

<div class="form-background">
    <div class="form-container">
        <div class="form-header">
            <h1 class="form-title"><?= $pageHeading ?></h1>
            <a href="/Worknest/public/jobs-manage" class="form-back-link">← Return to list</a>
        </div>

        <?php if (isset($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?= htmlspecialchars($error) ?></span>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= $formAction ?>" class="space-y-6">
            <div class="form-grid">
                <!-- Job Title -->
                <div class="md:col-span-2">
                    <label class="form-label">
                        Job Title <span class="required">*</span>
                    </label>
                    <input type="text" name="title" required value="<?= htmlspecialchars($job->getTitle()) ?>"
                        class="form-input" placeholder="e.g., Senior Backend Developer">
                </div>

                <!-- Location -->
                <div>
                    <label class="form-label">
                        Location
                    </label>
                    <input type="text" name="location" value="<?= htmlspecialchars($job->getLocation() ?? '') ?>"
                        class="form-input" placeholder="e.g., Ho Chi Minh City, Remote">
                </div>

                <!-- Salary -->
                <div>
                    <label class="form-label">
                        Salary (VND)
                    </label>
                    <input type="number" name="salary" step="0.01"
                        value="<?= htmlspecialchars($job->getSalary() ?? '') ?>" class="form-input"
                        placeholder="e.g., 25000000">
                </div>

                <!-- Deadline -->
                <div>
                    <label class="form-label">
                        Application Deadline
                    </label>
                    <input type="datetime-local" name="deadline"
                        value="<?= $job->getDeadline() ? date('Y-m-d', strtotime($job->getDeadline())) . 'T' . date('H:i', strtotime($job->getDeadline())) : '' ?>"
                        class="form-input">
                </div>

                <!-- Status (Read-only display) -->
                <div>
                    <label class="form-label">
                        Current Status
                    </label>
                    <div class="px-3 py-2 bg-gray-50 border border-gray-300 rounded-md">
                        <span class="px-2 py-1 rounded text-sm <?= $job->getStatusColor() ?>">
                            <?php
                            $status = $job->getStatus();
                            echo $status === 'soft_deleted' ? 'Deleted' : ucfirst(str_replace('_', ' ', $status));
                            ?>
                        </span>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Use the status dropdown in the list view to change job status
                    </p>
                </div>

                <!-- Categories -->
                <div class="md:col-span-2">
                    <label class="form-label">
                        Job Categories
                    </label>
                    <select name="categories[]" id="jobCategories" multiple class="form-input w-full"
                        style="visibility: hidden; height: 42px;">
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category->getId() ?>" <?= in_array($category->getId(), $jobCategoryIds) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($category->getCategoryName()) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label class="form-label">
                        Job Description
                    </label>
                    <textarea name="description" rows="6" class="form-input"
                        placeholder="Detailed job description..."><?= htmlspecialchars($job->getDescription() ?? '') ?></textarea>
                </div>

                <!-- Requirements -->
                <div class="md:col-span-2">
                    <label class="form-label">
                        Requirements
                    </label>
                    <textarea name="requirements" rows="6" class="form-input"
                        placeholder="Job requirements and qualifications..."><?= htmlspecialchars($job->getRequirements() ?? '') ?></textarea>
                </div>
            </div>

            <!-- Info Box -->
            <div class="form-info-box">
                <?= Icons::infoCircle('form-info-icon') ?>
                <div>
                    <p class="form-info-title">Job Information</p>
                    <p class="form-info-text">
                        <strong>Employer:</strong> <?= htmlspecialchars($job->getEmployerName() ?? 'N/A') ?><br>
                        <strong>Posted By:</strong> <?= htmlspecialchars($job->getPostedByName() ?? 'N/A') ?><br>
                        <strong>Created:</strong>
                        <?= $job->getCreatedAt() ? date('d m, Y H:i', strtotime($job->getCreatedAt())) : 'N/A' ?><br>
                        <strong>Last Updated:</strong>
                        <?= $job->getUpdatedAt() ? date('d m, Y H:i', strtotime($job->getUpdatedAt())) : 'N/A' ?>
                    </p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="form-actions">
                <a href="/Worknest/public/jobs-manage" class="btn-cancel">Cancel</a>
                <button type="submit" class="btn-submit">
                    <?= Icons::check('btn-icon') ?>
                    Update Job
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    (function () {
        // Initialize Choices.js for categories multi-select
        function initCategorySelect() {
            const categorySelect = document.getElementById('jobCategories');

            if (!categorySelect) {
                console.log('Category select not found');
                return;
            }

            console.log('Initializing Choices.js');

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

            // Show the enhanced select
            categorySelect.style.visibility = 'visible';

            console.log('Choices.js initialized successfully');
        }

        // Initialize when DOM is ready or immediately if already loaded
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initCategorySelect);
        } else {
            setTimeout(initCategorySelect, 100);
        }
    })();
</script>

<?php
include __DIR__ . '/../../layouts/public_footer.php';
?>