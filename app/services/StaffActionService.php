<?php
require_once __DIR__ . '/../dao/StaffActionDAO.php';
require_once __DIR__ . '/../models/StaffAction.php';

class StaffActionService {
    private $staffActionDAO;

    public function __construct() {
        $this->staffActionDAO = new StaffActionDAO();
    }

    /**
     * Log a staff action
     * @param int $userId - The user ID performing the action
     * @param int $jobId - The job ID being affected
     * @param string $actionType - Type of action (e.g., 'create', 'update', 'delete', 'status_change')
     * @return bool
     */
    public function logAction($userId, $jobId, $actionType) {
        $action = new StaffAction(
            null,
            $userId,
            $jobId,
            $actionType,
            date('Y-m-d H:i:s')
        );

        return $this->staffActionDAO->create($action);
    }

    /**
     * Get all actions with filters and pagination
     */
    public function getAllActions($userRoleFilter, $actionTypeFilter, $dateFrom, $dateTo, $per_page, $offset) {
        return $this->staffActionDAO->getAll($userRoleFilter, $actionTypeFilter, $dateFrom, $dateTo, $per_page, $offset);
    }

    /**
     * Get total count for pagination
     */
    public function getTotalCount($userRoleFilter, $actionTypeFilter, $dateFrom, $dateTo) {
        return $this->staffActionDAO->getTotalCount($userRoleFilter, $actionTypeFilter, $dateFrom, $dateTo);
    }

    /**
     * Get unique action types for filter dropdown
     */
    public function getUniqueActionTypes() {
        return $this->staffActionDAO->getUniqueActionTypes();
    }
}
