<?php
require_once __DIR__ . '/../../app/dao/JobDAO.php';
header('Content-Type: application/json; charset=utf-8');
$q = trim($_GET['q'] ?? '');
$category_id = (int)($_GET['category_id'] ?? 0);
$location = trim($_GET['location'] ?? '');
$status = trim($_GET['status'] ?? '');
$page = (int)($_GET['page'] ?? 1);
$per_page = (int)($_GET['per_page'] ?? 9);
$dao = new JobDAO();
echo json_encode($dao->searchPublic($q, $category_id, $location, $status, $page, $per_page));
