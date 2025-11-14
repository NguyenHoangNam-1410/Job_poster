<?php
require_once __DIR__ . '/../services/UserService.php';

class UserController {
    private $userService;
    
    public function __construct() {
        $this->userService = new UserService();
    }

    private function getCurrentUserId() {
        return $_SESSION['user']['id'] ?? null;
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

    public function profile() {
        $currentUserId = $this->getCurrentUserId();
        $user = $this->userService->getUserById($currentUserId);
        
        if (!$user) {
            header('Location: /Job_poster/public/401');
            exit;
        }

        $error = $_GET['error'] ?? null;
        $success = $_GET['success'] ?? null;
        
        require_once __DIR__ . '/../views/staff/profile/form.php';
    }

    public function updateProfile() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /Job_poster/public/profile');
            exit;
        }

        $currentUserId = $this->getCurrentUserId();
        
        try {
            $data = [
                'name' => trim($_POST['name'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'avatar' => null
            ];

            // Handle avatar upload
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['avatar'];
                
                // Validate file size (max 1MB)
                if ($file['size'] > 1048576) {
                    throw new Exception("Avatar image must be less than 1MB.");
                }

                // Validate file type
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mimeType = finfo_file($finfo, $file['tmp_name']);
                finfo_close($finfo);

                if (!in_array($mimeType, $allowedTypes)) {
                    throw new Exception("Invalid image format. Only JPEG, PNG, GIF, and WebP are allowed.");
                }

                // Create directory if not exists
                $uploadDir = __DIR__ . '/../../public/image/avatar/' . $currentUserId . '/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                // Generate unique filename
                $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $filename = 'avatar_' . time() . '.' . $extension;
                $uploadPath = $uploadDir . $filename;

                // Move uploaded file
                if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
                    throw new Exception("Failed to upload avatar image.");
                }

                // Set avatar path relative to public directory
                $data['avatar'] = '/Job_poster/public/image/avatar/' . $currentUserId . '/' . $filename;
            } else {
                // Keep existing avatar if no new file uploaded
                $currentUser = $this->userService->getUserById($currentUserId);
                $data['avatar'] = $currentUser->getAvatar();
            }

            // Handle password change if provided
            if (!empty($_POST['old_password']) || !empty($_POST['new_password']) || !empty($_POST['confirm_password'])) {
                $oldPassword = $_POST['old_password'] ?? '';
                $newPassword = $_POST['new_password'] ?? '';
                $confirmPassword = $_POST['confirm_password'] ?? '';

                if (empty($oldPassword)) {
                    throw new Exception("Current password is required to change password.");
                }

                $this->userService->updatePassword($currentUserId, $oldPassword, $newPassword, $confirmPassword);
            }

            // Update profile
            $this->userService->updateProfile($currentUserId, $data);

            // Get the referrer or default to profile page
            $referrer = $_POST['referrer'] ?? '/Job_poster/public/profile';
            header('Location: ' . $referrer . '?success=' . urlencode('Profile updated successfully'));
            exit;

        } catch (Exception $e) {
            header('Location: /Job_poster/public/profile?error=' . urlencode($e->getMessage()));
            exit;
        }
    }
}
