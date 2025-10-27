<?php $pageTitle = 'Discount Management'; ?>
<?php
include __DIR__ . '/../../layouts/admin_header.php';
require_once __DIR__ . '/../../../helpers/Icons.php';
?>

<div class="list-container">
    <div class="list-content">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="list-header">Discount</h1>
                <p class="text-gray-600 mt-2">Manage discount codes and promotions</p>
            </div>
            <a href="/Job_poster/public/discounts/create" class="btn-submit">
                <?= Icons::add('btn-icon') ?>
                Add Discount
            </a>
        </div>

        <?php if (empty($discounts)): ?>
            <div class="list-table-wrapper">
                <div id="empty-state" class="text-center py-12">
                    <?= Icons::tag('mx-auto h-12 w-12 text-gray-400') ?>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No discounts found</h3>
                    <p class="mt-1 text-sm text-gray-500">Start by creating a new discount code.</p>
                </div>
            </div>
        <?php else: ?>
            <div class="list-table-wrapper">
                <div class="overflow-x-auto">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="table-header">ID</th>
                                    <th class="table-header">Code</th>
                                    <th class="table-header">Amount</th>
                                    <th class="table-header">Condition</th>
                                    <th class="table-header">Quantity</th>
                                    <th class="table-header">Status</th>
                                    <th class="table-header">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="discounts-table-body" class="bg-white divide-y divide-gray-200">
                                <?php foreach ($discounts as $discount): ?>
                                    <tr id="discount-<?= $discount->getId() ?>" class="hover:bg-gray-50">
                                        <td class="table-cell table-cell-text">
                                            <?= $discount->getId() ?>
                                        </td>
                                        <td class="table-cell">
                                            <span class="text-sm font-semibold text-indigo-600 bg-indigo-50 px-2 py-1 rounded">
                                                <?= htmlspecialchars($discount->getCode()) ?>
                                            </span>
                                        </td>
                                        <td class="table-cell table-cell-text font-medium">
                                            <?= number_format($discount->getMoneyDeduct(), 0, ',', '.') ?> ₫
                                        </td>
                                        <td class="table-cell table-cell-text-secondary">
                                            <?= htmlspecialchars($discount->getCondition() ?: 'None') ?>
                                        </td>
                                        <td class="table-cell table-cell-text">
                                            <?= $discount->getQuantity() ?>
                                        </td>
                                        <td class="table-cell">
                                            <?php
                                            $statusColors = [
                                                'Activate' => 'bg-green-100 text-green-800',
                                                'Hết hạn' => 'bg-red-100 text-red-800',
                                                'Đã xóa' => 'bg-gray-100 text-gray-800'
                                            ];
                                            $statusLabels = [
                                                'Activate' => 'Active',
                                                'Hết hạn' => 'Expired',
                                                'Đã xóa' => 'Deleted'
                                            ];
                                            ?>
                                            <span
                                                class="px-2 py-1 text-xs font-semibold rounded-full <?= $statusColors[$discount->getStatus()] ?? 'bg-gray-100 text-gray-800' ?>">
                                                <?= $statusLabels[$discount->getStatus()] ?? $discount->getStatus() ?>
                                            </span>
                                        </td>
                                        <td class="table-cell">
                                            <a href="/Job_poster/public/discounts/edit/<?= $discount->getId() ?>"
                                                class="inline-flex items-center text-blue-600 hover:text-blue-900 mr-3">
                                                <?= Icons::edit('w-5 h-5 mr-1') ?> Edit
                                            </a>
                                            <button onclick="deleteDiscount(<?= $discount->getId() ?>)"
                                                class="inline-flex items-center text-red-600 hover:text-red-900">
                                                <?= Icons::delete('w-5 h-5 mr-1') ?> Delete
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="/Job_poster/public/javascript/discount.js"></script>

    <?php include __DIR__ . '/../../layouts/admin_footer.php'; ?>