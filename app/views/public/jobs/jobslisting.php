<?php
// app/views/public/jobs/index.php
$pageTitle = "Browse Jobs";
$additionalCSS = [];
$additionalJS  = [];
require dirname(__DIR__, 2) . '/layouts/public_header.php';

/* ================== ROUTE HELPER ================== */
const BASE_PUBLIC = '/Job_poster/public';
function route(string $r, array $qs = []): string {
  $qs = array_merge(['r' => $r], $qs);
  return BASE_PUBLIC . '/index.php?' . http_build_query($qs);
}
function job_link($id){ return route('/jobs/show', ['id' => $id]); }
/* =================================================== */

function ic($name,$class='w-4 h-4'){
  if (class_exists('Icons') && method_exists('Icons', $name)) return Icons::$name($class);
  $map=['search'=>'🔎','mapPin'=>'📍','briefcase'=>'💼','clock'=>'⏱️','filter'=>'🧰'];
  return '<span class="inline-block" aria-hidden="true">'.($map[$name]??'•').'</span>';
}

// Sample nếu controller chưa truyền
if (!isset($jobs) || !is_array($jobs)) {
  $jobs = [
    ['id'=>1,'title'=>'Senior Frontend Engineer','company'=>'TechCorp Solutions','location'=>'San Francisco, CA','type'=>'Full-time','field'=>'Software','expertise'=>'Senior','posted_at'=>'2025-10-18 13:00:00','thumbnail_url'=>'images/jobs/frontend.jpg','tags'=>['React','Tailwind']],
    ['id'=>2,'title'=>'Junior Backend Developer','company'=>'StartupXYZ','location'=>'Remote','type'=>'Full-time','field'=>'Engineering','expertise'=>'Junior','posted_at'=>'2025-10-19 10:00:00','thumbnail_url'=>'images/jobs/backend.jpg','tags'=>['Node.js','SQL']],
    ['id'=>3,'title'=>'DevOps Engineer','company'=>'CloudTech Systems','location'=>'Singapore','type'=>'Full-time','field'=>'Engineering','expertise'=>'Mid','posted_at'=>'2025-10-21 09:30:00','thumbnail_url'=>'images/jobs/devops.jpg','tags'=>['AWS','CI/CD']],
  ];
}

