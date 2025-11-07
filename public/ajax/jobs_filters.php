<?php
require_once __DIR__ . '/../../app/dao/JobDAO.php';
header('Content-Type: application/json; charset=utf-8');
$dao = new JobDAO();
echo json_encode($dao->getPublicFilters());
