<?php $pageTitle = 'Job Management'; ?>
<?php
if(isset($_SESSION['user']) && $_SESSION['user']['role'] == 'Admin') {
    require_once '../app/views/layouts/admin_header.php';
} else{
    require_once '../app/views/layouts/staff_header.php';
}
require_once __DIR__ . '/../../../helpers/Icons.php';

?>

<div class="list-container">
    <div class="list-content">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="list-header">Job Management</h1>
                <p class="text-gray-600 mt-2">Manage and review job postings (Approved, Overdue, and Deleted Jobs)</p>
            </div>
        </div>

        <!-- Search and Filter Form -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <form method="GET" action="/Job_poster/public/jobs-manage" id="jobsManageFilterForm">
                <div class="flex flex-wrap gap-4 items-end">
                    <!-- Search -->
                    <div class="flex-1 min-w-[200px]">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search by Title</label>
                        <input 
                            type="text" 
                            id="search" 
                            name="search" 
                            value="<?= htmlspecialchars($pagination['search'] ?? '') ?>"
                            placeholder="Search job title..."
                            class="form-input w-full"
                            onkeyup="debounceJobsManageSearch()"
                        >
                    </div>

                    <!-- Category Filter -->
                    <div class="flex-1 min-w-[150px]">
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                        <select id="category" name="category" class="form-select" onchange="document.getElementById('jobsManageFilterForm').submit()">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category->getId() ?>" 
                                    <?= ($pagination['category_filter'] ?? '') == $category->getId() ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($category->getCategoryName()) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Location Filter -->
                    <div class="flex-1 min-w-[150px]">
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                        <select id="location" name="location" class="form-select" onchange="document.getElementById('jobsManageFilterForm').submit()">
                            <option value="">All Locations</option>
                            <?php foreach ($locations as $location): ?>
                                <option value="<?= htmlspecialchars($location) ?>" 
                                    <?= ($pagination['location_filter'] ?? '') == $location ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($location) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div class="flex-1 min-w-[150px]">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select id="status" name="status" class="form-select" onchange="document.getElementById('jobsManageFilterForm').submit()">
                            <option value="">All Statuses</option>
                            <option value="approved" <?= ($pagination['status_filter'] ?? '') == 'approved' ? 'selected' : '' ?>>Approved</option>
                            <option value="overdue" <?= ($pagination['status_filter'] ?? '') == 'overdue' ? 'selected' : '' ?>>Overdue</option>
                            <option value="soft_deleted" <?= ($pagination['status_filter'] ?? '') == 'soft_deleted' ? 'selected' : '' ?>>Deleted</option>
                        </select>
                    </div>

                    <!-- Per Page -->
                    <div class="w-[120px]">
                        <label for="per_page" class="block text-sm font-medium text-gray-700 mb-2">Per Page</label>
                        <select id="per_page" name="per_page" class="form-select" onchange="document.getElementById('jobsManageFilterForm').submit()">
                            <option value="10" <?= ($pagination['per_page'] ?? 10) == 10 ? 'selected' : '' ?>>10</option>
                            <option value="25" <?= ($pagination['per_page'] ?? 10) == 25 ? 'selected' : '' ?>>25</option>
                            <option value="50" <?= ($pagination['per_page'] ?? 10) == 50 ? 'selected' : '' ?>>50</option>
                        </select>
                    </div>

                    <!-- Clear Button -->
                    <div>
                        <a href="/Job_poster/public/jobs-manage" class="btn-cancel">
                            Clear Filters
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <?php if (isset($_GET['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?= htmlspecialchars($_GET['error']) ?></span>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['success'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?= htmlspecialchars($_GET['success']) ?></span>
            </div>
        <?php endif; ?>

        <?php if (empty($jobs)): ?>
            <div class="list-table-wrapper">
                <div id="empty-state" class="text-center py-12">
                    <?= Icons::emptyState() ?>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No jobs found</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        <?php if (!empty($pagination['search']) || !empty($pagination['category_filter']) || !empty($pagination['location_filter']) || !empty($pagination['status_filter'])): ?>
                            No jobs match your filters. Try adjusting your search criteria.
                        <?php else: ?>
                            No jobs available for management.
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        <?php else: ?>
            <div class="list-table-wrapper">
                <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="jobsTable">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="table-header">Title</th>
                            <th class="table-header">Employer</th>
                            <th class="table-header">Location</th>
                            <th class="table-header">Salary</th>
                            <th class="table-header">Deadline</th>
                            <th class="table-header">Status</th>
                            <th class="table-header">Categories</th>
                            <th class="table-header">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="jobsTableBody" class="bg-white divide-y divide-gray-200">
                        <?php foreach ($jobs as $job): ?>
                            <tr class="hover:bg-gray-50" id="job-row-<?= $job->getId() ?>">
                                <td class="table-cell font-medium">
                                    <?= htmlspecialchars($job->getTitle()) ?>
                                </td>
                                <td class="table-cell">
                                    <?= htmlspecialchars($job->getEmployerName() ?? 'N/A') ?>
                                </td>
                                <td class="table-cell">
                                    <?= htmlspecialchars($job->getLocation() ?? 'N/A') ?>
                                </td>
                                <td class="table-cell">
                                    <?php if ($job->getSalary()): ?>
                                        <?= number_format($job->getSalary(), 0, ',', '.') ?> VND
                                    <?php else: ?>
                                        <span class="text-gray-400">Negotiable</span>
                                    <?php endif; ?>
                                </td>
                                <td class="table-cell">
                                    <?php if ($job->getDeadline()): ?>
                                        <?= date('Y-m-d', strtotime($job->getDeadline())) ?>
                                    <?php else: ?>
                                        <span class="text-gray-400">N/A</span>
                                    <?php endif; ?>
                                </td>
                                <td class="table-cell">
                                    <span class="px-2 py-1 rounded text-sm <?= $job->getStatusColor() ?>">
                                        <?php 
                                            $status = $job->getStatus();
                                            echo $status === 'soft_deleted' ? 'Deleted' : ucfirst(str_replace('_', ' ', $status));
                                        ?>
                                    </span>
                                </td>
                                <td class="table-cell">
                                    <?php 
                                    $categories = $job->getCategories();
                                    if (!empty($categories)): 
                                    ?>
                                        <div class="flex flex-wrap gap-1">
                                            <?php foreach (array_slice($categories, 0, 2) as $cat): ?>
                                                <span class="px-2 py-1 text-xs rounded bg-blue-50 text-blue-700">
                                                    <?= htmlspecialchars($cat['name']) ?>
                                                </span>
                                            <?php endforeach; ?>
                                            <?php if (count($categories) > 2): ?>
                                                <span class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-600">
                                                    +<?= count($categories) - 2 ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-gray-400 text-sm">No categories</span>
                                    <?php endif; ?>
                                </td>
                                <td class="table-cell">
                                    <div class="flex gap-2">
                                        <a href="/Job_poster/public/jobs-manage/edit/<?= $job->getId() ?>"
                                            class="inline-flex items-center text-blue-600 hover:text-blue-900 text-sm">
                                            <?= Icons::edit('w-4 h-4 mr-1') ?>
                                            Edit
                                        </a>
                                        
                                        <button
                                            onclick="deleteJob(<?= $job->getId() ?>, '<?= htmlspecialchars($job->getTitle(), ENT_QUOTES) ?>')"
                                            class="inline-flex items-center text-red-600 hover:text-red-900 text-sm focus:outline-none">
                                            <?= Icons::delete('w-4 h-4 mr-1') ?>
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php 
            include __DIR__ . '/../../components/pagination.php';
            renderPagination($pagination, '/Job_poster/public/jobs-manage', [
                'search' => $pagination['search'] ?? '',
                'category' => $pagination['category_filter'] ?? '',
                'location' => $pagination['location_filter'] ?? '',
                'status' => $pagination['status_filter'] ?? '',
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
let jobsManageSearchTimeout;
function debounceJobsManageSearch() {
    clearTimeout(jobsManageSearchTimeout);
    jobsManageSearchTimeout = setTimeout(() => {
        document.getElementById('jobsManageFilterForm').submit();
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

function deleteJob(id, title) {
    if (confirm(`⚠️ DELETE JOB\n\nAre you sure you want to delete "${title}"?\n\nThis action cannot be undone!`)) {
        fetch(`/Job_poster/public/jobs-manage/hard-delete/${id}`, {
            method: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                notyf.success(data.message);
                const row = document.getElementById(`job-row-${id}`);
                if (row) row.remove();
                setTimeout(() => window.location.reload(), 1000);
            } else {
                notyf.error(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            notyf.error('An error occurred while deleting the job.');
        });
    }
}
</script>

<?php 
if(isset($_SESSION['user']) && $_SESSION['user']['role'] == 'Admin') {
    require_once '../app/views/layouts/admin_footer.php';
} else{
    require_once '../app/views/layouts/staff_footer.php';
}
?>
