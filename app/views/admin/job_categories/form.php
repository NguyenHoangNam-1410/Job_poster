<?php $pageTitle = isset($category) ? 'Update Job Category' : 'Create New Job Category'; ?>
<?php
include __DIR__ . '/../../layouts/auth_header.php';
require_once __DIR__ . '/../../../helpers/Icons.php';
?>

<?php
// Determine if we're in edit mode or create mode
$isEditMode = isset($category) && $category !== null;
$formAction = $isEditMode
    ? "/Job_poster/public/job-categories/update/{$category->getId()}"
    : "/Job_poster/public/job-categories/store";
$submitButtonText = $isEditMode ? 'Update' : 'Create';
$pageHeading = $isEditMode ? "Update Category #{$category->getId()}" : 'Create New Job Category';
?>

<div class="form-background">
    <div class="form-container form-container-small">
        <div class="form-header">
            <h1 class="form-title"><?= $pageHeading ?></h1>
            <a href="/Job_poster/public/job-categories" class="form-back-link">← Return to list</a>
        </div>

        <?php if (isset($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?= htmlspecialchars($error) ?></span>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= $formAction ?>" class="space-y-6">
            <div class="form-grid">
                <!-- Category Name -->
                <div>
                    <label class="form-label">
                        Category Name <span class="required">*</span>
                    </label>
                    <input type="text" name="category_name" required
                        value="<?= $isEditMode ? htmlspecialchars($category->getCategoryName()) : '' ?>"
                        class="form-input" placeholder="e.g., Software Engineering, Marketing, Design" minlength="2"
                        maxlength="255">
                    <p class="mt-1 text-sm text-gray-500">Category name must be unique and at least 2 characters long.
                    </p>
                </div>
            </div>

            <!-- Info Box -->
            <div class="form-info-box">
                <?= Icons::infoCircle('form-info-icon') ?>
                <div>
                    <p class="form-info-title">Note</p>
                    <p class="form-info-text">
                        <?php if ($isEditMode): ?>
                            This category name must be unique. If this category is assigned to jobs, you cannot delete it
                            until those assignments are removed.
                        <?php else: ?>
                            Create categories that will be used to organize and filter job postings. Category names must be
                            unique across the system.
                        <?php endif; ?>
                    </p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="form-actions">
                <a href="/Job_poster/public/job-categories" class="btn-cancel">Cancel</a>
                <button type="submit" class="btn-submit">
                    <?= $isEditMode ? Icons::check('btn-icon') : Icons::add('btn-icon') ?>
                    <?= $submitButtonText ?>
                </button>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/auth_footer.php'; ?>