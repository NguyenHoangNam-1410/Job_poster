<?php $pageTitle = 'Edit Profile'; ?>
<?php include __DIR__ . '/../../layouts/admin_header.php'; ?>

<div class="list-container">
    <div class="list-content max-w-3xl mx-auto">
        <div class="mb-6">
            <h1 class="list-header">Edit Profile</h1>
            <p class="text-gray-600 mt-2">Update your personal information and password</p>
        </div>

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

        <div class="bg-white rounded-lg shadow-md p-6">
            <form id="profileForm" method="POST" action="/Job_poster/public/profile/update" enctype="multipart/form-data" class="space-y-6">
                <!-- Store referrer for redirect after update -->
                <input type="hidden" name="referrer" id="referrer" value="">

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
                            <label for="avatar" class="btn-submit cursor-pointer inline-block">
                                Choose Image
                            </label>
                            <input 
                                type="file" 
                                id="avatar" 
                                name="avatar" 
                                accept="image/jpeg,image/png,image/gif,image/webp"
                                class="hidden"
                            >
                            <p class="text-sm text-gray-500 mt-2">
                                Maximum file size: 1MB<br>
                                Supported formats: JPEG, PNG, GIF, WebP
                            </p>
                            <p id="fileInfo" class="text-sm text-blue-600 mt-1"></p>
                        </div>
                    </div>
                </div>

                <!-- Basic Information -->
                <div class="border-b pb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Basic Information</h2>
                    <div class="space-y-4">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Name <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                value="<?= htmlspecialchars($user->getName()) ?>"
                                class="form-input w-full"
                                required
                            >
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                value="<?= htmlspecialchars($user->getEmail()) ?>"
                                class="form-input w-full"
                                required
                            >
                        </div>

                        <!-- Role (Read-only) -->
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                                Role
                            </label>
                            <input 
                                type="text" 
                                id="role" 
                                value="<?= htmlspecialchars($user->getRole()) ?>"
                                class="form-input w-full bg-gray-100"
                                readonly
                                disabled
                            >
                        </div>
                    </div>
                </div>

                <!-- Change Password Section -->
                <div class="border-b pb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Change Password</h2>
                    <p class="text-sm text-gray-600 mb-4">Leave blank if you don't want to change your password</p>
                    <div class="space-y-4">
                        <!-- Current Password -->
                        <div>
                            <label for="old_password" class="block text-sm font-medium text-gray-700 mb-2">
                                Current Password
                            </label>
                            <input 
                                type="password" 
                                id="old_password" 
                                name="old_password" 
                                class="form-input w-full"
                                autocomplete="current-password"
                            >
                        </div>

                        <!-- New Password -->
                        <div>
                            <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">
                                New Password
                            </label>
                            <input 
                                type="password" 
                                id="new_password" 
                                name="new_password" 
                                class="form-input w-full"
                                autocomplete="new-password"
                                minlength="6"
                            >
                            <p class="text-sm text-gray-500 mt-1">Minimum 6 characters</p>
                        </div>

                        <!-- Confirm New Password -->
                        <div>
                            <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-2">
                                Confirm New Password
                            </label>
                            <input 
                                type="password" 
                                id="confirm_password" 
                                name="confirm_password" 
                                class="form-input w-full"
                                autocomplete="new-password"
                                minlength="6"
                            >
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-3 pt-4">
                    <button 
                        type="button" 
                        id="cancelBtn"
                        class="btn-cancel"
                    >
                        Cancel
                    </button>
                    <button 
                        type="submit" 
                        class="btn-submit"
                    >
                        Update Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
