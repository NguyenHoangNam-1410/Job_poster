<?php $pageTitle = 'Edit Profile'; ?>
<?php 
// Load header based on user role
if(isset($_SESSION['user']) && $_SESSION['user']['role'] == 'Admin') {
    include __DIR__ . '/../../layouts/admin_header.php';
} else {
    include __DIR__ . '/../../layouts/staff_header.php';
}
?>

<div class="list-container">
    <div class="list-content max-w-3xl mx-auto">
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
                            <div class="flex gap-3 mb-2">
                                <label for="avatar" class="btn-submit cursor-pointer inline-block">
                                    Choose Image
                                </label>
                                <button 
                                    type="button" 
                                    id="deleteAvatarBtn"
                                    class="btn-cancel"
                                    <?= empty($user->getAvatar()) || $user->getAvatar() === '/Job_poster/public/image/avatar/default.svg' ? 'style="display:none;"' : '' ?>
                                >
                                    Delete Avatar
                                </button>
                            </div>
                            <input 
                                type="file" 
                                id="avatar" 
                                name="avatar" 
                                accept="image/jpeg,image/png,image/gif,image/webp"
                                class="hidden"
                            >
                            <input type="hidden" id="delete_avatar" name="delete_avatar" value="0">
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
                    <p class="text-sm text-gray-600 mb-4">All three fields must be filled to change password.</p>
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
                                autocomplete="new-password"
                                readonly 
                                onfocus="this.removeAttribute('readonly');"
                                placeholder="Enter current password"
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
                                readonly 
                                onfocus="this.removeAttribute('readonly');"
                                minlength="8"
                                placeholder="Enter new password"
                            >
                            <ul class="text-sm text-gray-600 mt-2 space-y-1">
                                <li>• At least 8 characters</li>
                                <li>• Include a number</li>
                                <li>• Include uppercase and lowercase letters</li>
                                <li>• Include a special character</li>
                            </ul>
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
                                readonly 
                                onfocus="this.removeAttribute('readonly');"
                                minlength="8"
                                placeholder="Confirm new password"
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
    const deleteAvatarBtn = document.getElementById('deleteAvatarBtn');
    const deleteAvatarInput = document.getElementById('delete_avatar');

    // Clear password fields on page load to prevent browser autofill
    document.getElementById('old_password').value = '';
    document.getElementById('new_password').value = '';
    document.getElementById('confirm_password').value = '';

    // Store initial form values (excluding password fields) after a slight delay
    let initialName = '';
    let initialEmail = '';
    let formChanged = false;
    let originalAvatar = avatarPreview.src;
    let isSubmitting = false;

    // Get the referrer from document.referrer or default
    const referrer = document.referrer || '/Job_poster/public/profile';
    referrerInput.value = referrer;

    // Initialize values after DOM is fully loaded
    setTimeout(function() {
        initialName = document.getElementById('name').value;
        initialEmail = document.getElementById('email').value;
    }, 100);

    // Track form changes
    function checkFormChanges() {
        let hasChanges = false;

        // Check if basic information changed
        const currentName = document.getElementById('name').value;
        const currentEmail = document.getElementById('email').value;
        
        if (currentName !== initialName || currentEmail !== initialEmail) {
            hasChanges = true;
        }

        // Check if avatar file selected
        if (avatarInput.files.length > 0) {
            hasChanges = true;
        }

        // Check if avatar deletion requested
        if (deleteAvatarInput.value === '1') {
            hasChanges = true;
        }

        // Check if any password fields are filled
        const oldPassword = document.getElementById('old_password').value;
        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        
        if (oldPassword.trim() || newPassword.trim() || confirmPassword.trim()) {
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
                deleteAvatarBtn.style.display = 'inline-block';
                deleteAvatarInput.value = '0'; // Reset delete flag
            };
            reader.readAsDataURL(file);

            checkFormChanges();
        } else {
            fileInfo.textContent = '';
            if (deleteAvatarInput.value !== '1') {
                avatarPreview.src = originalAvatar;
            }
        }
    });

    // Password validation function
    function validatePassword(password) {
        const minLength = password.length >= 8;
        const hasNumber = /\d/.test(password);
        const hasUpper = /[A-Z]/.test(password);
        const hasLower = /[a-z]/.test(password);
        const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);
        
        return {
            valid: minLength && hasNumber && hasUpper && hasLower && hasSpecial,
            minLength,
            hasNumber,
            hasUpper,
            hasLower,
            hasSpecial
        };
    }

    // Handle delete avatar button
    deleteAvatarBtn.addEventListener('click', function() {
        const confirmed = confirm('Are you sure you want to delete your avatar? This will reset it to the default image.');
        if (confirmed) {
            deleteAvatarInput.value = '1';
            avatarPreview.src = '/Job_poster/public/image/avatar/default.svg';
            deleteAvatarBtn.style.display = 'none';
            avatarInput.value = '';
            fileInfo.textContent = '';
            checkFormChanges();
        }
    });

    // Validate password fields
    form.addEventListener('submit', function(e) {
        const oldPassword = document.getElementById('old_password').value;
        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = document.getElementById('confirm_password').value;

        // Count how many password fields are filled
        const passwordFieldsFilled = [oldPassword, newPassword, confirmPassword].filter(p => p.trim()).length;

        // If any password field is filled, all three must be filled
        if (passwordFieldsFilled > 0) {
            if (passwordFieldsFilled < 3) {
                e.preventDefault();
                alert('To change password, all three password fields must be filled (Current Password, New Password, and Confirm New Password).');
                return false;
            }

            // Validate new passwords match
            if (newPassword !== confirmPassword) {
                e.preventDefault();
                alert('New passwords do not match.');
                return false;
            }
            
            // Validate password requirements
            const validation = validatePassword(newPassword);
            if (!validation.valid) {
                e.preventDefault();
                let errorMsg = 'Password must meet the following requirements:\n';
                if (!validation.minLength) errorMsg += '• At least 8 characters\n';
                if (!validation.hasNumber) errorMsg += '• Include a number\n';
                if (!validation.hasUpper || !validation.hasLower) errorMsg += '• Include uppercase and lowercase letters\n';
                if (!validation.hasSpecial) errorMsg += '• Include a special character\n';
                alert(errorMsg);
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

<?php 
// Load footer based on user role
if(isset($_SESSION['user']) && $_SESSION['user']['role'] == 'Admin') {
    include __DIR__ . '/../../layouts/admin_footer.php';
} else {
    include __DIR__ . '/../../layouts/staff_footer.php';
}
?>
