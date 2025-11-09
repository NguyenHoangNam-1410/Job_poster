<?php
// filepath: /Job_poster/public/ajax/jobs_list.php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../app/dao/JobDAO.php';

$q          = trim($_GET['q'] ?? '');
$categoryId = (int)($_GET['category_id'] ?? 0);
$location   = trim($_GET['location'] ?? '');
$status     = trim($_GET['status'] ?? '');  // '', all, recruiting, overdue
$page       = (int)($_GET['page'] ?? 1);
$perPage    = (int)($_GET['per_page'] ?? 12);

$dao = new JobDAO();
$out = $dao->searchPublic($q, $categoryId, $location, $status, $page, $perPage);

echo json_encode([
  'total' => (int)($out['total'] ?? 0),
  'rows'  => $out['rows'] ?? [],
], JSON_UNESCAPED_UNICODE);
