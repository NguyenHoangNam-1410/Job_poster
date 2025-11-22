<?php
// filepath: /Worknest/public/ajax/jobs_filters.php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../config/db.php';

$pdo = (new Database())->conn; // mysqli

// Categories có ít nhất 1 job public (approved->recruiting hoặc recruiting)
$sqlCats = "
  SELECT c.id, c.category_name AS name
  FROM JOB_CATEGORIES c
  JOIN JOB_CATEGORY_MAP m ON m.category_id = c.id
  JOIN JOBS j ON j.id = m.job_id
  WHERE j.status IN ('approved','recruiting')
  GROUP BY c.id, c.category_name
  ORDER BY c.category_name ASC
";
$rsCats = $pdo->query($sqlCats);
$cats   = [];
if ($rsCats) {
  while ($r = $rsCats->fetch_assoc()) $cats[] = ['id'=>(int)$r['id'], 'name'=>$r['name']];
}

// Locations chỉ lấy job public
$sqlLocs = "
  SELECT DISTINCT j.location
  FROM JOBS j
  WHERE j.location IS NOT NULL AND j.location <> ''
    AND j.status IN ('approved','recruiting')
  ORDER BY j.location ASC
";
$rsLocs = $pdo->query($sqlLocs);
$locs   = [];
if ($rsLocs) {
  while ($r = $rsLocs->fetch_assoc()) $locs[] = $r['location'];
}

echo json_encode([
  'categories' => $cats,
  'locations'  => $locs,
  'statuses'   => ['recruiting','overdue'],
], JSON_UNESCAPED_UNICODE);
