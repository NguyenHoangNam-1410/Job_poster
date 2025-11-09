<?php $pageTitle = 'Job Approval'; ?>
<?php
include __DIR__ . '/../../layouts/admin_header.php';
require_once __DIR__ . '/../../../helpers/Icons.php';
?>

<div class="list-container">
    <div class="list-content">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="list-header">Job Approval</h1>
                <p class="text-gray-600 mt-2">Review and approve pending job postings</p>
            </div>
        </div>

        <!-- Search and Filter Form -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <form method="GET" action="/Job_poster/public/approvals" id="approvalsFilterForm" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div class="lg:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search by Title</label>
                        <input 
                            type="text" 
                            id="search" 
                            name="search" 
                            value="<?= htmlspecialchars($pagination['search'] ?? '') ?>"
                            placeholder="Search job title..."
                            class="form-input w-full"
                            onkeyup="debounceApprovalsSearch()"
                        >
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select id="status" name="status" class="form-select" onchange="document.getElementById('approvalsFilterForm').submit()">
                            <option value="">All Statuses</option>
                            <option value="pending" <?= ($pagination['status_filter'] ?? '') == 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="rejected" <?= ($pagination['status_filter'] ?? '') == 'rejected' ? 'selected' : '' ?>>Rejected</option>
                        </select>
                    </div>

                    <!-- Per Page -->
                    <div>
                        <label for="per_page" class="block text-sm font-medium text-gray-700 mb-2">Per Page</label>
                        <select id="per_page" name="per_page" class="form-select" onchange="document.getElementById('approvalsFilterForm').submit()">
                            <option value="10" <?= ($pagination['per_page'] ?? 10) == 10 ? 'selected' : '' ?>>10</option>
                            <option value="25" <?= ($pagination['per_page'] ?? 10) == 25 ? 'selected' : '' ?>>25</option>
                            <option value="50" <?= ($pagination['per_page'] ?? 10) == 50 ? 'selected' : '' ?>>50</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end gap-2">
                    <a href="/Job_poster/public/approvals" class="btn-cancel">
                        Clear Filters
                    </a>
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
                        <?php if (!empty($pagination['search']) || !empty($pagination['status_filter'])): ?>
                            No jobs match your filters. Try adjusting your search criteria.
                        <?php else: ?>
                            No pending or rejected jobs available for review.
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        <?php else: ?>
            <div class="list-table-wrapper">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="table-header">Title</th>
                                <th class="table-header">Company Name</th>
                                <th class="table-header">Employer Name</th>
                                <th class="table-header">Status</th>
                                <th class="table-header">Created At</th>
                                <th class="table-header">Updated At</th>
                                <th class="table-header">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($jobs as $job): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="table-cell font-medium">
                                        <?= htmlspecialchars($job->getTitle()) ?>
                                    </td>
                                    <td class="table-cell">
                                        <?= htmlspecialchars($job->getCompanyName() ?? 'N/A') ?>
                                    </td>
                                    <td class="table-cell">
                                        <?= htmlspecialchars($job->getEmployerName() ?? 'N/A') ?>
                                    </td>
                                    <td class="table-cell">
                                        <span class="px-2 py-1 rounded text-sm <?= $job->getStatusColor() ?>">
                                            <?= ucfirst($job->getStatus()) ?>
                                        </span>
                                    </td>
                                    <td class="table-cell">
                                        <?= date('Y-m-d H:i', strtotime($job->getCreatedAt())) ?>
                                    </td>
                                    <td class="table-cell">
                                        <?= date('Y-m-d H:i', strtotime($job->getUpdatedAt())) ?>
                                    </td>
                                    <td class="table-cell">
                                        <a href="/Job_poster/public/approvals/detail/<?= $job->getId() ?>"
                                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md">
                                            <?= Icons::view('w-4 h-4 mr-1') ?>
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php 
                include __DIR__ . '/../../components/pagination.php';
                renderPagination($pagination, '/Job_poster/public/approvals', [
                    'search' => $pagination['search'] ?? '',
                    'status' => $pagination['status_filter'] ?? '',
                    'per_page' => $pagination['per_page'] ?? 10
                ]);
                ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
// Debounce search functionality
let approvalsSearchTimeout;
function debounceApprovalsSearch() {
    clearTimeout(approvalsSearchTimeout);
    approvalsSearchTimeout = setTimeout(() => {
        document.getElementById('approvalsFilterForm').submit();
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
</script>

<?php include __DIR__ . '/../../layouts/admin_footer.php'; ?>
