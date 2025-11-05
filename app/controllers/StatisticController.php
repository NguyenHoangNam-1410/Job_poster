<?php
require_once __DIR__ . '/../services/StatisticsService.php';

class StatisticController {
    private $statisticsService;
    
    public function __construct() {
        $this->statisticsService = new StatisticsService();
    }

    public function index() {
        try {
            // Get all statistics
            $stats = $this->statisticsService->getAllStatistics();
            
            require_once __DIR__ . '/../views/admin/statistics/list.php';
        } catch (Exception $e) {
            $error = $e->getMessage();
            error_log("Statistics error: " . $error);
            require_once __DIR__ . '/../views/admin/statistics/list.php';
        }
    }
}
