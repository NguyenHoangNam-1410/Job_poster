<?php

class HomeController
{
    public function index()
    {
        require_once '../app/views/public/home.php';
        exit;
    }

    public function adminIndex()
    {
        require_once '../app/views/statistics.php';
        exit;
    }

    public function staffIndex()
    {
        // Load DAOs for staff dashboard
        require_once '../app/dao/JobDAO.php';
        require_once '../app/dao/FeedbackDAO.php';
        require_once '../app/dao/StaffActionDAO.php';

        $jobDAO = new JobDAO();
        $feedbackDAO = new FeedbackDAO();
        $staffActionDAO = new StaffActionDAO();

        // Get dashboard statistics
        // Pending job requests (status = 'pending')
        $pendingJobs = $jobDAO->getAll('', '', '', 'pending', null, 0);
        $pendingJobsCount = count($pendingJobs);

        // Reviewed feedback/reports (get recent)
        $allFeedback = $feedbackDAO->getAll('', '', '', null, 0);
        $reviewedReportsCount = count($allFeedback);

        // Managed jobs (status = 'active')
        $activeJobs = $jobDAO->getAll('', '', '', 'active', null, 0);
        $managedJobsCount = count($activeJobs);

        // Recent job requests (pending, limit 5)
        $recentJobRequests = $jobDAO->getAll('', '', '', 'pending', 5, 0);

        // Recent feedback (limit 5)
        $recentFeedback = $feedbackDAO->getAll('', '', '', 5, 0);

        // Pass data to view
        $pageTitle = 'Staff Dashboard';
        require_once '../app/views/staff/home.php';
        exit;
    }

    public function employerIndex()
    {
        require_once '../app/views/employer/home.php';
        exit;
    }
}