(function() {
    const form = document.getElementById('profileForm');
    const avatarInput = document.getElementById('avatar');
    const avatarPreview = document.getElementById('avatarPreview');
    const fileInfo = document.getElementById('fileInfo');
    const cancelBtn = document.getElementById('cancelBtn');
    const referrerInput = document.getElementById('referrer');

    // Store initial form values
    let initialFormData = new FormData(form);
    let formChanged = false;
    let originalAvatar = avatarPreview.src;
    let isSubmitting = false; // Flag to prevent beforeunload during submit

    // Get the referrer from document.referrer or default
    const referrer = document.referrer || '/Job_poster/public/profile';
    referrerInput.value = referrer;

    // Track form changes
    function checkFormChanges() {
        const currentFormData = new FormData(form);
        let hasChanges = false;

        // Check text inputs
        for (let [key, value] of currentFormData.entries()) {
            if (key !== 'avatar' && key !== 'referrer') {
                if (initialFormData.get(key) !== value) {
                    hasChanges = true;
                    break;
                }
            }
        }

        // Check if avatar changed
        if (avatarInput.files.length > 0) {
            hasChanges = true;
        }

        formChanged = hasChanges;
    }

    // Add change listeners to all inputs
    form.querySelectorAll('input:not([type="hidden"]):not([type="file"])').forEach(input => {
        input.addEventListener('input', checkFormChanges);
    });

    // Handle avatar preview and validation
    avatarInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        
        if (file) {
            // Validate file size (1MB = 1048576 bytes)
            if (file.size > 1048576) {
                alert('Image size must be less than 1MB');
                avatarInput.value = '';
                fileInfo.textContent = '';
                return;
            }

            // Validate file type
            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!allowedTypes.includes(file.type)) {
                alert('Invalid image format. Only JPEG, PNG, GIF, and WebP are allowed.');
                avatarInput.value = '';
                fileInfo.textContent = '';
                return;
            }

            // Show file info
            const sizeInKB = (file.size / 1024).toFixed(2);
            fileInfo.textContent = `Selected: ${file.name} (${sizeInKB} KB)`;

            // Preview image
            const reader = new FileReader();
            reader.onload = function(e) {
                avatarPreview.src = e.target.result;
            };
            reader.readAsDataURL(file);

            checkFormChanges();
        } else {
            fileInfo.textContent = '';
            avatarPreview.src = originalAvatar;
        }
    });

    // Validate password fields
    form.addEventListener('submit', function(e) {
        const oldPassword = document.getElementById('old_password').value;
        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = document.getElementById('confirm_password').value;

        // If any password field is filled, all must be filled
        if (oldPassword || newPassword || confirmPassword) {
            if (!oldPassword) {
                e.preventDefault();
                alert('Current password is required to change password.');
                return false;
            }
            if (!newPassword) {
                e.preventDefault();
                alert('New password is required.');
                return false;
            }
            if (!confirmPassword) {
                e.preventDefault();
                alert('Please confirm your new password.');
                return false;
            }
            if (newPassword !== confirmPassword) {
                e.preventDefault();
                alert('New passwords do not match.');
                return false;
            }
            if (newPassword.length < 6) {
                e.preventDefault();
                alert('New password must be at least 6 characters long.');
                return false;
            }
        }

        // Show confirmation if there are changes
        if (formChanged) {
            const confirmed = confirm('Are you sure you want to update your profile?');
            if (!confirmed) {
                e.preventDefault();
                return false;
            }
            // Set flag to allow form submission without beforeunload prompt
            isSubmitting = true;
        } else {
            // Set flag even if no changes to prevent prompt
            isSubmitting = true;
        }
    });

    // Handle cancel button
    cancelBtn.addEventListener('click', function() {
        if (formChanged) {
            const confirmLeave = confirm('You have unsaved changes. Are you sure you want to leave?');
            if (confirmLeave) {
                // Set flag to allow navigation
                isSubmitting = true;
                window.location.href = referrer;
            }
        } else {
            window.location.href = referrer;
        }
    });

    // Warn before leaving page with unsaved changes
    window.addEventListener('beforeunload', function(e) {
        // Only show warning if form changed AND not submitting
        if (formChanged && !isSubmitting) {
            e.preventDefault();
            e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
            return e.returnValue;
        }
    });
})();
</script>

<?php include __DIR__ . '/../../layouts/admin_footer.php'; ?>
