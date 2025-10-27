<?php
require_once __DIR__ . '/../services/UserService.php';

class UserController {
    private $userService;
    
    public function __construct() {
        $this->userService = new UserService();
    }

    public function index() {
        $search = $_GET['search'] ?? '';
        $per_page = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;
        $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        if (!in_array($per_page, [10, 25, 50])) {
            $per_page = 10;
        }

        $offset = ($current_page - 1) * $per_page;

        $total_records = $this->userService->getTotalCount($search);
        $total_pages = ceil($total_records / $per_page);

        $users = $this->userService->getAllUsers($search, $per_page, $offset);

        $pagination = [
            'current_page' => $current_page,
            'per_page' => $per_page,
            'total_records' => $total_records,
            'total_pages' => $total_pages,
            'search' => $search
        ];

        require_once __DIR__ . '/../views/admin/users/list.php';
    }

    public function create() {
        $user = null;
        require_once __DIR__ . '/../views/admin/users/form.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $success = $this->userService->createUser($_POST);
            if ($success) {
                header('Location: /Job_poster/public/users');
                exit;
            } else {
                $error = "Failed to create user";
                $user = null;
                require_once __DIR__ . '/../views/admin/users/form.php';
            }
        }
    }

    public function edit($id) {
        $user = $this->userService->getUserById($id);
        require_once __DIR__ . '/../views/admin/users/form.php';
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $success = $this->userService->updateUser($id, $_POST);
            if ($success) {
                header('Location: /Job_poster/public/users');
                exit;
            }
        }
    }

    public function destroy($id) {
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

        try {
            $result = $this->userService->deleteUser($id);
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
                    'message' => 'Failed to delete user: ' . $e->getMessage()
                ]);
                exit;
            } else {
                header('Location: /Job_poster/public/users?error=' . urlencode($e->getMessage()));
                exit;
            }
        }
    }
}
