<?php require_once __DIR__ . '/../../layouts/auth_header.php'; ?>

<div class="list-container">
    <div class="list-content">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="list-header">My Feedbacks</h1>
            </div>
            <button onclick="window.formModal.loadForm('/Job_poster/public/my-feedbacks/create', 'Add New Feedback')"
                class="btn-submit">
                <?= Icons::add('btn-icon') ?>
                Add Feedback
            </button>
        </div>

        <!-- Success/Error Messages -->
        <?php if (isset($_GET['success'])): ?>
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline"><?= htmlspecialchars($_GET['success']) ?></span>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline"><?= htmlspecialchars($_GET['error']) ?></span>
            </div>
        <?php endif; ?>

        <!-- Search and Filter Form -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <form method="GET" action="" id="feedbackFilterForm" class="flex flex-col sm:flex-row gap-4">
                <!-- Date From -->
                <div class="flex-1">
                    <label for="date_from" class="block text-sm font-medium text-gray-700 mb-2">Date From</label>
                    <input type="date" name="date_from" id="date_from"
                        value="<?= htmlspecialchars($pagination['date_from'] ?? '') ?>" class="form-input w-full"
                        onchange="document.getElementById('feedbackFilterForm').submit()">
                </div>

                <!-- Date To -->
                <div class="flex-1">
                    <label for="date_to" class="block text-sm font-medium text-gray-700 mb-2">Date To</label>
                    <input type="date" name="date_to" id="date_to"
                        value="<?= htmlspecialchars($pagination['date_to'] ?? '') ?>" class="form-input w-full"
                        onchange="document.getElementById('feedbackFilterForm').submit()">
                </div>

                <div class="sm:w-32">
                    <label for="per_page" class="block text-sm font-medium text-gray-700 mb-2">Per Page</label>
                    <select id="per_page" name="per_page" class="form-select"
                        onchange="document.getElementById('feedbackFilterForm').submit()">
                        <option value="10" <?= ($pagination['per_page'] ?? 10) == 10 ? 'selected' : '' ?>>10</option>
                        <option value="25" <?= ($pagination['per_page'] ?? 10) == 25 ? 'selected' : '' ?>>25</option>
                        <option value="50" <?= ($pagination['per_page'] ?? 10) == 50 ? 'selected' : '' ?>>50</option>
                    </select>
                </div>

                <div class="flex gap-2 items-end">
                    <a href="/Job_poster/public/my-feedbacks" class="btn-cancel">
                        Clear
                    </a>
                </div>
            </form>
        </div>

        <!-- Feedbacks Table -->
        <?php if (empty($feedbacks)): ?>
            <div class="list-table-wrapper">
                <div id="empty-state" class="text-center py-12">
                    <?= Icons::emptyState() ?>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No feedbacks found</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        <?php if (!empty($pagination['date_from']) || !empty($pagination['date_to'])): ?>
                            No feedbacks match your date range.
                        <?php else: ?>
                            Submit your first feedback!
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
                                <th class="table-header">#</th>
                                <th class="table-header">Feedback</th>
                                <th class="table-header">Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php $i = ($pagination['current_page'] - 1) * $pagination['per_page'] + 1; ?>
                            <?php foreach ($feedbacks as $feedback): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="table-cell table-cell-text">
                                        <?php echo $i++; ?>
                                    </td>
                                    <td class="table-cell"><?= nl2br(htmlspecialchars($feedback->getComments())) ?></td>
                                    <td class="table-cell">
                                        <?php
                                        $date = new DateTime($feedback->getCreatedAt());
                                        echo $date->format('M d, Y');
                                        ?>
                                        <span class="text-gray-500 text-xs block"><?= $date->format('h:i A') ?></span>
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
                    '/Job_poster/public/my-feedbacks',
                    [
                        'date_from' => $pagination['date_from'] ?? '',
                        'date_to' => $pagination['date_to'] ?? '',
                        'per_page' => $pagination['per_page']
                    ]
                );
                ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../../layouts/auth_footer.php'; ?>