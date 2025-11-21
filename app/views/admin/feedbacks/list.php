<?php
$pageTitle = 'Employer Feedbacks';
require_once __DIR__ . '/../../layouts/auth_header.php';
require_once __DIR__ . '/../../../helpers/Icons.php';
?>

<div class="list-container">
    <div class="list-content">
        <div class="mb-6">
            <h1 class="list-header">Feedbacks</h1>
        </div>

        <!-- Success/Error Messages -->
        <?php if (isset($_GET['success'])): ?>
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline"><?php echo htmlspecialchars($_GET['success']); ?></span>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline"><?php echo htmlspecialchars($_GET['error']); ?></span>
            </div>
        <?php endif; ?>

        <!-- Search and Filter Form -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <form method="GET" action="" id="feedbackFilterForm" class="flex flex-wrap gap-4 items-end">
                <!-- Search by User Name -->
                <div class="flex-1 min-w-[200px]">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search by User Name</label>
                    <input type="text" name="search" id="search"
                        value="<?php echo htmlspecialchars($pagination['search'] ?? ''); ?>"
                        placeholder="Enter user name..." class="form-input" onkeyup="debounceFeedbackSearch()">
                </div>

                <!-- Date From -->
                <div class="flex-1 min-w-[150px]">
                    <label for="date_from" class="block text-sm font-medium text-gray-700 mb-2">Date From</label>
                    <input type="date" name="date_from" id="date_from"
                        value="<?php echo htmlspecialchars($pagination['date_from'] ?? ''); ?>" class="form-input"
                        onchange="document.getElementById('feedbackFilterForm').submit()">
                </div>

                <!-- Date To -->
                <div class="flex-1 min-w-[150px]">
                    <label for="date_to" class="block text-sm font-medium text-gray-700 mb-2">Date To</label>
                    <input type="date" name="date_to" id="date_to"
                        value="<?php echo htmlspecialchars($pagination['date_to'] ?? ''); ?>" class="form-input"
                        onchange="document.getElementById('feedbackFilterForm').submit()">
                </div>

                <!-- Per Page Selector -->
                <div class="w-[120px]">
                    <label for="per_page" class="block text-sm font-medium text-gray-700 mb-2">Per Page</label>
                    <select name="per_page" id="per_page" class="form-select" onchange="changePerPage(this.value)">
                        <option value="10" <?php echo $pagination['per_page'] == 10 ? 'selected' : ''; ?>>10</option>
                        <option value="25" <?php echo $pagination['per_page'] == 25 ? 'selected' : ''; ?>>25</option>
                        <option value="50" <?php echo $pagination['per_page'] == 50 ? 'selected' : ''; ?>>50</option>
                    </select>
                </div>

                <!-- Filter Buttons -->
                <div>
                    <a href="?" class="btn-cancel">
                        Clear
                    </a>
                </div>
            </form>
        </div>

        <?php if (empty($feedbacks)): ?>
            <div class="list-table-wrapper">
                <div id="empty-state" class="text-center py-12">
                    <?= Icons::emptyState() ?>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No feedbacks found</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        <?php if (!empty($pagination['search'])): ?>
                            No feedbacks match your search. Try different keywords.
                        <?php else: ?>
                            No feedback submissions yet.
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
                                <th class="table-header">
                                    ID
                                </th>
                                <th class="table-header">
                                    Name
                                </th>
                                <th class="table-header">
                                    Feedbacks
                                </th>
                                <th class="table-header">
                                    Date
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($feedbacks as $feedback): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="table-cell table-cell-text">
                                        #<?php echo htmlspecialchars($feedback->getUserId()); ?>
                                    </td>
                                    <td class="table-cell">
                                        <?php echo htmlspecialchars($feedback->getUserName()); ?>
                                    </td>
                                    <td class="table-cell">
                                        <div class="max-w-2xl">
                                            <?php echo nl2br(htmlspecialchars($feedback->getComments())); ?>
                                        </div>
                                    </td>
                                    <td class="table-cell">
                                        <?php
                                        $date = new DateTime($feedback->getCreatedAt());
                                        echo $date->format('d M, Y');
                                        ?>
                                        <span class="text-gray-500 text-xs block">
                                            <?php echo $date->format('H:i'); ?>
                                        </span>
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
                    '',
                    [
                        'search' => $pagination['search'] ?? '',
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

<script>
    function changePerPage(value) {
        const url = new URL(window.location);
        url.searchParams.set('per_page', value);
        url.searchParams.set('page', '1'); // Reset to first page
        window.location = url.toString();
    }

    // Debounce function for feedback search
    let feedbackSearchTimeout;
    function debounceFeedbackSearch() {
        clearTimeout(feedbackSearchTimeout);
        feedbackSearchTimeout = setTimeout(() => {
            document.getElementById('feedbackFilterForm').submit();
        }, 500); // Wait 500ms after user stops typing
    }

    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('search');
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('search') && searchInput.value) {
            searchInput.focus();
            // Set cursor to end of text
            searchInput.setSelectionRange(searchInput.value.length, searchInput.value.length);
        }
    });
</script>

<?php require_once __DIR__ . '/../../layouts/auth_footer.php'; ?>