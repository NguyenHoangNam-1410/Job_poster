<?php
require_once __DIR__ . '/../dao/StatisticDAO.php';

class StatisticsService
{
    private $statisticDAO;

    public function __construct()
    {
        $this->statisticDAO = new StatisticDAO();
    }

    /**
     * Get count of users by role
     */
    public function getUserCountByRole($role)
    {
        return $this->statisticDAO->getUserCountByRole($role);
    }

    /**
     * Get job counts by status
     */
    public function getJobCountsByStatus()
    {
        $statuses = ['pending', 'approved', 'rejected', 'overdue', 'soft_deleted'];
        $counts = [];

        foreach ($statuses as $status) {
            $counts[$status] = $this->statisticDAO->getJobCountByStatus($status);
        }

        return $counts;
    }

    /**
     * Get total jobs count
     */
    public function getTotalJobsCount()
    {
        return $this->statisticDAO->getTotalJobsCount();
    }

    /**
     * Get top staff members with highest workload (most actions)
     */
    public function getTopStaffByWorkload($limit = 3)
    {
        return $this->statisticDAO->getTopStaffByWorkload($limit);
    }

    /**
     * Get top employers who posted most jobs (approved and overdue status only)
     */
    public function getTopEmployersByJobs($limit = 3)
    {
        return $this->statisticDAO->getTopEmployersByJobs($limit);
    }

    /**
     * Get top trending categories
     */
    public function getTopTrendingCategories($limit = 5)
    {
        return $this->statisticDAO->getTopTrendingCategories($limit);
    }

    /**
     * Get current month feedback count
     */
    public function getCurrentMonthFeedbackCount()
    {
        return $this->statisticDAO->getCurrentMonthFeedbackCount();
    }

    /**
     * Get all statistics at once
     */
    public function getAllStatistics()
    {
        return [
            'employer_count' => $this->getUserCountByRole('Employer'),
            'staff_count' => $this->getUserCountByRole('Staff'),
            'admin_count' => $this->getUserCountByRole('Admin'),
            'feedback_count' => $this->getCurrentMonthFeedbackCount(),
            'job_counts' => $this->getJobCountsByStatus(),
            'total_jobs' => $this->getTotalJobsCount(),
            'top_staff' => $this->getTopStaffByWorkload(3),
            'top_employers' => $this->getTopEmployersByJobs(3),
            'trending_categories' => $this->getTopTrendingCategories(5)
        ];
    }
}
