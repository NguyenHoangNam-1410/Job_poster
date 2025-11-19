<?php $pageTitle = 'Job Categories Management'; ?>
<?php
include __DIR__ . '/../../layouts/admin_header.php';
require_once __DIR__ . '/../../../helpers/Icons.php';
?>

<div class="list-container">
    <div class="list-content">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="list-header">Job Categories</h1>
            </div>
            <button onclick="window.formModal.loadForm('/Job_poster/public/job-categories/create?ajax=1', 'Create New Category')"
                class="relative inline-flex items-center gap-2 px-6 py-3 text-white font-bold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 group overflow-hidden"
                style="background: linear-gradient(270deg, #020918ff, #070442ff, #595cb4ff, #201d5eff, #011c57ff); background-size: 400% 400%; animation: gradientWave 3s ease infinite;">
                <span class="flex items-center justify-center w-5 h-5 rounded-full bg-white/20 group-hover:bg-white/30 transition-colors relative z-10">
                    <?= Icons::add('w-3 h-3') ?>
                </span>
                <span class="font-semibold relative z-10">Add Category</span>
                <span class="absolute inset-0 rounded-lg bg-white opacity-0 group-hover:opacity-10 transition-opacity"></span>
            </button>
            <style>
                @keyframes gradientWave {
                    0% { background-position: 0% 50%; }
                    50% { background-position: 100% 50%; }
                    100% { background-position: 0% 50%; }
                }
            </style>
        </div>

        <!-- Search and Filter Form -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <form method="GET" action="/Job_poster/public/job-categories" id="categoryFilterForm" class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search Categories</label>
                    <input 
                        type="text" 
                        id="search" 
                        name="search" 
                        value="<?= htmlspecialchars($pagination['search'] ?? '') ?>"
                        placeholder="Search by category name..."
                        class="form-input w-full"
                        onkeyup="debounceCategorySearch()"
                    >
                </div>
                <div class="sm:w-32">
                    <label for="per_page" class="block text-sm font-medium text-gray-700 mb-2">Per Page</label>
                    <select id="per_page" name="per_page" class="form-select" onchange="document.getElementById('categoryFilterForm').submit()">
                        <option value="10" <?= ($pagination['per_page'] ?? 10) == 10 ? 'selected' : '' ?>>10</option>
                        <option value="25" <?= ($pagination['per_page'] ?? 10) == 25 ? 'selected' : '' ?>>25</option>
                        <option value="50" <?= ($pagination['per_page'] ?? 10) == 50 ? 'selected' : '' ?>>50</option>
                    </select>
                </div>
                <div class="flex gap-2 items-end">
                    <a href="/Job_poster/public/job-categories" class="btn-cancel">
                        Clear
                    </a>
                </div>
            </form>
        </div>

        <?php if (isset($_GET['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?= htmlspecialchars($_GET['error']) ?></span>
            </div>
        <?php endif; ?>

        <?php if (empty($categoriesWithCounts)): ?>
            <div class="list-table-wrapper">
                <div id="empty-state" class="text-center py-12">
                    <?= Icons::emptyState() ?>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No categories found</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        <?php if (!empty($pagination['search'])): ?>
                            No categories match your search. Try different keywords.
                        <?php else: ?>
                            Get started by creating a new job category.
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        <?php else: ?>
            <div class="list-table-wrapper">
                <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="categoriesTable">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="table-header">ID</th>
                            <th class="table-header">Category Name</th>
                            <th class="table-header">Jobs Count</th>
                            <th class="table-header">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="categoriesTableBody" class="bg-white divide-y divide-gray-200">
                        <?php foreach ($categoriesWithCounts as $item): ?>
                            <?php 
                                $category = $item['category']; 
                                $jobCount = $item['job_count'];
                                $canDelete = $item['can_delete'];
                            ?>
                            <tr class="hover:bg-gray-50" id="category-row-<?= $category->getId() ?>">
                                <td class="table-cell table-cell-text">
                                    <?= $category->getId() ?>
                                </td>
                                <td class="table-cell font-medium">
                                    <?= htmlspecialchars($category->getCategoryName()) ?>
                                </td>
                                <td class="table-cell">
                                    <?php if ($jobCount > 0): ?>
                                        <span class="px-2 py-1 rounded bg-blue-100 text-blue-800 text-sm">
                                            <?= $jobCount ?> job<?= $jobCount != 1 ? 's' : '' ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-gray-400 text-sm">No jobs</span>
                                    <?php endif; ?>
                                </td>
                                <td class="table-cell">
                                    <button onclick="window.formModal.loadForm('/Job_poster/public/job-categories/edit/<?= $category->getId() ?>?ajax=1', 'Edit Category #<?= $category->getId() ?>')"
                                        class="inline-flex items-center text-blue-600 hover:text-blue-900 mr-3 focus:outline-none">
                                        <?= Icons::edit('w-5 h-5 mr-1') ?>
                                        Edit
                                    </button>
                                    
                                    <?php if ($canDelete): ?>
                                        <button
                                            onclick="deleteCategory(<?= $category->getId() ?>, '<?= htmlspecialchars($category->getCategoryName(), ENT_QUOTES) ?>')"
                                            class="inline-flex items-center text-red-600 hover:text-red-900 focus:outline-none">
                                            <?= Icons::delete('w-5 h-5 mr-1') ?>
                                            Delete
                                        </button>
                                    <?php else: ?>
                                        <span class="inline-flex items-center text-gray-400 cursor-not-allowed" 
                                              title="Cannot delete category with assigned jobs">
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
            include __DIR__ . '/../../components/pagination.php';
            renderPagination($pagination, '/Job_poster/public/job-categories', [
                'search' => $pagination['search'] ?? '',
                'per_page' => $pagination['per_page'] ?? 10
            ]);
            ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="/Job_poster/public/javascript/notyf.min.js"></script>
<script>
const notyf = new Notyf({
    duration: 3000,
    position: { x: 'right', y: 'top' }
});

// Debounce search functionality
let categorySearchTimeout;
function debounceCategorySearch() {
    clearTimeout(categorySearchTimeout);
    categorySearchTimeout = setTimeout(() => {
        document.getElementById('categoryFilterForm').submit();
    }, 500);
}

// Restore focus to search input after page reload
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const searchParam = urlParams.get('search');
    
    if (searchParam) {
        const searchInput = document.getElementById('search');
        if (searchInput) {
            searchInput.focus();
            searchInput.setSelectionRange(searchInput.value.length, searchInput.value.length);
        }
    }
});

async function deleteCategory(id, name) {
    const confirmed = await window.confirmModal.show(
        `Are you sure you want to delete the category "${name}"?`,
        'Delete Category',
        'Delete'
    );
    
    if (confirmed) {
        fetch(`/Job_poster/public/job-categories/delete/${id}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                notyf.success(data.message);
                // Remove the row from table
                const row = document.getElementById(`category-row-${id}`);
                if (row) {
                    row.remove();
                }
                // Reload page after a short delay to update counts
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                notyf.error(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            notyf.error('An error occurred while deleting the category.');
        });
    }
}
</script>

<?php include __DIR__ . '/../../layouts/admin_footer.php'; ?>
