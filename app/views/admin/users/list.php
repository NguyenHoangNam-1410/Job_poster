<?php $pageTitle = 'Quản lý người dùng'; ?>
<?php
include __DIR__ . '/../../layouts/auth_header.php';
require_once __DIR__ . '/../../../helpers/Icons.php';
?>

<div class="list-container">
    <div class="list-content">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="list-header">User</h1>
            </div>
            <button onclick="window.formModal.loadForm('/Job_poster/public/users/create?ajax=1', 'Create New User')"
                class="btn-submit">
                <?= Icons::add('btn-icon') ?>
                Add User
            </button>
        </div>

        <!-- Search and Filter Form -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <form method="GET" action="/Job_poster/public/users" id="filterForm" class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search Users</label>
                    <input 
                        type="text" 
                        id="search" 
                        name="search" 
                        value="<?= htmlspecialchars($pagination['search'] ?? '') ?>"
                        placeholder="Search by username or email..."
                        class="form-input w-full"
                        onkeyup="debounceSearch()"
                    >
                </div>
                <div class="sm:w-40">
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                    <select id="role" name="role" class="form-select" onchange="document.getElementById('filterForm').submit()">
                        <option value="" <?= empty($pagination['role_filter']) ? 'selected' : '' ?>>All Roles</option>
                        <option value="Admin" <?= ($pagination['role_filter'] ?? '') == 'Admin' ? 'selected' : '' ?>>Admin</option>
                        <option value="Staff" <?= ($pagination['role_filter'] ?? '') == 'Staff' ? 'selected' : '' ?>>Staff</option>
                        <option value="Employer" <?= ($pagination['role_filter'] ?? '') == 'Employer' ? 'selected' : '' ?>>Employer</option>
                        <option value="Guest" <?= ($pagination['role_filter'] ?? '') == 'Guest' ? 'selected' : '' ?>>Guest</option>
                    </select>
                </div>
                <div class="sm:w-32">
                    <label for="per_page" class="block text-sm font-medium text-gray-700 mb-2">Per Page</label>
                    <select id="per_page" name="per_page" class="form-select" onchange="document.getElementById('filterForm').submit()">
                        <option value="10" <?= ($pagination['per_page'] ?? 10) == 10 ? 'selected' : '' ?>>10</option>
                        <option value="25" <?= ($pagination['per_page'] ?? 10) == 25 ? 'selected' : '' ?>>25</option>
                        <option value="50" <?= ($pagination['per_page'] ?? 10) == 50 ? 'selected' : '' ?>>50</option>
                    </select>
                </div>
                <div class="flex gap-2 items-end">
                    <a href="/Job_poster/public/users" class="btn-cancel">
                        Clear
                    </a>
                </div>
            </form>
        </div>

        <?php if (empty($users)): ?>
            <div class="list-table-wrapper">
                <div id="empty-state" class="text-center py-12">
                    <?= Icons::emptyState() ?>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No users found</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        <?php if (!empty($pagination['search'])): ?>
                            No users match your search. Try different keywords.
                        <?php else: ?>
                            Let's create a user
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        <?php else: ?>
            <div class="list-table-wrapper">
                <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="usersTable">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="table-header">ID</th>
                            <th class="table-header">User</th>
                            <th class="table-header">Email</th>
                            <th class="table-header">Role</th>
                            <th class="table-header">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="usersTableBody" class="bg-white divide-y divide-gray-200">
                        <?php foreach ($users as $user): ?>
                            <tr class="hover:bg-gray-50" id="user-row-<?= $user->getId() ?>">
                                <td class="table-cell table-cell-text">
                                    <?= $user->getId() ?>
                                </td>
                                <td class="table-cell">
                                    <div class="flex items-center gap-3">
                                        <img 
                                            src="<?= !empty($user->getAvatar()) ? htmlspecialchars($user->getAvatar()) : '/Job_poster/public/image/avatar/default.svg' ?>" 
                                            alt="Avatar" 
                                            class="w-10 h-10 rounded-full object-cover border border-gray-200"
                                        >
                                        <span><?= htmlspecialchars($user->getUsername()) ?></span>
                                    </div>
                                </td>
                                <td class="table-cell">
                                    <?= htmlspecialchars($user->getEmail()) ?>
                                </td>
                                <td class="table-cell">
                                    <span
                                        class="px-2 py-1 rounded <?php 
                                            switch($user->getRole()) {
                                                case 'Admin': echo 'bg-red-100 text-red-800'; break;
                                                case 'Staff': echo 'bg-blue-100 text-blue-800'; break;
                                                case 'Employer': echo 'bg-purple-100 text-purple-800'; break;
                                                default: echo 'bg-green-100 text-green-800';
                                            }
                                        ?>"><?= htmlspecialchars($user->getRole()) ?></span>
                                </td>
                                <td class="table-cell">
                                    <?php 
                                    $canEdit = ($user->getId() == $currentUserId) || ($user->getRole() !== 'Admin');
                                    $canDelete = ($user->getId() != $currentUserId);
                                    ?>
                                    <?php if ($canEdit): ?>
                                        <button onclick="window.formModal.loadForm('/Job_poster/public/users/edit/<?= $user->getId() ?>?ajax=1', 'Edit User #<?= $user->getId() ?>')"
                                            class="inline-flex items-center text-blue-600 hover:text-blue-900 mr-3 focus:outline-none">
                                            <?= Icons::edit('w-5 h-5 mr-1') ?>
                                            Edit
                                        </button>
                                    <?php else: ?>
                                        <span class="inline-flex items-center text-gray-400 mr-3 cursor-not-allowed" title="Cannot edit other administrators">
                                            <?= Icons::edit('w-5 h-5 mr-1') ?>
                                            Edit
                                        </span>
                                    <?php endif; ?>
                                    
                                    <?php if ($canDelete): ?>
                                        <button
                                            onclick="deleteUser(<?= $user->getId() ?>, '<?= htmlspecialchars($user->getUsername(), ENT_QUOTES) ?>')"
                                            class="inline-flex items-center text-red-600 hover:text-red-900 focus:outline-none">
                                            <?= Icons::delete('w-5 h-5 mr-1') ?>
                                            Delete
                                        </button>
                                    <?php else: ?>
                                        <span class="inline-flex items-center text-gray-400 cursor-not-allowed" title="Cannot delete yourself">
                                            <?= Icons::delete('w-5 h-5 mr-1') ?>
                                            Delete
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php 
            require_once __DIR__ . '/../../components/pagination.php';
            renderPagination(
                $pagination, 
                '/Job_poster/public/users',
                [
                    'search' => $pagination['search'] ?? '',
                    'role' => $pagination['role_filter'] ?? '',
                    'per_page' => $pagination['per_page']
                ]
            );
            ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
// Debounce function for search
let searchTimeout;
function debounceSearch() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        document.getElementById('filterForm').submit();
    }, 500); // Wait 500ms after user stops typing
}

// Restore focus to search input after page load if there was a search
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('search') && searchInput.value) {
        searchInput.focus();
        // Set cursor to end of text
        searchInput.setSelectionRange(searchInput.value.length, searchInput.value.length);
    }
});
</script>

<script src="/Job_poster/public/javascript/user.js"></script>

<?php include __DIR__ . '/../../layouts/auth_footer.php'; ?>