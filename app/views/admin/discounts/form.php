<?php 
$pageTitle = $mode === 'create' ? 'Create Discount' : 'Edit Discount';
$isEdit = $mode === 'edit';
$formAction = $isEdit ? "/Job_poster/public/discounts/update/" . $discount->getId() : "/Job_poster/public/discounts/store";
?>
<?php 
include __DIR__ . '/../../layouts/admin_header.php';
require_once __DIR__ . '/../../../helpers/Icons.php';
?>

<div class="form-background">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            <?= $isEdit ? 'Edit Discount' : 'Create New Discount' ?>
        </h1>
        <p class="text-gray-600 mt-2">
            <?= $isEdit ? 'Update discount code information' : 'Add a new discount code to the system' ?>
        </p>
    </div>

    <!-- Form -->
    <div class="form-container">
        <form method="POST" action="<?= $formAction ?>" class="space-y-6">
            <!-- Discount Code -->
            <div>
                <label for="code" class="form-label">Discount Code *</label>
                <input type="text" 
                       id="code" 
                       name="code" 
                       class="form-input"
                       value="<?= $isEdit ? htmlspecialchars($discount->getCode()) : '' ?>"
                       placeholder="e.g., SUMMER2024"
                       disabled="<?= $isEdit ? 'disabled' : '' ?>">
                <p class="text-sm text-gray-500 mt-1">Unique code that customers will use</p>
            </div>

            <!-- Discount Amount -->
            <div>
                <label for="money_deduct" class="form-label">Discount Amount (₫) *</label>
                <input type="number" 
                       id="money_deduct" 
                       name="money_deduct" 
                       class="form-input"
                       value="<?= $isEdit ? $discount->getMoneyDeduct() : '' ?>"
                       placeholder="e.g., 50000"
                       min="0"
                       step="1000"
                       required>
                <p class="text-sm text-gray-500 mt-1">Amount to be deducted from order total</p>
            </div>

            <!-- Condition -->
            <div>
                <label for="dieu_kien" class="form-label">Condition</label>
                <textarea id="dieu_kien" 
                          name="dieu_kien" 
                          class="form-input"
                          rows="3"
                          placeholder="e.g., Minimum order 500,000₫"><?= $isEdit ? htmlspecialchars($discount->getCondition()) : '' ?></textarea>
                <p class="text-sm text-gray-500 mt-1">Optional conditions for using this discount</p>
            </div>

            <!-- Quantity -->
            <div>
                <label for="so_luong" class="form-label">Quantity *</label>
                <input type="number" 
                       id="so_luong" 
                       name="so_luong" 
                       class="form-input"
                       value="<?= $isEdit ? $discount->getQuantity() : '' ?>"
                       placeholder="e.g., 100"
                       min="0"
                       required>
                <p class="text-sm text-gray-500 mt-1">Number of times this code can be used</p>
            </div>

            <!-- Status -->
            <div>
                <label for="trang_thai" class="form-label">Status *</label>
                <select id="trang_thai" name="trang_thai" class="form-input" required>
                    <option value="Activate" <?= $isEdit && $discount->getStatus() === 'Activate' ? 'selected' : '' ?>>
                        Active
                    </option>
                    <option value="Hết hạn" <?= $isEdit && $discount->getStatus() === 'Hết hạn' ? 'selected' : '' ?>>
                        Expired
                    </option>
                    <option value="Đã xóa" <?= $isEdit && $discount->getStatus() === 'Đã xóa' ? 'selected' : '' ?>>
                        Deleted
                    </option>
                </select>
                <p class="text-sm text-gray-500 mt-1">Current status of the discount code</p>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center space-x-4 pt-4">
                <button type="submit" class="btn-submit">
                    <?= Icons::check('btn-icon') ?>
                    <?= $isEdit ? 'Update Discount' : 'Create Discount' ?>
                </button>
                <a href="/Job_poster/public/discounts" class="btn-cancel">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/admin_footer.php'; ?>