$q         = trim($_GET['q'] ?? '');
$field     = $_GET['field'] ?? '';
$expertise = $_GET['expertise'] ?? '';
$type      = $_GET['type'] ?? '';
$location  = trim($_GET['location'] ?? '');
$page      = (int)($_GET['page'] ?? 1);
if ($page < 1) $page = 1;
?>
<main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">

  <!-- Search / Filter -->
  <div class="flex flex-wrap items-center gap-3">
    <form action="<?= htmlspecialchars(BASE_PUBLIC . '/index.php') ?>" method="GET" class="flex-1 min-w-[260px]">
      <input type="hidden" name="r" value="/jobs">

      <div class="relative">
        <input name="q" value="<?= htmlspecialchars($q) ?>" type="text" placeholder="Search jobs by title, company, or keywords…"
               class="w-full rounded-2xl border-gray-300 pl-10 pr-4 py-3 focus:border-gray-500 focus:ring-gray-500">
        <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none"><?= ic('search','w-5 h-5') ?></span>
      </div>

      <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <select name="field" class="rounded-xl border-gray-300">
          <?php foreach ([''=>'All','Engineering'=>'Engineering','Software'=>'Software','Design'=>'Design','Marketing'=>'Marketing','Data'=>'Data','Mobile'=>'Mobile'] as $v=>$label): ?>
            <option value="<?=htmlspecialchars($v)?>" <?=$field===$v?'selected':''?>><?=htmlspecialchars($label)?></option>
          <?php endforeach; ?>
        </select>

        <select name="expertise" class="rounded-xl border-gray-300">
          <?php foreach ([''=>'All','Intern'=>'Intern','Junior'=>'Junior','Mid'=>'Mid','Senior'=>'Senior','Lead'=>'Lead'] as $v=>$label): ?>
            <option value="<?=htmlspecialchars($v)?>" <?=$expertise===$v?'selected':''?>><?=htmlspecialchars($label)?></option>
          <?php endforeach; ?>
        </select>

        <select name="type" class="rounded-xl border-gray-300">
          <?php foreach ([''=>'All','Full-time'=>'Full-time','Part-time'=>'Part-time','Contract'=>'Contract','Internship'=>'Internship'] as $v=>$label): ?>
            <option value="<?=htmlspecialchars($v)?>" <?=$type===$v?'selected':''?>><?=htmlspecialchars($label)?></option>
          <?php endforeach; ?>
        </select>

        <input name="location" value="<?= htmlspecialchars($location) ?>" class="rounded-xl border-gray-300" placeholder="Location / Remote">
      </div>

      <?php if ($field || $expertise || $type || $location || $q): ?>
        <a href="<?= htmlspecialchars(route('/jobs')) ?>" class="inline-block mt-3 text-sm text-gray-600 hover:underline">✕ Clear all</a>
      <?php endif; ?>
    </form>
  </div>

  <h2 class="mt-6 text-lg text-gray-700">Showing <?= count($jobs) ?> jobs</h2>

  <!-- Cards -->
  <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
    <?php foreach ($jobs as $job): ?>
      <article class="group overflow-hidden rounded-2xl bg-white shadow border border-gray-100 hover:shadow-md transition">
        <a href="<?= htmlspecialchars(job_link($job['id'])) ?>">
          <div class="aspect-[16/9] bg-gray-100 overflow-hidden">
            <img src="<?= htmlspecialchars($job['thumbnail_url'] ?: 'images/jobs/placeholder.jpg') ?>" alt="<?= htmlspecialchars($job['title']) ?>"
                 class="h-full w-full object-cover group-hover:scale-[1.02] transition" loading="lazy">
          </div>
        </a>
        <div class="p-4">
          <div class="flex items-center justify-between">
            <h3 class="text-base font-semibold leading-tight line-clamp-2"><?= htmlspecialchars($job['title']) ?></h3>
            <span class="text-xs rounded-full bg-gray-100 px-2 py-1"><?= htmlspecialchars($job['type']) ?></span>
          </div>
          <p class="mt-1 text-sm text-gray-600"><?= htmlspecialchars($job['company']) ?></p>
          <div class="mt-3 flex flex-wrap items-center gap-3 text-sm text-gray-700">
            <span class="inline-flex items-center gap-1"><?= ic('mapPin') ?><?= htmlspecialchars($job['location']) ?></span>
            <span class="inline-flex items-center gap-1"><?= ic('clock') ?><?= date('M j, Y', strtotime($job['posted_at'] ?? 'now')) ?></span>
          </div>
          <?php if (!empty($job['tags'])): ?>
            <div class="mt-3 flex flex-wrap gap-2">
              <?php foreach ($job['tags'] as $tg): ?>
                <span class="inline-flex items-center rounded-full border border-gray-200 px-2.5 py-1 text-xs font-medium text-gray-700 bg-gray-50"><?= htmlspecialchars($tg) ?></span>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
          <div class="mt-4 flex justify-between items-center">
            <span class="text-xs text-gray-500"><?= htmlspecialchars(($job['field']??'') . ' • ' . ($job['expertise']??'')) ?></span>
            <a href="<?= htmlspecialchars(job_link($job['id'])) ?>" class="text-sm font-medium text-gray-900 hover:underline">View details →</a>
          </div>
        </div>
      </article>
    <?php endforeach; ?>
  </div>

  <nav class="mt-6 flex items-center justify-center gap-2" aria-label="Pagination">
    <a class="px-3 py-2 rounded-lg border border-gray-200 text-sm text-gray-700 hover:bg-gray-50"
       href="<?= htmlspecialchars(route('/jobs', ['page'=>max(1,$page-1)])) ?>">Prev</a>
    <a class="px-3 py-2 rounded-lg bg-gray-900 text-white text-sm"
       href="<?= htmlspecialchars(route('/jobs', ['page'=>$page])) ?>"><?= (int)$page ?></a>
    <a class="px-3 py-2 rounded-lg border border-gray-200 text-sm text-gray-700 hover:bg-gray-50"
       href="<?= htmlspecialchars(route('/jobs', ['page'=>$page+1])) ?>">Next</a>
  </nav>
</main>

<!-- Auto filter script -->
<script>
document.addEventListener('DOMContentLoaded', () => {
  const form = document.querySelector('form[action*="jobs"]');
  if (!form) return;
  form.querySelectorAll('select, input[name="location"]').forEach(el => {
    el.addEventListener('change', () => form.submit());
  });
});
</script>

<?php require dirname(__DIR__, 2) . '/layouts/public_footer.php'; ?>

