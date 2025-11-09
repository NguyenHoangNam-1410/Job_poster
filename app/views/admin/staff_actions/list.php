<?php require_once __DIR__ . '/../../layouts/admin_header.php'; ?>

<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Staff Action History</h1>
            <p class="text-gray-600 mt-2">Track all administrative actions performed on jobs</p>
        </div>

        <!-- Filters Section -->
        <div class="bg-gray-50 p-4 rounded-lg mb-6">
            <form method="GET" action="" id="staffActionFilterForm" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- User Role Filter -->
                <div>
                    <label for="user_role" class="block text-sm font-medium text-gray-700 mb-1">User Role</label>
                    <select name="user_role" id="user_role" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="document.getElementById('staffActionFilterForm').submit()">
                        <option value="">All Roles</option>
                        <option value="Admin" <?php echo ($pagination['user_role_filter'] ?? '') === 'Admin' ? 'selected' : ''; ?>>Admin</option>
                        <option value="Staff" <?php echo ($pagination['user_role_filter'] ?? '') === 'Staff' ? 'selected' : ''; ?>>Staff</option>
                    </select>
                </div>

                <!-- Action Type Filter -->
                <div>
                    <label for="action_type" class="block text-sm font-medium text-gray-700 mb-1">Action Type</label>
                    <select name="action_type" id="action_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="document.getElementById('staffActionFilterForm').submit()">
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
                <div>
                    <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">Date From</label>
                    <input type="date" name="date_from" id="date_from" 
                           value="<?php echo htmlspecialchars($pagination['date_from'] ?? ''); ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           onchange="document.getElementById('staffActionFilterForm').submit()">
                </div>

                <!-- Date To -->
                <div>
                    <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">Date To</label>
                    <input type="date" name="date_to" id="date_to" 
                           value="<?php echo htmlspecialchars($pagination['date_to'] ?? ''); ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           onchange="document.getElementById('staffActionFilterForm').submit()">
                </div>

                <!-- Per Page Selector -->
                <div>
                    <label for="per_page" class="block text-sm font-medium text-gray-700 mb-1">Per Page</label>
                    <select name="per_page" id="per_page" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="document.getElementById('staffActionFilterForm').submit()">
                        <option value="10" <?php echo $pagination['per_page'] == 10 ? 'selected' : ''; ?>>10</option>
                        <option value="25" <?php echo $pagination['per_page'] == 25 ? 'selected' : ''; ?>>25</option>
                        <option value="50" <?php echo $pagination['per_page'] == 50 ? 'selected' : ''; ?>>50</option>
                    </select>
                </div>
            </form>
            <div class="mt-4">
                <a href="?" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition duration-200 text-center inline-block">
                    Clear Filters
                </a>
            </div>
        </div>

        <!-- Results Info -->
        <div class="mb-4">
            <div class="text-gray-700">
                Showing <?php echo count($actions); ?> of <?php echo $pagination['total_records']; ?> actions
            </div>
        </div>

        <!-- Actions Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-b">
                            Name
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-b">
                            Role
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-b">
                            Job Title
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-b">
                            Action Type
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-b">
                            Action Date
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php if (empty($actions)): ?>
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                No actions found matching your filters.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($actions as $action): ?>
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-4 py-3 text-sm text-gray-900">
                                    <?php echo htmlspecialchars($action->getUserName()); ?>
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                        <?php echo $action->getUserRole() === 'Admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800'; ?>">
                                        <?php echo htmlspecialchars($action->getUserRole()); ?>
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900">
                                    <?php echo htmlspecialchars($action->getJobTitle()); ?>
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
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
                                <td class="px-4 py-3 text-sm text-gray-900">
                                    <?php 
                                    $date = new DateTime($action->getActionDate());
                                    echo $date->format('M d, Y');
                                    ?>
                                    <span class="text-gray-500 text-xs block">
                                        <?php echo $date->format('h:i A'); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php 
        include __DIR__ . '/../../components/pagination.php';
        renderPagination($pagination, '', [
            'user_role' => $pagination['user_role_filter'] ?? '',
            'action_type' => $pagination['action_type_filter'] ?? '',
            'date_from' => $pagination['date_from'] ?? '',
            'date_to' => $pagination['date_to'] ?? '',
            'per_page' => $pagination['per_page'] ?? 10
        ]);
        ?>
    </div>
</div>

<?php require_once __DIR__ . '/../../layouts/admin_footer.php'; ?>
