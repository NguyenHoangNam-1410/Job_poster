<?php
// filepath: /Job_poster/public/ajax/jobs_list.php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../app/dao/JobDAO.php';

$q          = trim($_GET['q'] ?? '');
// Support both single category_id and multiple category_ids[]
$categoryIds = [];
if (isset($_GET['category_ids']) && is_array($_GET['category_ids'])) {
    // Multiple categories
    $categoryIds = array_filter(array_map('intval', $_GET['category_ids']), fn($id) => $id > 0);
} elseif (isset($_GET['category_id'])) {
    // Single category (backward compatibility)
    $catId = (int)$_GET['category_id'];
    if ($catId > 0) {
        $categoryIds = [$catId];
    }
}
// Support both single location/status and multiple location_ids[]/status_ids[]
$locationIds = [];
if (isset($_GET['location_ids']) && is_array($_GET['location_ids'])) {
    $locationIds = array_filter(array_map('trim', $_GET['location_ids']), fn($l) => $l !== '');
} elseif (isset($_GET['location'])) {
    $loc = trim($_GET['location']);
    if ($loc !== '') {
        $locationIds = [$loc];
    }
}

$statusIds = [];
if (isset($_GET['status_ids']) && is_array($_GET['status_ids'])) {
    $statusIds = array_filter(array_map('trim', $_GET['status_ids']), fn($s) => $s !== '');
} elseif (isset($_GET['status'])) {
    $st = trim($_GET['status']);
    if ($st !== '') {
        $statusIds = [$st];
    }
}

$page       = (int)($_GET['page'] ?? 1);
$perPage    = (int)($_GET['per_page'] ?? 12);

$dao = new JobDAO();
$out = $dao->searchPublic($q, $categoryIds, $locationIds, $statusIds, $page, $perPage);

echo json_encode([
  'total' => (int)($out['total'] ?? 0),
  'rows'  => $out['rows'] ?? [],
], JSON_UNESCAPED_UNICODE);
