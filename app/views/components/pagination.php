<?php
/**
 * Reusable Pagination Component
 * 
 * @param array $pagination - Pagination data (current_page, total_pages, per_page, total_records, search, etc.)
 * @param string $baseUrl - Base URL for pagination links
 * @param array $extraParams - Additional query parameters to preserve
 */
function renderPagination($pagination, $baseUrl, $extraParams = []) {
    if ($pagination['total_pages'] <= 1) {
        return;
    }

    // Build query string with extra parameters
    $queryParams = [];
    foreach ($extraParams as $key => $value) {
        if (!empty($value)) {
            $queryParams[] = $key . '=' . urlencode($value);
        }
    }
    $queryString = !empty($queryParams) ? '&' . implode('&', $queryParams) : '';
    
    // Calculate page range to show
    $maxVisiblePages = 5;
    $startPage = max(1, $pagination['current_page'] - floor($maxVisiblePages / 2));
    $endPage = min($pagination['total_pages'], $startPage + $maxVisiblePages - 1);
    $startPage = max(1, $endPage - $maxVisiblePages + 1);
    ?>
    
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
                results
            </div>

            <!-- Pagination buttons -->
            <div class="flex items-center gap-1">
                <!-- First page button -->
                <?php if ($pagination['current_page'] > 1): ?>
                    <a href="<?= $baseUrl ?>?page=1<?= $queryString ?>" 
                       class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
                       title="First page">
                        &laquo;
                    </a>
                <?php else: ?>
                    <span class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 border border-gray-300 rounded-md cursor-not-allowed"
                          title="First page">
                        &laquo;
                    </span>
                <?php endif; ?>

                <!-- Previous button -->
                <?php if ($pagination['current_page'] > 1): ?>
                    <a href="<?= $baseUrl ?>?page=<?= $pagination['current_page'] - 1 ?><?= $queryString ?>" 
                       class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
                       title="Previous page">
                        &lsaquo;
                    </a>
                <?php else: ?>
                    <span class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 border border-gray-300 rounded-md cursor-not-allowed"
                          title="Previous page">
                        &lsaquo;
                    </span>
                <?php endif; ?>

                <!-- First page if not in range -->
                <?php if ($startPage > 1): ?>
                    <a href="<?= $baseUrl ?>?page=1<?= $queryString ?>" 
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
                        <a href="<?= $baseUrl ?>?page=<?= $i ?><?= $queryString ?>" 
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
                    <a href="<?= $baseUrl ?>?page=<?= $pagination['total_pages'] ?><?= $queryString ?>" 
                       class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        <?= $pagination['total_pages'] ?>
                    </a>
                <?php endif; ?>

                <!-- Next button -->
                <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                    <a href="<?= $baseUrl ?>?page=<?= $pagination['current_page'] + 1 ?><?= $queryString ?>" 
                       class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
                       title="Next page">
                        &rsaquo;
                    </a>
                <?php else: ?>
                    <span class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 border border-gray-300 rounded-md cursor-not-allowed"
                          title="Next page">
                        &rsaquo;
                    </span>
                <?php endif; ?>

                <!-- Last page button -->
                <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                    <a href="<?= $baseUrl ?>?page=<?= $pagination['total_pages'] ?><?= $queryString ?>" 
                       class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
                       title="Last page">
                        &raquo;
                    </a>
                <?php else: ?>
                    <span class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 border border-gray-300 rounded-md cursor-not-allowed"
                          title="Last page">
                        &raquo;
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php
}
