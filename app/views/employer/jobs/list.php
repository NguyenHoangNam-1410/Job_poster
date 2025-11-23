<?php $pageHeading = 'My Posted Jobs'; ?>
<?php
require_once __DIR__ . '/../../layouts/auth_header.php';
require_once __DIR__ . '/../../../helpers/Icons.php';

$perPage = (int) ($pagination['per_page'] ?? 10);
$perPage = in_array($perPage, [10, 25, 50]) ? $perPage : 10;
$page = max(1, (int) ($pagination['current_page'] ?? 1));
$totalPages = $total_pages;
?>

<div class="list-container">
    <div class="list-content">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="list-header">My Posted Jobs</h1>
            </div>
            <button onclick="window.formModal.loadForm('/Worknest/public/my-jobs/create', 'Post New Job')"
                class="btn-submit">
                <?= Icons::add('btn-icon') ?>
                Post New Job
            </button>
        </div>

        <!-- Search and Filter Form -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <form method="GET" action="" id="myJobsFilterForm" class="flex flex-col gap-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                    <!-- Search -->
                    <div class="lg:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search by Title</label>
                        <input type="text" id="search" name="search"
                            value="<?= htmlspecialchars($pagination['search'] ?? '') ?>"
                            placeholder="Search job title..." class="form-input w-full"
                            onkeyup="debounceMyJobsSearch()">
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                        <select id="category" name="category" class="form-select" onchange="this.form.submit()">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category->getId() ?>" <?= ($pagination['category_filter'] ?? '') == $category->getId() ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($category->getCategoryName()) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select id="status" name="status" class="form-select" onchange="this.form.submit()">
                            <option value="">All</option>
                            <?php foreach (['draft', 'pending', 'approved', 'rejected', 'overdue'] as $status): ?>
                                <option value="<?= $status ?>" <?= ($pagination['status_filter'] ?? '') === $status ? 'selected' : '' ?>>
                                    <?= ucfirst($status) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- From Deadline -->
                    <div>
                        <label for="from_date" class="block text-sm font-medium text-gray-700 mb-2">From
                            Deadline</label>
                        <input type="date" id="from_date" name="from_date"
                            value="<?= htmlspecialchars($pagination['date_from'] ?? '') ?>" class="form-input"
                            onchange="this.form.submit()">
                    </div>

                    <!-- To Deadline -->
                    <div>
                        <label for="to_date" class="block text-sm font-medium text-gray-700 mb-2">To Deadline</label>
                        <input type="date" id="to_date" name="to_date"
                            value="<?= htmlspecialchars($pagination['date_to'] ?? '') ?>" class="form-input"
                            onchange="this.form.submit()">
                    </div>
                </div>

                <div class="flex justify-between items-center pt-2">
                    <div>
                        <label for="per_page" class="block text-sm font-medium text-gray-700 mb-2">Per Page</label>
                        <select id="per_page" name="per_page" class="form-select w-32" onchange="this.form.submit()">
                            <?php foreach ([10, 25, 50] as $pp): ?>
                                <option value="<?= $pp ?>" <?= $perPage == $pp ? 'selected' : '' ?>><?= $pp ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <a href="/Worknest/public/my-jobs" class="btn-cancel">Clear Filters</a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Jobs Table -->
        <?php if (empty($jobs)): ?>
            <div class="list-table-wrapper">
                <div id="empty-state" class="text-center py-12">
                    <?= Icons::emptyState() ?>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No jobs found</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        <?php if (!empty($pagination['search'])): ?>
                            No jobs match your search. Try different keywords.
                        <?php else: ?>
                            Post your first job to get started!
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
                                <th class="table-header">Location</th>
                                <th class="table-header">Salary</th>
                                <th class="table-header">Deadline</th>
                                <th class="table-header">Status</th>
                                <th class="table-header">Categories</th>
                                <th class="table-header">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($jobs as $job): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="table-cell font-medium"><?= htmlspecialchars($job->getTitle()) ?></td>
                                    <td class="table-cell"><?= htmlspecialchars($job->getLocation() ?? 'N/A') ?></td>
                                    <td class="table-cell">
                                        <?= $job->getSalary() ? number_format($job->getSalary(), 0, ',', '.') . ' VND' : '<span class="text-gray-400">Negotiable</span>' ?>
                                    </td>
                                    <td class="table-cell">
                                        <?= $job->getDeadline() ? date('M j, Y', strtotime($job->getDeadline())) : '<span class="text-gray-400">N/A</span>' ?>
                                    </td>
                                    <td class="table-cell">
                                        <span class="px-2 py-1 rounded text-sm <?= $job->getStatusColor() ?>">
                                            <?= ucfirst(str_replace('_', ' ', $job->getStatus())) ?>
                                        </span>
                                    </td>
                                    <td class="table-cell">
                                        <?php $cats = $job->getCategories() ?? []; ?>
                                        <?php if (!empty($cats)): ?>
                                            <div class="flex flex-wrap gap-1">
                                                <?php foreach (array_slice($cats, 0, 2) as $cat): ?>
                                                    <span
                                                        class="px-2 py-1 text-xs rounded bg-blue-50 text-blue-700"><?= htmlspecialchars($cat['name']) ?></span>
                                                <?php endforeach; ?>
                                                <?php if (count($cats) > 2): ?>
                                                    <span
                                                        class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-600">+<?= count($cats) - 2 ?></span>
                                                <?php endif; ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-gray-400 text-sm">No categories</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="table-cell">
                                        <div class="flex gap-2">
                                            <?php if ($job->getStatus() === 'pending'): ?>
                                                <button
                                                    onclick="window.formModal.loadForm('/Worknest/public/my-jobs/show/<?= $job->getId() ?>', 'Job Details')"
                                                    class="inline-flex items-center text-blue-600 hover:text-blue-900 text-sm">
                                                    <?= Icons::view('w-4 h-4 mr-1') ?> View
                                                </button>
                                            <?php else: ?>
                                                <button
                                                    onclick="window.formModal.loadForm('/Worknest/public/my-jobs/edit/<?= $job->getId() ?>', 'Edit Job')"
                                                    class="inline-flex items-center text-blue-600 hover:text-blue-900 text-sm">
                                                    <?= Icons::edit('w-4 h-4 mr-1') ?> Edit
                                                </button>
                                            <?php endif; ?>
                                            <?php $deleteType = $job->getStatus() === 'draft' ? 'hard' : 'soft'; ?>
                                            <button type="button"
                                                onclick="handleDeleteJob('<?= $deleteType ?>', '<?= $job->getId() ?>')"
                                                class="inline-flex items-center text-red-600 hover:text-red-900 text-sm">
                                                <?= Icons::delete('w-4 h-4 mr-1') ?> Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                    <div class="flex justify-center mt-6 gap-2 flex-wrap">
                        <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                            <?php
                            $query = $_GET;
                            $query['page'] = $p;
                            ?>
                            <a href="?<?= http_build_query($query) ?>"
                                class="px-3 py-1 rounded-lg <?= $p === $page ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700' ?>">
                                <?= $p ?>
                            </a>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    // Global functions that need to be accessible from HTML
    window.handleDeleteJob = async function (type, jobId) {
        let message = "Are you sure you want to delete this job?\n\n";
        if (type === 'soft') {
            message += "";
        } else {
            message += "The job will be permanently removed.";
        }

        const confirmed = await window.confirmModal.show(
            '',
            message,
            'Delete',
            'Cancel'
        );

        if (confirmed) {
            window.location.href = `/Worknest/public/my-jobs/${type}-delete/${jobId}?type=${type}`;
        }
    };
</script>
<?php
$additionalJS = ['/Worknest/public/javascript/employer_jobs.js'];
include __DIR__ . '/../../layouts/auth_footer.php';
?>