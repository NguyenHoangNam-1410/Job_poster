<?php
// Check if this is an AJAX request (modal view)
$isModal = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';

if (!$isModal) {
    $pageTitle = 'Post New Job';
    require_once __DIR__ . '/../../layouts/public_header.php';
}
require_once __DIR__ . '/../../../helpers/Icons.php';
?>

<?php if (!$isModal): ?>
    <div class="form-background">
        <div class="form-container form-container-small">
            <!-- Header -->
            <div class="form-header">
                <h1 class="form-title">Post New Job</h1>
            </div>
        <?php endif; ?> <!-- FORM -->
        <form method="POST" action="/Worknest/public/my-jobs/store<?= $isModal ? '?ajax=1' : '' ?>" id="jobForm"
            >
            <?php if ($isModal): ?>
                <input type="hidden" name="ajax" value="1">
            <?php endif; ?>
            <div class="form-grid">

                <!-- TITLE -->
                <div>
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
                    <input type="number" name="salary" step="0.01" min="0"
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
                    <select name="categories[]" id="jobCategoriesNew" multiple required class="form-input w-full"
                        style="visibility: hidden; height: 42px;">
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category->getId() ?>">
                                <?= htmlspecialchars($category->getCategoryName()) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
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
                <?php if (!$isModal): ?>
                    <a href="/Worknest/public/my-jobs" id="cancelBtn"
                        class="px-4 py-2 rounded-lg font-semibold bg-gray-300 hover:bg-gray-400 transition">
                        Cancel
                    </a>
                <?php else: ?>
                    <button type="button" onclick="window.formModal.close()" id="cancelBtn"
                        class="px-4 py-2 rounded-lg font-semibold bg-gray-300 hover:bg-gray-400 transition">
                        Cancel
                    </button>
                <?php endif; ?>
            </div>

        </form>
        <?php if (!$isModal): ?>
        </div>
    </div>
<?php endif; ?>

<script>
    (function () {
        // Initialize Choices.js for categories multi-select
        function initCategorySelect() {
            const categorySelect = document.getElementById('jobCategoriesNew');

            if (!categorySelect) {
                console.log('Category select not found');
                return;
            }

            // Check if Choices is available
            if (typeof Choices === 'undefined') {
                console.error('Choices.js is not loaded');
                categorySelect.style.visibility = 'visible';
                return;
            }

            console.log('Initializing Choices.js with', categorySelect.options.length, 'options');

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
        }

        // Initialize when DOM is ready or immediately if already loaded
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initCategorySelect);
        } else {
            setTimeout(initCategorySelect, 100);
        }
    })();
</script>

<?php if (!$isModal): ?>
    <?php
    $additionalJS = [
        '/Worknest/public/javascript/employer_jobs.js'
    ];
    include __DIR__ . '/../../layouts/public_footer.php';
?>
<?php endif; ?>