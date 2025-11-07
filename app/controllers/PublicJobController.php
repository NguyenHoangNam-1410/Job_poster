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
      'title'         => method_exists($m,'getTitle')        ? $m->getTitle()        : ($m->title ?? ''),
      'company_name'  => method_exists($m,'getCompanyName')  ? $m->getCompanyName()  : ($m->employer_name ?? ''),
      'location'      => method_exists($m,'getLocation')     ? $m->getLocation()     : ($m->location ?? ''),
      'description'   => method_exists($m,'getDescription')  ? $m->getDescription()  : ($m->description ?? ''),
      'requirements'  => method_exists($m,'getRequirements') ? $m->getRequirements() : ($m->requirements ?? ''),
      'salary'        => method_exists($m,'getSalary')       ? $m->getSalary()       : ($m->salary ?? ''),
      'deadline'      => method_exists($m,'getDeadline')     ? $m->getDeadline()     : ($m->deadline ?? ''),
      'status'        => method_exists($m,'getStatus')       ? $m->getStatus()       : ($m->status ?? ''),
      'created_at'    => method_exists($m,'getCreatedAt')    ? $m->getCreatedAt()    : ($m->created_at ?? ''),
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
