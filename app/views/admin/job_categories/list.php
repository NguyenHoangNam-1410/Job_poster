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
                <p class="text-gray-600 mt-2">Manage job categories for job filtering and organization</p>
            </div>
            <a href="/Job_poster/public/job-categories/create"
                class="btn-submit">
                <?= Icons::add('btn-icon') ?>
                Add Category
            </a>
        </div>

        <!-- Search and Filter Form -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <form method="GET" action="/Job_poster/public/job-categories" class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search Categories</label>
                    <input 
                        type="text" 
                        id="search" 
                        name="search" 
                        value="<?= htmlspecialchars($pagination['search'] ?? '') ?>"
                        placeholder="Search by category name..."
                        class="form-input w-full"
                    >
                </div>
                <div class="sm:w-32">
                    <label for="per_page" class="block text-sm font-medium text-gray-700 mb-2">Per Page</label>
                    <select id="per_page" name="per_page" class="form-select">
                        <option value="10" <?= ($pagination['per_page'] ?? 10) == 10 ? 'selected' : '' ?>>10</option>
                        <option value="25" <?= ($pagination['per_page'] ?? 10) == 25 ? 'selected' : '' ?>>25</option>
                    </select>
                </div>
                <div class="flex gap-2 items-end">
                    <button type="submit" class="btn-submit">
                        <?= Icons::filter('btn-icon') ?>
                        Search
                    </button>
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
                                    <a href="/Job_poster/public/job-categories/edit/<?= $category->getId() ?>"
                                        class="inline-flex items-center text-blue-600 hover:text-blue-900 mr-3">
                                        <?= Icons::edit('w-5 h-5 mr-1') ?>
                                        Edit
                                    </a>
                                    
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
            <?php if ($pagination['total_pages'] > 1): ?>
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <!-- Results info -->
                        <div class="text-sm text-gray-700">
                            Showing 
                            <span class="font-medium"><?= min(($pagination['current_page'] - 1) * $pagination['per_page'] + 1, $pagination['total_records']) ?></span>
                            to 
                            <span class="font-medium"><?= min($pagination['current_page'] * $pagination['per_page'], $pagination['total_records']) ?></span>
                            of 
                            <span class="font-medium"><?= $pagination['total_records'] ?></span>
                            categories
                        </div>

                        <!-- Pagination buttons -->
                        <div class="flex items-center gap-2">
                            <?php
                            $baseUrl = '/Job_poster/public/job-categories?';
                            if (!empty($pagination['search'])) {
                                $baseUrl .= 'search=' . urlencode($pagination['search']) . '&';
                            }
                            $baseUrl .= 'per_page=' . $pagination['per_page'] . '&';

                            // Calculate page range to show
                            $maxVisiblePages = 5;
                            $startPage = max(1, $pagination['current_page'] - floor($maxVisiblePages / 2));
                            $endPage = min($pagination['total_pages'], $startPage + $maxVisiblePages - 1);
                            $startPage = max(1, $endPage - $maxVisiblePages + 1);
                            ?>

                            <!-- Previous button -->
                            <?php if ($pagination['current_page'] > 1): ?>
                                <a href="<?= $baseUrl ?>page=<?= $pagination['current_page'] - 1 ?>" 
                                   class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                    Previous
                                </a>
                            <?php else: ?>
                                <span class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 border border-gray-300 rounded-md cursor-not-allowed">
                                    Previous
                                </span>
                            <?php endif; ?>

                            <!-- First page if not in range -->
                            <?php if ($startPage > 1): ?>
                                <a href="<?= $baseUrl ?>page=1" 
                                   class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                    1
                                </a>
                                <?php if ($startPage > 2): ?>
                                    <span class="px-2 py-2 text-gray-500">...</span>
                                <?php endif; ?>
                            <?php endif; ?>

                            <!-- Page range -->
                            <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                                <?php if ($i == $pagination['current_page']): ?>
                                    <span class="px-3 py-2 text-sm font-medium text-white bg-blue-600 border border-blue-600 rounded-md">
                                        <?= $i ?>
                                    </span>
                                <?php else: ?>
                                    <a href="<?= $baseUrl ?>page=<?= $i ?>" 
                                       class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                        <?= $i ?>
                                    </a>
                                <?php endif; ?>
                            <?php endfor; ?>

                            <!-- Last page if not in range -->
                            <?php if ($endPage < $pagination['total_pages']): ?>
                                <?php if ($endPage < $pagination['total_pages'] - 1): ?>
                                    <span class="px-2 py-2 text-gray-500">...</span>
                                <?php endif; ?>
                                <a href="<?= $baseUrl ?>page=<?= $pagination['total_pages'] ?>" 
                                   class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                    <?= $pagination['total_pages'] ?>
                                </a>
                            <?php endif; ?>

                            <!-- Next button -->
                            <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                                <a href="<?= $baseUrl ?>page=<?= $pagination['current_page'] + 1 ?>" 
                                   class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                    Next
                                </a>
                            <?php else: ?>
                                <span class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 border border-gray-300 rounded-md cursor-not-allowed">
                                    Next
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
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

function deleteCategory(id, name) {
    if (confirm(`Are you sure you want to delete the category "${name}"?`)) {
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
