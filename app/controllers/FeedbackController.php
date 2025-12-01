<?php
require_once __DIR__ . '/../services/FeedbackService.php';

class FeedbackController
{
    private $feedbackService;

    public function __construct()
    {
        $this->feedbackService = new FeedbackService();
    }

    private function getCurrentUserId()
    {
        return $_SESSION['user']['id'] ?? null;
    }

    public function index()
    {
        // Get filter parameters
        $search = $_GET['search'] ?? '';
        $dateFrom = $_GET['date_from'] ?? '';
        $dateTo = $_GET['date_to'] ?? '';
        $per_page = isset($_GET['per_page']) ? (int) $_GET['per_page'] : 10;
        $current_page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

        // Validate per_page
        if (!in_array($per_page, [10, 25, 50])) {
            $per_page = 10;
        }

        $offset = ($current_page - 1) * $per_page;

        // Get total records and calculate pages
        $total_records = $this->feedbackService->getTotalCount($search, $dateFrom, $dateTo);
        $total_pages = ceil($total_records / $per_page);

        // Get feedbacks
        $feedbacks = $this->feedbackService->getAllFeedbacks($search, $dateFrom, $dateTo, $per_page, $offset);

        // Prepare pagination data
        $pagination = [
            'current_page' => $current_page,
            'per_page' => $per_page,
            'total_records' => $total_records,
            'total_pages' => $total_pages,
            'search' => $search,
            'date_from' => $dateFrom,
            'date_to' => $dateTo
        ];

        // Determine which layout to use based on user role
        $userRole = $_SESSION['user']['role'] ?? 'Guest';

        if ($userRole === 'Staff') {
            require_once __DIR__ . '/../views/staff/feedbacks/list.php';
        } else {
            require_once __DIR__ . '/../views/admin/feedbacks/list.php';
        }
    }

    public function delete($id)
    {
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

        try {
            $currentUserId = $this->getCurrentUserId();
            $result = $this->feedbackService->deleteFeedback($id, $currentUserId);

            if ($result) {
                if ($isAjax) {
                    header('Content-Type: application/json');
                    http_response_code(200);
                    echo json_encode([
                        'success' => true,
                        'message' => 'Feedback deleted successfully'
                    ]);
                    exit;
                } else {
                    header('Location: /Worknest/public/feedbacks?success=' . urlencode('Feedback deleted successfully'));
                    exit;
                }
            } else {
                throw new Exception('Delete operation failed');
            }
        } catch (Exception $e) {
            error_log("Delete feedback error: " . $e->getMessage());
            if ($isAjax) {
                header('Content-Type: application/json');
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
                exit;
            } else {
                header('Location: /Worknest/public/feedbacks?error=' . urlencode($e->getMessage()));
                exit;
            }
        }
    }

    public function myFeedbacks()
    {
        $currentUserId = $this->getCurrentUserId();
        if (!$currentUserId) {
            header('Location: /Worknest/public/login');
            exit;
        }

        // Get filter parameters
        $search = $_GET['search'] ?? '';
        $dateFrom = $_GET['date_from'] ?? '';
        $dateTo = $_GET['date_to'] ?? '';
        $per_page = isset($_GET['per_page']) ? (int) $_GET['per_page'] : 10;
        $current_page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

        // Validate per_page
        if (!in_array($per_page, [10, 25, 50])) {
            $per_page = 10;
        }

        $offset = ($current_page - 1) * $per_page;

        // Get total records and calculate pages
        $total_records = $this->feedbackService->getTotalCountForUser($currentUserId, $search, $dateFrom, $dateTo);
        $total_pages = ceil($total_records / $per_page);

        // Get feedbacks
        $feedbacks = $this->feedbackService->getFeedbacksByUser($currentUserId, $search, $dateFrom, $dateTo, $per_page, $offset);

        // Prepare pagination data
        $pagination = [
            'current_page' => $current_page,
            'per_page' => $per_page,
            'total_records' => $total_records,
            'total_pages' => $total_pages,
            'search' => $search,
            'date_from' => $dateFrom,
            'date_to' => $dateTo
        ];

        require_once __DIR__ . '/../views/employer/my_feedbacks/list.php';
    }

    public function createMyFeedback()
    {
        require_once __DIR__ . '/../views/employer/my_feedbacks/form.php';
    }

    public function storeMyFeedback()
    {
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $currentUserId = $this->getCurrentUserId();
                if (!$currentUserId) {
                    throw new Exception("User not logged in.");
                }

                $comments = $_POST['comments'] ?? '';

                if (empty($comments)) {
                    throw new Exception("Comments are required.");
                }

                if (strlen($comments) > 1000) {
                    throw new Exception("Feedback cannot exceed 1000 characters.");
                }

                $result = $this->feedbackService->createFeedback($currentUserId, $comments);

                if ($result) {
                    if ($isAjax) {
                        // AJAX request - return JSON
                        header('Content-Type: application/json');
                        http_response_code(200);
                        echo json_encode([
                            'success' => true,
                            'message' => 'Feedback added successfully'
                        ]);
                        exit;
                    } else {
                        // Regular form submission - redirect
                        header('Location: /Worknest/public/my-feedbacks?success=' . urlencode('Feedback added successfully'));
                        exit;
                    }
                } else {
                    throw new Exception("Failed to create feedback. Please try again.");
                }
            } catch (Exception $e) {
                if ($isAjax) {
                    // AJAX request - return JSON error
                    header('Content-Type: application/json');
                    http_response_code(400);
                    echo json_encode([
                        'success' => false,
                        'message' => $e->getMessage()
                    ]);
                    exit;
                } else {
                    // Regular form submission - redirect with error
                    header('Location: /Worknest/public/my-feedbacks/create?error=' . urlencode($e->getMessage()));
                    exit;
                }
            }
        }
    }
}
