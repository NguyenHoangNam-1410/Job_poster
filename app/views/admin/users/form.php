<?php $pageTitle = isset($user) ? 'Update user' : 'Create New User'; ?>
<?php
include __DIR__ . '/../../layouts/admin_header.php';
require_once __DIR__ . '/../../../helpers/Icons.php';
?>

<?php
// Determine if we're in edit mode or create mode
$isEditMode = isset($user) && $user !== null;
$formAction = $isEditMode
    ? "/Job_poster/public/users/update/{$user->getId()}"
    : "/Job_poster/public/users/store";
$submitButtonText = $isEditMode ? 'Update' : 'Create New';
$pageHeading = $isEditMode ? "Update user #{$user->getId()}" : 'Create New User';
?>

<div class="form-background">
    <div class="form-container form-container-small">
        <div class="form-header">
            <h1 class="form-title"><?= $pageHeading ?></h1>
            <a href="/Job_poster/public/users" class="form-back-link">← Return to list</a>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="<?= $formAction ?>" enctype="multipart/form-data" class="space-y-6">
            <?php if ($isEditMode): ?>
            <!-- Profile Picture Section -->
            <div class="border-b pb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Profile Picture</h2>
                <div class="flex items-center space-x-6">
                    <div class="relative">
                        <img 
                            id="avatarPreview" 
                            src="<?= !empty($user->getAvatar()) ? htmlspecialchars($user->getAvatar()) : '/Job_poster/public/image/avatar/default.svg' ?>" 
                            alt="Avatar Preview" 
                            class="w-24 h-24 rounded-full object-cover border-2 border-gray-300"
                        >
                    </div>
                    <div class="flex-1">
                        <div class="flex gap-3 mb-2">
                            <button 
                                type="button" 
                                id="deleteAvatarBtn"
                                class="btn-cancel"
                                <?= empty($user->getAvatar()) || $user->getAvatar() === '/Job_poster/public/image/avatar/default.svg' ? 'style="display:none;"' : '' ?>
                            >
                                Delete Avatar
                            </button>
                        </div>
                        <input type="hidden" id="delete_avatar" name="delete_avatar" value="0">
                        <p class="text-sm text-gray-500 mt-2">
                            Avatar can only be removed.User must upload from their profile.
                        </p>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div class="form-grid">
                <!-- Username -->
                <div>
                    <label class="form-label">
                        Name <span class="required">*</span>
                    </label>
                    <input type="text" name="username" required
                        value="<?= $isEditMode ? htmlspecialchars($user->getUsername()) : '' ?>"
                        class="form-input" placeholder="Enter name"
                        readonly 
                        onfocus="this.removeAttribute('readonly');">
                </div>

                <!-- Email -->
                <div>
                    <label class="form-label">
                        Email <span class="required">*</span>
                    </label>
                    <input type="email" name="email" required disabled="<?= $isEditMode ? 'disabled' : '' ?>"
                        value="<?= $isEditMode ? htmlspecialchars($user->getEmail()) : '' ?>"
                        class="form-input" placeholder="example@email.com">
                </div>

                <!-- Password (only for create mode) -->
                <?php if (!$isEditMode): ?>
                    <div>
                        <label class="form-label">
                            Password <span class="required">*</span>
                        </label>
                        <input type="password" name="password" required
                            class="form-input" placeholder="Enter password">
                    </div>
                <?php endif; ?>

                <!-- Role -->
                <?php if (!$isEditMode): ?>
                    <div>
                        <label class="form-label">
                            Role <span class="required">*</span>
                        </label>
                        <select name="role" required class="form-select">
                            <option value="Admin" <?= $isEditMode && $user->getRole() === 'Admin' ? 'selected' : '' ?>>Admin</option>
                            <option value="Staff" <?= $isEditMode && $user->getRole() === 'Staff' ? 'selected' : '' ?>>Staff</option>
                        </select>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ($isEditMode && $user->getRole() === 'Employer'): ?>
            <!-- Employer Company Information Section -->
            <div class="border-b pb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Company Information</h2>
                
                <?php
                // Fetch employer data
                require_once __DIR__ . '/../../../dao/EmployerDAO.php';
                $employerDAO = new EmployderDAO();
                $employer = $employerDAO->getByUserId($user->getId());
                ?>
                
                <?php if ($employer): ?>
                <!-- Company Logo -->
                <div class="mb-6">
                    <label class="form-label">Company Logo</label>
                    <div class="flex items-center space-x-6">
                        <div class="relative">
                            <img 
                                id="logoPreview" 
                                src="<?= !empty($employer->getLogo()) ? htmlspecialchars($employer->getLogo()) : '/Job_poster/public/image/avatar/default.svg' ?>" 
                                alt="Logo Preview" 
                                class="w-24 h-24 rounded-lg object-cover border-2 border-gray-300"
                            >
                        </div>
                        <div class="flex-1">
                            <button 
                                type="button" 
                                id="deleteLogoBtn"
                                class="btn-cancel"
                                <?= empty($employer->getLogo()) || $employer->getLogo() === '/Job_poster/public/image/avatar/default.svg' ? 'style="display:none;"' : '' ?>
                            >
                                Delete Logo
                            </button>
                            <input type="hidden" id="delete_logo" name="delete_logo" value="0">
                            <p class="text-sm text-gray-500 mt-2">
                                Logo can only be removed. Employer must upload from their profile.
                            </p>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="employer_id" value="<?= $employer->getId() ?>">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Company Name -->
                    <div>
                        <label class="form-label">
                            Company Name <span class="required">*</span>
                        </label>
                        <input type="text" name="company_name" required
                            value="<?= htmlspecialchars($employer->getCompanyName()) ?>"
                            class="form-input" placeholder="Enter company name">
                    </div>

                    <!-- Website -->
                    <div>
                        <label class="form-label">Website</label>
                        <input type="url" name="website"
                            value="<?= htmlspecialchars($employer->getWebsite() ?? '') ?>"
                            class="form-input" placeholder="https://example.com">
                    </div>

                    <!-- Contact Person -->
                    <div>
                        <label class="form-label">Contact Person</label>
                        <input type="text" name="contact_person" disabled
                            value="<?= htmlspecialchars($employer->getContactPerson() ?? '') ?>"
                            class="form-input" placeholder="Enter contact person name">
                    </div>

                    <!-- Contact Phone -->
                    <div>
                        <label class="form-label">Contact Phone</label>
                        <input type="tel" name="contact_phone" disabled
                            value="<?= htmlspecialchars($employer->getContactPhone() ?? '') ?>"
                            class="form-input" placeholder="+1234567890">
                    </div>

                    <!-- Contact Email -->
                    <div class="md:col-span-2">
                        <label class="form-label">Contact Email</label>
                        <input type="email" name="contact_email" disabled
                            value="<?= htmlspecialchars($employer->getContactEmail() ?? '') ?>"
                            class="form-input" placeholder="contact@company.com">
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label class="form-label">Company Description</label>
                        <textarea name="description" rows="4"
                            class="form-input" placeholder="Enter company description..."><?= htmlspecialchars($employer->getDescription() ?? '') ?></textarea>
                    </div>
                </div>
                <?php else: ?>
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <p class="text-sm text-yellow-800">No company information found for this employer.</p>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <!-- Info Box for Edit Mode -->
            <?php if ($isEditMode): ?>
                <div class="form-info-box">
                    <?= Icons::infoCircle('form-info-icon') ?>
                    <div>
                        <p class="form-info-title">Note</p>
                        <p class="form-info-text">Password cannot be changed through this form. Guest and Employer accounts are managed separately.</p>
                    </div>
                </div>
            <?php else: ?>
                <div class="form-info-box">
                    <?= Icons::infoCircle('form-info-icon') ?>
                    <div>
                        <p class="form-info-title">Note</p>
                        <p class="form-info-text">Only Admin and Staff roles can be created here. Guest and Employer accounts are managed separately. Emails must be unique across all user types.</p>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Action Buttons -->
            <div class="form-actions">
                <a href="/Job_poster/public/users" class="btn-cancel">Cancel</a>
                <button type="submit" class="btn-submit">
                    <?= $isEditMode ? Icons::check('btn-icon') : Icons::add('btn-icon') ?>
                    <?= $submitButtonText ?>
                </button>
            </div>
        </form>
    </div>
