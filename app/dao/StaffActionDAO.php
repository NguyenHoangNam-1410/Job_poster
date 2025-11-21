<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../models/StaffAction.php';

class StaffActionDAO
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->conn;
    }

    // CREATE - Log a staff action
    public function create(StaffAction $action)
    {
        $sql = "INSERT INTO STAFF_ACTIONS (user_id, job_id, action_type, action_date) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->db->error);
        }

        $userId = $action->getUserId();
        $jobId = $action->getJobId();
        $actionType = $action->getActionType();
        $actionDate = $action->getActionDate();

        $stmt->bind_param("iiss", $userId, $jobId, $actionType, $actionDate);

        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    // READ - Get all actions with filters and pagination
    public function getAll($userRoleFilter = '', $actionTypeFilter = '', $dateFrom = '', $dateTo = '', $limit = null, $offset = 0)
    {
        $sql = "SELECT sa.*, u.Name as user_name, u.Role as user_role, j.title as job_title 
                FROM STAFF_ACTIONS sa
                INNER JOIN USERS u ON sa.user_id = u.UID
                INNER JOIN JOBS j ON sa.job_id = j.id
                WHERE 1=1";

        $params = [];
        $types = '';

        // Filter by user role (Admin or Staff)
        if (!empty($userRoleFilter)) {
            $sql .= " AND u.Role = ?";
            $params[] = $userRoleFilter;
            $types .= 's';
        }

        // Filter by action type
        if (!empty($actionTypeFilter)) {
            $sql .= " AND sa.action_type = ?";
            $params[] = $actionTypeFilter;
            $types .= 's';
        }

        // Filter by date range
        if (!empty($dateFrom)) {
            $sql .= " AND DATE(sa.action_date) >= ?";
            $params[] = $dateFrom;
            $types .= 's';
        }

        if (!empty($dateTo)) {
            $sql .= " AND DATE(sa.action_date) <= ?";
            $params[] = $dateTo;
            $types .= 's';
        }

        // Order by newest first
        $sql .= " ORDER BY sa.action_date DESC, sa.id DESC";

        if ($limit !== null) {
            $sql .= " LIMIT ? OFFSET ?";
            $params[] = $limit;
            $params[] = $offset;
            $types .= 'ii';
        }

        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->db->error);
        }

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $actions = [];

        while ($row = $result->fetch_assoc()) {
            $action = new StaffAction(
                $row['id'],
                $row['user_id'],
                $row['job_id'],
                $row['action_type'],
                $row['action_date']
            );
            $action->setUserName($row['user_name']);
            $action->setUserRole($row['user_role']);
            $action->setJobTitle($row['job_title']);
            $actions[] = $action;
        }

        $stmt->close();
        return $actions;
    }

    // Get total count for pagination
    public function getTotalCount($userRoleFilter = '', $actionTypeFilter = '', $dateFrom = '', $dateTo = '')
    {
        $sql = "SELECT COUNT(*) as total 
                FROM STAFF_ACTIONS sa
                INNER JOIN USERS u ON sa.user_id = u.UID
                INNER JOIN JOBS j ON sa.job_id = j.id
                WHERE 1=1";

        $params = [];
        $types = '';

        // Filter by user role
        if (!empty($userRoleFilter)) {
            $sql .= " AND u.Role = ?";
            $params[] = $userRoleFilter;
            $types .= 's';
        }

        // Filter by action type
        if (!empty($actionTypeFilter)) {
            $sql .= " AND sa.action_type = ?";
            $params[] = $actionTypeFilter;
            $types .= 's';
        }

        // Filter by date range
        if (!empty($dateFrom)) {
            $sql .= " AND DATE(sa.action_date) >= ?";
            $params[] = $dateFrom;
            $types .= 's';
        }

        if (!empty($dateTo)) {
            $sql .= " AND DATE(sa.action_date) <= ?";
            $params[] = $dateTo;
            $types .= 's';
        }

        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->db->error);
        }

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $stmt->close();
        return $row['total'];
    }

    // Get unique action types for filter
    public function getUniqueActionTypes()
    {
        $sql = "SELECT DISTINCT action_type FROM STAFF_ACTIONS ORDER BY action_type";
        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->db->error);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $actionTypes = [];

        while ($row = $result->fetch_assoc()) {
            $actionTypes[] = $row['action_type'];
        }

        $stmt->close();
        return $actionTypes;
    }
}
