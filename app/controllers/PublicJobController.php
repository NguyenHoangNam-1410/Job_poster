<?php
require_once __DIR__ . '/../dao/JobDAO.php';

class PublicJobController {
  public function show(int $id){
    $dao = new JobDAO();
    $m = $dao->getById($id);
    if (!$m) {
      http_response_code(404);
      $job = null;
      require __DIR__ . '/../views/public/jobs/show.php';
      return;
    }

    $cats = [];
    if (method_exists($m, 'getCategories')) {
      foreach ($m->getCategories() as $c) $cats[] = $c['name'] ?? ($c['category_name'] ?? '');
    }

    $job = [
      'id'            => $id,
      'title'         => $m->getTitle(),
      'company_name'  => $m->getCompanyName() ?: $m->getEmployerName(),
      'location'      => $m->getLocation(),
      'description'   => $m->getDescription(),
      'requirements'  => $m->getRequirements(),
      'salary'        => $m->getSalary(),
      'deadline'      => $m->getDeadline(),
      'status'        => $m->getStatus(),
      'created_at'    => $m->getCreatedAt(),
      'categories'    => $cats,
      'logo'          => '',
      'website'       => ''
    ];

    require __DIR__ . '/../views/public/jobs/show.php';
  }

  public function index(){
    require __DIR__ . '/../views/public/jobs/jobslisting.php';
  }
}
