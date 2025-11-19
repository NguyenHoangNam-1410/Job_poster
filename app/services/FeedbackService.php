<?php
require_once __DIR__ . '/../dao/FeedbackDAO.php';
require_once __DIR__ . '/../models/Feedback.php';

class FeedbackService {
    private $feedbackDAO;

    public function __construct() {
        $this->feedbackDAO = new FeedbackDAO();
    }

    /**
     * Get all feedbacks with filters and pagination
     */
    public function getAllFeedbacks($search, $dateFrom, $dateTo, $per_page, $offset) {
        return $this->feedbackDAO->getAll($search, $dateFrom, $dateTo, $per_page, $offset);
    }

    /**
     * Get total count for pagination
     */
    public function getTotalCount($search, $dateFrom, $dateTo) {
        return $this->feedbackDAO->getTotalCount($search, $dateFrom, $dateTo);
    }

    /**
     * Get feedback by ID
     */
    public function getFeedbackById($id) {
        return $this->feedbackDAO->getById($id);
    }

    /**
     * Create new feedback
     */
    public function createFeedback($userId, $comments) {
        if (empty($comments)) {
            throw new Exception("Comments are required.");
        }

        $feedback = new Feedback(
            null,
            $userId,
            $comments,
            date('Y-m-d H:i:s')
        );

        return $this->feedbackDAO->create($feedback);
    }

    /**
     * Delete feedback (only by the owner)
     */
    public function deleteFeedback($id, $currentUserId) {
        $feedback = $this->feedbackDAO->getById($id);
        
        if (!$feedback) {
            throw new Exception("Feedback not found.");
        }

        // Check if the current user is the owner
        if ($feedback->getUserId() != $currentUserId) {
            throw new Exception("You can only delete your own feedback.");
        }

        return $this->feedbackDAO->delete($id);
    }

    public function getTotalCountForUser($userId, $search, $dateFrom, $dateTo) {
        return $this->feedbackDAO->getTotalCountForUser($userId, $search, $dateFrom, $dateTo);
    }

    public function getFeedbacksByUser($userId, $search, $dateFrom, $dateTo, $per_page, $offset) {
        return $this->feedbackDAO->getFeedbacksByUser($userId, $search, $dateFrom, $dateTo, $per_page, $offset);
    }
}
