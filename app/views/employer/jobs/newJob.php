<?php 
$pageTitle = 'Post New Job'; 
require_once __DIR__ . '/../../layouts/public_header.php';
require_once __DIR__ . '/../../../helpers/Icons.php';
?>

<div class="form-background">
    <div class="form-container">

    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-4xl font-extrabold text-left">Post New Job</h1>
    </div>

    <!-- FORM -->
    <form method="POST" action="/Job_poster/public/my-jobs/store" id="jobForm" class="space-y-8" onsubmit="return confirmJobAction(event)">
        <div class="form-grid">

            <!-- TITLE -->
            <div class="md:col-span-2">
                <label class="form-label">Job Title <span class="required">*</span></label>
                <input type="text" name="title" required
                    class="form-input bg-white focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- LOCATION -->
            <div>
                <label class="form-label">Location <span class="required">*</span></label>
                <input type="text" name="location" required
                    class="form-input bg-white focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- SALARY -->
            <div>
                <label class="form-label">Salary (VND)</label>
                <input type="number" name="salary" step="0.01"
                    class="form-input bg-white focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- DEADLINE -->
            <div>
                <label class="form-label">Application Deadline <span class="required">*</span></label>
                <input type="datetime-local" name="deadline" required
                    class="form-input bg-white focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- CATEGORIES -->
            <div class="md:col-span-2">
                <label class="form-label">Job Categories <span class="required">*</span></label>
                <div class="flex flex-wrap gap-2 p-3 border rounded-md bg-gray-50">
                    <?php foreach ($categories as $category): ?>
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="categories[]" value="<?= $category->getId() ?>"
                            class="rounded text-blue-600 border-gray-300">
                        <span class="text-sm"><?= htmlspecialchars($category->getCategoryName()) ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- DESCRIPTION -->
            <div class="md:col-span-2">
                <label class="form-label">Job Description <span class="required">*</span></label>
                <textarea name="description" rows="6" required
                        class="form-input bg-white focus:ring-blue-500 focus:border-blue-500"></textarea>
            </div>

            <!-- REQUIREMENTS -->
            <div class="md:col-span-2">
                <label class="form-label">Requirements <span class="required">*</span></label>
                <textarea name="requirements" rows="6" required
                        class="form-input bg-white focus:ring-blue-500 focus:border-blue-500"></textarea>
            </div>
        </div>

        <div class="flex justify-between items-center pt-6 border-t">
            <!-- Save Draft -->
            <button type="submit" name="action" value="save_draft"
                class="flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-gray-400 to-gray-500 text-white rounded-lg font-semibold shadow-md hover:scale-105 hover:from-gray-500 hover:to-gray-600 active:scale-95 transition-all duration-200">
                <?= Icons::save('w-5 h-5 inline-block align-middle') ?>
                <span>Save Draft</span>
            </button>

            <!-- Post Job -->
            <button type="submit" name="action" value="post_job"
                class="flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg font-semibold shadow-md hover:scale-105 hover:from-green-600 hover:to-green-700 active:scale-95 transition-all duration-200">
                <?= Icons::send('w-5 h-5 inline-block align-middle') ?>
                <span>Post Job</span>
            </button>

            <!-- Cancel -->
            <a href="/Job_poster/public/my-jobs" id="cancelBtn"
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
include __DIR__ . '/../../layouts/public_footer.php'; 
?>
