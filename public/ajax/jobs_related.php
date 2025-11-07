<?php
require_once __DIR__ . '/../../app/dao/JobDAO.php';
header('Content-Type: application/json; charset=utf-8');

$job_id = (int)($_GET['job_id'] ?? 0);
$limit = (int)($_GET['limit'] ?? 12);

if ($job_id <= 0) {
    echo json_encode([]);
    exit;
}

$result = jp_get_related_public_jobs($job_id, $limit);
echo json_encode($result);
