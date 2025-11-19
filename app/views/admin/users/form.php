<?php $pageTitle = isset($user) ? 'Update user' : 'Create New User'; ?>
<?php
include __DIR__ . '/../../layouts/public_header.php';
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

        <form method="POST" action="<?= $formAction ?>" class="space-y-6">
            <div class="form-grid">
                <!-- Username -->
                <div>
                    <label class="form-label">
                        Name <span class="required">*</span>
                    </label>
                    <input type="text" name="username" required
                        value="<?= $isEditMode ? htmlspecialchars($user->getUsername()) : '' ?>"
                        class="form-input" placeholder="Enter name">
                </div>

                <!-- Email -->
                <div>
                    <label class="form-label">
                        Email <span class="required">*</span>
                    </label>
                    <input type="email" name="email" required
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

<?php
include __DIR__ . '/../../layouts/public_footer.php';
?>