<?php
require_once __DIR__ . '/../services/StaffActionService.php';

class StaffActionController {
    private $staffActionService;
    
    public function __construct() {
        $this->staffActionService = new StaffActionService();
    }

    public function index() {
        // Get filter parameters
        $userRoleFilter = $_GET['user_role'] ?? '';
        $actionTypeFilter = $_GET['action_type'] ?? '';
        $dateFrom = $_GET['date_from'] ?? '';
        $dateTo = $_GET['date_to'] ?? '';
        $per_page = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;
        $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        // Validate per_page
        if (!in_array($per_page, [10, 25, 50])) {
            $per_page = 10;
        }

        // Validate user role filter
        if (!empty($userRoleFilter) && !in_array($userRoleFilter, ['Admin', 'Staff'])) {
            $userRoleFilter = '';
        }

        $offset = ($current_page - 1) * $per_page;

        // Get total records and calculate pages
        $total_records = $this->staffActionService->getTotalCount($userRoleFilter, $actionTypeFilter, $dateFrom, $dateTo);
        $total_pages = ceil($total_records / $per_page);

        // Get actions
        $actions = $this->staffActionService->getAllActions($userRoleFilter, $actionTypeFilter, $dateFrom, $dateTo, $per_page, $offset);

        // Get unique action types for filter
        $actionTypes = $this->staffActionService->getUniqueActionTypes();

        // Prepare pagination data
        $pagination = [
            'current_page' => $current_page,
            'per_page' => $per_page,
            'total_records' => $total_records,
            'total_pages' => $total_pages,
            'user_role_filter' => $userRoleFilter,
            'action_type_filter' => $actionTypeFilter,
            'date_from' => $dateFrom,
            'date_to' => $dateTo
        ];

        require_once __DIR__ . '/../views/admin/staff_actions/list.php';
    }
}
