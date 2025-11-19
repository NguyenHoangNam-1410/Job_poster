<?php require_once __DIR__ . '/../../layouts/public_header.php'; ?>

<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">My Employer Feedbacks</h1>
            <p class="text-gray-600 mt-2">View feedback submissions for your company</p>
        </div>

        <a href="/Job_poster/public/my-feedbacks/create"
        class="mt-4 md:mt-0 inline-block bg-blue-600 text-white px-5 py-2 rounded-md shadow hover:bg-blue-700 transition duration-200">
            + Add Feedback
        </a>
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

        <!-- Filters Section -->
        <div class="bg-gray-50 p-4 rounded-lg mb-6">
            <form method="GET" action="" id="feedbackFilterForm" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Date From -->
                <div>
                    <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">Date From</label>
                    <input type="date" name="date_from" id="date_from" 
                           value="<?= htmlspecialchars($pagination['date_from'] ?? '') ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           onchange="document.getElementById('feedbackFilterForm').submit()">
                </div>

                <!-- Date To -->
                <div>
                    <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">Date To</label>
                    <input type="date" name="date_to" id="date_to" 
                           value="<?= htmlspecialchars($pagination['date_to'] ?? '') ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           onchange="document.getElementById('feedbackFilterForm').submit()">
                </div>

                <!-- Reset Button -->
                <div class="flex items-end">
                    <a href="?" class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition duration-200 text-center">
                        Reset
                    </a>
                </div>

                <!-- Hidden per_page field -->
                <input type="hidden" name="per_page" value="<?= $pagination['per_page'] ?>">
            </form>
        </div>

        <!-- Results Info & Pagination Controls -->
        <div class="flex flex-col sm:flex-row justify-between items-center mb-4 gap-4">
            <div class="text-gray-700">
                Showing <?= count($feedbacks) ?> of <?= $pagination['total_records'] ?> feedbacks
            </div>
            
            <!-- Per Page Selector -->
            <div class="flex items-center gap-2">
                <label for="per_page" class="text-sm text-gray-700">Per page:</label>
                <select name="per_page" id="per_page" onchange="changePerPage(this.value)" 
                        class="px-3 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="10" <?= $pagination['per_page'] == 10 ? 'selected' : '' ?>>10</option>
                    <option value="25" <?= $pagination['per_page'] == 25 ? 'selected' : '' ?>>25</option>
                    <option value="50" <?= $pagination['per_page'] == 50 ? 'selected' : '' ?>>50</option>
                </select>
            </div>
        </div>

        <!-- Feedbacks Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-b">#</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-b">Feedback</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-b">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php if (empty($feedbacks)): ?>
                        <tr>
                            <td colspan="2" class="px-4 py-8 text-center text-gray-500">
                                No feedbacks found.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $i = ($pagination['current_page'] - 1) * $pagination['per_page'] + 1; ?>
                        <?php foreach ($feedbacks as $feedback): ?>
                             <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-4 py-3 text-sm text-gray-900">
                                    <?php echo $i++; ?>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700 max-w-2xl"><?= nl2br(htmlspecialchars($feedback->getComments())) ?></td>
                                <td class="px-4 py-3 text-sm text-gray-900">
                                    <?php 
                                    $date = new DateTime($feedback->getCreatedAt());
                                    echo $date->format('M d, Y'); 
                                    ?>
                                    <span class="text-gray-500 text-xs block"><?= $date->format('h:i A') ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php 
        require_once __DIR__ . '/../../components/pagination.php';
        renderPagination($pagination, '', [
            'date_from' => $pagination['date_from'] ?? '',
            'date_to' => $pagination['date_to'] ?? '',
            'per_page' => $pagination['per_page']
        ]);
        ?>
    </div>
</div>

<script>
function changePerPage(value) {
    const url = new URL(window.location);
    url.searchParams.set('per_page', value);
    url.searchParams.set('page', '1'); // Reset to first page
    window.location = url.toString();
}
</script>

<?php require_once __DIR__ . '/../../layouts/public_footer.php'; ?>
