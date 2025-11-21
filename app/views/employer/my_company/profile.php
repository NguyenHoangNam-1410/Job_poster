<?php
// Check if this is an AJAX request (modal view)
$isModal = (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
    || (!empty($_SERVER['HTTP_X_REQUESTED_WITH']));

if (!$isModal) {
    $pageTitle = 'Edit Company Profile';
    include __DIR__ . '/../../layouts/auth_header.php';
    ?>

    <div class="list-container">
        <div class="list-content max-w-3xl mx-auto">
            <div class="mb-6">
                <h1 class="list-header">Edit Company Profile</h1>
            </div>
        <?php } ?>

        <?php if (!$isModal): ?>
            <?php if (isset($_SESSION['error_profile'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline"><?= htmlspecialchars($_SESSION['error_profile']) ?></span>
                </div>
                <?php unset($_SESSION['error_profile']); ?>
            <?php endif; ?>
            <?php if (isset($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline"><?= htmlspecialchars($error) ?></span>
                </div>
            <?php endif; ?>

            <?php if (isset($success)): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline"><?= htmlspecialchars($success) ?></span>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="<?= !$isModal ? 'bg-white rounded-lg shadow-md p-6' : '' ?>">
            <form id="companyForm" method="POST"
                action="/Job_poster/public/company-profile/update<?= $isModal ? '?ajax=1' : '' ?>"
                enctype="multipart/form-data" class="space-y-6">
                <input type="hidden" name="referrer" id="referrer"
                    value="<?= isset($_SESSION['job_posting_flow']) ? 'job-posting' : '' ?>">
                <?php if ($isModal): ?>
                    <input type="hidden" name="ajax" value="1">
                <?php endif; ?>

                <!-- Company Logo -->
                <div class="border-b pb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Company Logo <span
                            class="text-red-500">*</span></h2>
                    <div class="flex items-center space-x-6">
                        <div class="relative">
                            <img id="logoPreview"
                                src="<?= !empty($company->getLogo()) ? htmlspecialchars($company->getLogo()) : '/Job_poster/public/image/no_image.png' ?>"
                                alt="Logo Preview" class="w-24 h-24 rounded-full object-cover border-2 border-gray-300">
                        </div>
                        <div class="flex-1">
                            <label for="logo" class="btn-submit cursor-pointer">
                                Choose Logo
                            </label>
                            <input type="file" id="logo" name="logo" accept="image/jpeg,image/png,image/gif,image/webp"
                                class="hidden">
                            <p class="text-sm text-gray-500 mt-2">
                                Maximum file size: 1MB<br>
                                Supported formats: JPEG, PNG, GIF, WebP
                            </p>
                            <p id="fileInfo" class="text-sm text-blue-600 mt-1"></p>
                        </div>
                    </div>
                </div>

                <!-- Company Information -->
                <div class="space-y-4">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Company Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name"
                            value="<?= htmlspecialchars($company->getCompanyName() ?? '') ?>"
                            placeholder="No company name available" class="form-input w-full" required>
                    </div>

                    <!-- Contact Person -->
                    <div>
                        <label for="contact" class="block text-sm font-medium text-gray-700 mb-2">
                            Contact Person <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="contact" name="contact"
                            value="<?= htmlspecialchars($company->getContactPerson() ?? '') ?>"
                            placeholder="No contact person available" class="form-input w-full" required>
                    </div>

                    <!-- Contact Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Contact Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" id="email" name="email"
                            value="<?= htmlspecialchars($company->getContactEmail() ?? '') ?>"
                            placeholder="No contact email available" class="form-input w-full" required>
                    </div>

                    <!-- Contact Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            Contact Phone <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" id="phone" name="phone"
                            value="<?= htmlspecialchars($company->getContactPhone() ?? '') ?>"
                            placeholder="No contact phone available" class="form-input w-full" required>
                    </div>

                    <!-- Website -->
                    <div>
                        <label for="website" class="block text-sm font-medium text-gray-700 mb-2">
                            Website <span class="text-red-500">*</span>
                        </label>
                        <input type="url" id="website" name="website"
                            value="<?= htmlspecialchars($company->getWebsite() ?? '') ?>"
                            placeholder="No website available" class="form-input w-full" required>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description <span class="text-red-500">*</span>
                        </label>
                        <textarea id="description" name="description" rows="4" class="form-input w-full"
                            placeholder="No company description available"
                            required><?= htmlspecialchars($company->getDescription() ?? '') ?></textarea>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-3 pt-4">
                    <?php if (!$isModal): ?>
                        <button type="button" id="cancelBtn" class="btn-cancel">
                            Cancel
                        </button>
                    <?php else: ?>
                        <button type="button" onclick="window.formModal.close()" class="btn-cancel">
                            Cancel
                        </button>
                    <?php endif; ?>
                    <button type="submit" class="btn-submit">
                        Update Company Profile
                    </button>
                </div>
            </form>
        </div>
        <?php if (!$isModal): ?>
        </div>
    </div>
<?php endif; ?>

<script>
    (function () {
        const form = document.getElementById('companyForm');
        const isModal = <?= $isModal ? 'true' : 'false' ?>;
        const logoInput = document.getElementById('logo');
        const logoPreview = document.getElementById('logoPreview');
        const fileInfo = document.getElementById('fileInfo');
        const referrerInput = document.getElementById('referrer');

        let initialFormData = new FormData(form);
        let formChanged = false;
        let originalLogo = logoPreview.src;
        let isSubmitting = false;

        if (!isModal && referrerInput) {
            const referrer = document.referrer || '/Job_poster/public/employer/company';
            referrerInput.value = referrer;
        }

        function checkFormChanges() {
            const currentFormData = new FormData(form);
            let hasChanges = false;

            for (let [key, value] of currentFormData.entries()) {
                if (key !== 'logo' && key !== 'referrer') {
                    if (initialFormData.get(key) !== value) {
                        hasChanges = true;
                        break;
                    }
                }
            }

            if (logoInput.files.length > 0) hasChanges = true;

            formChanged = hasChanges;
        }

        form.querySelectorAll('input:not([type="hidden"]):not([type="file"]), textarea').forEach(input => {
            input.addEventListener('input', checkFormChanges);
        });

        logoInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) {
                fileInfo.textContent = '';
                logoPreview.src = originalLogo;
                return;
            }

            if (file.size > 1048576) {
                alert('Image size must be less than 1MB');
                logoInput.value = '';
                fileInfo.textContent = '';
                return;
            }

            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!allowedTypes.includes(file.type)) {
                alert('Invalid image format. Only JPEG, PNG, GIF, and WebP are allowed.');
                logoInput.value = '';
                fileInfo.textContent = '';
                return;
            }

            const sizeInKB = (file.size / 1024).toFixed(2);
            fileInfo.textContent = `Selected: ${file.name} (${sizeInKB} KB)`;

            const reader = new FileReader();
            reader.onload = function (e) {
                logoPreview.src = e.target.result;
            };
            reader.readAsDataURL(file);

            checkFormChanges();
        });

        // Don't add submit listener in modal mode - form-modal.js handles it
        if (!isModal) {
            form.addEventListener('submit', function (e) {
                isSubmitting = true;
            });
        }

        if (!isModal) {
            const cancelBtn = document.getElementById('cancelBtn');
            if (cancelBtn) {
                cancelBtn.addEventListener('click', function () {
                    const referrer = document.referrer || '/Job_poster/public/employer/company';
                    if (formChanged && window.notyf) {
                        window.notyf.error('You have unsaved changes!');
                        // No blocking - just inform user
                    }
                    isSubmitting = true;
                    window.location.href = referrer;
                });
            }
        }

        window.addEventListener('beforeunload', function (e) {
            if (formChanged && !isSubmitting) {
                e.preventDefault();
                e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
                return e.returnValue;
            }
        });
    })();
</script>

<?php if (!$isModal): ?>
    <?php include __DIR__ . '/../../layouts/auth_footer.php'; ?>
<?php endif; ?>