</div>

<?php if ($isEditMode): ?>
<script>
(function() {
    const avatarPreview = document.getElementById('avatarPreview');
    const deleteAvatarBtn = document.getElementById('deleteAvatarBtn');
    const deleteAvatarInput = document.getElementById('delete_avatar');
    
    const logoPreview = document.getElementById('logoPreview');
    const deleteLogoBtn = document.getElementById('deleteLogoBtn');
    const deleteLogoInput = document.getElementById('delete_logo');

    // Handle delete avatar button
    if (deleteAvatarBtn) {
        deleteAvatarBtn.addEventListener('click', async function() {
            const confirmed = await window.confirmModal.show(
                'Are you sure you want to delete this avatar? This will reset it to the default image.',
                'Delete Avatar',
                'Delete'
            );
            if (confirmed) {
                deleteAvatarInput.value = '1';
                avatarPreview.src = '/Job_poster/public/image/avatar/default.svg';
                deleteAvatarBtn.style.display = 'none';
            }
        });
    }
    
    // Handle delete logo button
    if (deleteLogoBtn) {
        deleteLogoBtn.addEventListener('click', async function() {
            const confirmed = await window.confirmModal.show(
                'Are you sure you want to delete this company logo? This will reset it to the default image.',
                'Delete Company Logo',
                'Delete'
            );
            if (confirmed) {
                deleteLogoInput.value = '1';
                logoPreview.src = '/Job_poster/public/image/avatar/default.svg';
                deleteLogoBtn.style.display = 'none';
            }
        });
    }
})();
</script>
<?php endif; ?>

<?php include __DIR__ . '/../../layouts/admin_footer.php'; ?>