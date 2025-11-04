<?php
require_once __DIR__ . '/../services/UserService.php';

class UserController {
    private $userService;
    
    public function __construct() {
        $this->userService = new UserService();
    }

    private function getCurrentUserId() {
        // TODO: Replace with actual session-based user ID when authentication is implemented
        // For now, return 1 as placeholder admin user
        return $_SESSION['user_id'] ?? 1;
    }

    public function index() {
        $search = $_GET['search'] ?? '';
        $roleFilter = $_GET['role'] ?? '';
        $per_page = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;
        $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        if (!in_array($per_page, [10, 25, 50])) {
            $per_page = 10;
        }

        $offset = ($current_page - 1) * $per_page;

        $total_records = $this->userService->getTotalCount($search, $roleFilter);
        $total_pages = ceil($total_records / $per_page);

        $users = $this->userService->getAllUsers($search, $roleFilter, $per_page, $offset);

        $pagination = [
            'current_page' => $current_page,
            'per_page' => $per_page,
            'total_records' => $total_records,
            'total_pages' => $total_pages,
            'search' => $search,
            'role_filter' => $roleFilter
        ];

        $currentUserId = $this->getCurrentUserId();

        require_once __DIR__ . '/../views/admin/users/list.php';
    }

    public function create() {
        $user = null;
        $error = null;
        require_once __DIR__ . '/../views/admin/users/form.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $currentUserId = $this->getCurrentUserId();
                $success = $this->userService->createUser($_POST, $currentUserId);
                if ($success) {
                    header('Location: /Job_poster/public/users');
                    exit;
                }
            } catch (Exception $e) {
                $error = $e->getMessage();
                $user = null;
                require_once __DIR__ . '/../views/admin/users/form.php';
            }
        }
    }

    public function edit($id) {
        try {
            $currentUserId = $this->getCurrentUserId();
            $user = $this->userService->getUserById($id);
            
            if (!$user) {
                header('Location: /Job_poster/public/users?error=' . urlencode('User not found'));
                exit;
            }

            // Check if current user can edit this user
            if (!$this->userService->canEdit($id, $currentUserId)) {
                header('Location: /Job_poster/public/users?error=' . urlencode('You cannot edit other administrators'));
                exit;
            }

            $error = null;
            require_once __DIR__ . '/../views/admin/users/form.php';
        } catch (Exception $e) {
            header('Location: /Job_poster/public/users?error=' . urlencode($e->getMessage()));
            exit;
        }
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $currentUserId = $this->getCurrentUserId();
                $success = $this->userService->updateUser($id, $_POST, $currentUserId);
                if ($success) {
                    header('Location: /Job_poster/public/users');
                    exit;
                }
            } catch (Exception $e) {
                $error = $e->getMessage();
                $user = $this->userService->getUserById($id);
                require_once __DIR__ . '/../views/admin/users/form.php';
            }
        }
    }

    public function destroy($id) {
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

        try {
            $currentUserId = $this->getCurrentUserId();
            $result = $this->userService->deleteUser($id, $currentUserId);
            if ($result) {
                if ($isAjax) {
                    header('Content-Type: application/json');
                    http_response_code(200);
                    echo json_encode([
                        'success' => true,
                        'message' => 'User deleted successfully'
                    ]);
                    exit;
                } else {
                    header('Location: /Job_poster/public/users');
                    exit;
                }
            } else {
                throw new Exception('Delete operation failed');
            }
        } catch (Exception $e) {
            error_log("Delete user error: " . $e->getMessage());
            if ($isAjax) {
                header('Content-Type: application/json');
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
                exit;
            } else {
                header('Location: /Job_poster/public/users?error=' . urlencode($e->getMessage()));
                exit;
            }
        }
    }
}
