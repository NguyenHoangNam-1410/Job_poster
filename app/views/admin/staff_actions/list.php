<?php 
$pageTitle = 'Staff Action History';
require_once __DIR__ . '/../../layouts/admin_header.php';
require_once __DIR__ . '/../../../helpers/Icons.php';
?>

<div class="list-container">
    <div class="list-content">
        <div class="mb-6">
            <h1 class="list-header">Staff Action History</h1>
        </div>

        <!-- Search and Filter Form -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <form method="GET" action="" id="staffActionFilterForm" class="flex flex-wrap gap-4 items-end">
                <!-- User Role Filter -->
                <div class="flex-1 min-w-[150px]">
                    <label for="user_role" class="block text-sm font-medium text-gray-700 mb-2">User Role</label>
                    <select name="user_role" id="user_role" class="form-select" onchange="document.getElementById('staffActionFilterForm').submit()">
                        <option value="">All Roles</option>
                        <option value="Admin" <?php echo ($pagination['user_role_filter'] ?? '') === 'Admin' ? 'selected' : ''; ?>>Admin</option>
                        <option value="Staff" <?php echo ($pagination['user_role_filter'] ?? '') === 'Staff' ? 'selected' : ''; ?>>Staff</option>
                    </select>
                </div>

                <!-- Action Type Filter -->
                <div class="flex-1 min-w-[150px]">
                    <label for="action_type" class="block text-sm font-medium text-gray-700 mb-2">Action Type</label>
                    <select name="action_type" id="action_type" class="form-select" onchange="document.getElementById('staffActionFilterForm').submit()">
                        <option value="">All Actions</option>
                        <?php foreach ($actionTypes as $type): ?>
                            <option value="<?php echo htmlspecialchars($type); ?>" 
                                <?php echo ($pagination['action_type_filter'] ?? '') === $type ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $type))); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Date From -->
                <div class="flex-1 min-w-[150px]">
                    <label for="date_from" class="block text-sm font-medium text-gray-700 mb-2">Date From</label>
                    <input type="date" name="date_from" id="date_from" 
                           value="<?php echo htmlspecialchars($pagination['date_from'] ?? ''); ?>"
                           class="form-input"
                           onchange="document.getElementById('staffActionFilterForm').submit()">
                </div>

                <!-- Date To -->
                <div class="flex-1 min-w-[150px]">
                    <label for="date_to" class="block text-sm font-medium text-gray-700 mb-2">Date To</label>
                    <input type="date" name="date_to" id="date_to" 
                           value="<?php echo htmlspecialchars($pagination['date_to'] ?? ''); ?>"
                           class="form-input"
                           onchange="document.getElementById('staffActionFilterForm').submit()">
                </div>

                <!-- Per Page Selector -->
                <div class="w-[120px]">
                    <label for="per_page" class="block text-sm font-medium text-gray-700 mb-2">Per Page</label>
                    <select name="per_page" id="per_page" class="form-select" onchange="document.getElementById('staffActionFilterForm').submit()">
                        <option value="10" <?php echo $pagination['per_page'] == 10 ? 'selected' : ''; ?>>10</option>
                        <option value="25" <?php echo $pagination['per_page'] == 25 ? 'selected' : ''; ?>>25</option>
                        <option value="50" <?php echo $pagination['per_page'] == 50 ? 'selected' : ''; ?>>50</option>
                    </select>
                </div>
                
                <div>
                    <a href="?" class="btn-cancel">
                        Clear
                    </a>
                </div>
            </form>
        </div>

        <?php if (empty($actions)): ?>
            <div class="list-table-wrapper">
                <div id="empty-state" class="text-center py-12">
                    <?= Icons::emptyState() ?>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No actions found</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        No staff actions match your filters.
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
                                Name
                            </th>
                            <th class="table-header">
                                Role
                            </th>
                            <th class="table-header">
                                Job Title
                            </th>
                            <th class="table-header">
                                Action Type
                            </th>
                            <th class="table-header">
                                Action Date
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($actions as $action): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="table-cell">
                                    <?php echo htmlspecialchars($action->getUserName()); ?>
                                </td>
                                <td class="table-cell">
                                    <span class="px-2 py-1 rounded 
                                        <?php echo $action->getUserRole() === 'Admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800'; ?>">
                                        <?php echo htmlspecialchars($action->getUserRole()); ?>
                                    </span>
                                </td>
                                <td class="table-cell">
                                    <?php echo htmlspecialchars($action->getJobTitle()); ?>
                                </td>
                                <td class="table-cell">
                                    <span class="px-2 py-1 rounded text-xs
                                        <?php 
                                        $actionType = $action->getActionType();
                                        if (strpos($actionType, 'create') !== false) {
                                            echo 'bg-green-100 text-green-800';
                                        } elseif (strpos($actionType, 'update') !== false) {
                                            echo 'bg-yellow-100 text-yellow-800';
                                        } elseif (strpos($actionType, 'delete') !== false) {
                                            echo 'bg-red-100 text-red-800';
                                        } elseif (strpos($actionType, 'status') !== false) {
                                            echo 'bg-indigo-100 text-indigo-800';
                                        } else {
                                            echo 'bg-gray-100 text-gray-800';
                                        }
                                        ?>">
                                        <?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $actionType))); ?>
                                    </span>
                                </td>
                                <td class="table-cell">
                                    <?php 
                                    $date = new DateTime($action->getActionDate());
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
                renderPagination($pagination, '', [
                    'user_role' => $pagination['user_role_filter'] ?? '',
                    'action_type' => $pagination['action_type_filter'] ?? '',
                    'date_from' => $pagination['date_from'] ?? '',
                    'date_to' => $pagination['date_to'] ?? '',
                    'per_page' => $pagination['per_page'] ?? 10
                ]);
                ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../../layouts/admin_footer.php'; ?>
