<?php
$pageTitle = "Job Details";
$additionalCSS = [];
$additionalJS  = [];
require dirname(__DIR__, 2) . '/layouts/public_header.php';

const BASE_PUBLIC = '/Job_poster/public';

function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function ph($v){ return ($v===null || $v==='') ? 'No information provided yet' : $v; }
function money_vn($n){
  if($n===null || $n==='') return 'No information provided yet';
  if(!is_numeric($n)) return h($n);
  return number_format((float)$n,0,'.',',').' VND';
}
function days_left($d){
  if(!$d) return null; $t=strtotime($d); if($t===false) return null;
  $today=strtotime(date('Y-m-d')); return (int)floor(($t-$today)/86400);
}
function capStatus($s){
  $s = strtolower((string)$s);
  if($s==='approved') $s='recruiting';
  if($s==='overdue') return 'Overdue';
  if($s==='recruiting') return 'Recruiting';
  return ucfirst($s);
}

if (!isset($job) || !is_array($job)) {
  http_response_code(404);
  echo '<main class="max-w-3xl mx-auto px-6 py-12 text-center"><h1 class="text-xl font-semibold">Job not found</h1><p class="mt-2 text-gray-600">No job data available.</p></main>';
  require dirname(__DIR__, 2) . '/layouts/public_footer.php';
  return;
}

$title      = $job['title'] ?? '';
$jobId      = (int)($job['id'] ?? 0);
$companyRaw = $job['company'] ?? ($job['company_name'] ?? ($job['employer_name'] ?? ''));
$company    = ph($companyRaw);
$location   = ph($job['location'] ?? '');
$salary     = money_vn($job['salary'] ?? null);
$deadlineRaw= $job['deadline'] ?? null;
$deadline   = $deadlineRaw ? date('M j, Y', strtotime($deadlineRaw)) : 'No information provided yet';
$postedRaw  = $job['created_at'] ?? ($job['posted_at'] ?? null);
$posted     = $postedRaw ? date('M j, Y', strtotime($postedRaw)) : 'No information provided yet';
$statusRaw  = $job['status'] ?? ($job['public_status'] ?? '');
$status     = strtolower((string)$statusRaw) === 'approved' ? 'recruiting' : ($statusRaw ?? '');
$statusLbl  = capStatus($status);
$logo       = !empty($job['logo']) ? $job['logo'] : '';
$banner     = !empty($job['banner']) ? $job['banner'] : ($logo ?: BASE_PUBLIC.'/images/headers/job.jpg');
$chips      = $job['categories'] ?? [];
$desc       = $job['description'] ?? '';
$reqStr     = $job['requirements'] ?? '';
$reqs       = [];
if ($reqStr!=='') {
  foreach (preg_split('/\r\n|\n|,/', (string)$reqStr) as $p){
    $p=trim($p); if($p!=='') $reqs[]=$p;
  }
}
$left = days_left($deadlineRaw);
?>
<main class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 py-8 text-left">
  <nav class="text-sm text-gray-600 mb-3">
    <a href="<?= BASE_PUBLIC ?>/jobs" class="hover:underline">Jobs</a> / <span><?= h($title) ?></span>
  </nav>

  <div class="mt-6 grid lg:grid-cols-3 gap-6">
    <section class="lg:col-span-2 space-y-6 text-left">
      <div class="rounded-2xl overflow-hidden border border-gray-200">
        <img src="<?= h($banner) ?>" alt="" class="w-full h-56 md:h-64 object-cover">
      </div>

      <header class="text-left">
        <h1 class="text-3xl font-bold"><?= h($title) ?></h1>
        <p class="mt-1 text-gray-700"><?= h($company) ?> • <?= h($location) ?></p>
        <?php if (!empty($chips)): ?>
          <div class="mt-2 flex flex-wrap gap-2">
            <?php foreach ($chips as $c): if(!$c) continue; ?>
              <span class="px-3 py-1 rounded-full text-sm bg-gray-100 border border-gray-200"><?= h($c) ?></span>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="mt-2 text-gray-500 text-sm">No information provided yet</div>
        <?php endif; ?>
      </header>

      <?php if ($salary !== 'No information provided yet'): ?>
        <div>
          <h3 class="text-lg font-semibold">Salary</h3>
          <p class="mt-1 text-gray-800 text-left"><?= $salary ?></p>
        </div>
      <?php endif; ?>

      <div>
        <h2 class="text-xl font-semibold">Job Description</h2>
        <div class="mt-2 text-gray-800 whitespace-pre-wrap text-left leading-relaxed">
          <?php if ($desc===''): ?>
            <p>No information provided yet</p>
          <?php else: ?>
            <?= nl2br(h($desc)) ?>
          <?php endif; ?>
        </div>
      </div>

      <div>
        <h3 class="text-lg font-semibold">Requirements</h3>
        <?php if (!empty($reqs)): ?>
          <ul class="mt-2 list-disc ms-5 text-gray-800">
            <?php foreach ($reqs as $r): ?><li><?= h($r) ?></li><?php endforeach; ?>
          </ul>
        <?php else: ?>
          <p class="mt-2 text-gray-700">No information provided yet</p>
        <?php endif; ?>
      </div>

      <div class="rounded-2xl border border-gray-200 p-5">
        <h3 class="text-lg font-semibold">Quick info</h3>
        <dl class="mt-3 text-gray-700 grid sm:grid-cols-2 gap-y-2">
          <div class="flex justify-between sm:justify-start sm:gap-3"><dt class="font-medium">Company</dt><dd><?= h($company) ?></dd></div>
          <div class="flex justify-between sm:justify-start sm:gap-3"><dt class="font-medium">Location</dt><dd><?= h($location) ?></dd></div>
          <div class="flex justify-between sm:justify-start sm:gap-3"><dt class="font-medium">Deadline</dt><dd><?= h($deadline) ?></dd></div>
          <div class="flex justify-between sm:justify-start sm:gap-3"><dt class="font-medium">Posted</dt><dd><?= h($posted) ?></dd></div>
          <div class="flex justify-between sm:justify-start sm:gap-3"><dt class="font-medium">Status</dt><dd class="capitalize"><?= h($statusLbl) ?></dd></div>
        </dl>
        <?php if ($left !== null): ?>
          <p class="mt-3 text-sm <?= $left<=3?'text-red-600':'text-gray-600' ?>"><?= $left>=0 ? $left.' days left' : 'Closed' ?></p>
        <?php else: ?>
          <p class="mt-3 text-sm text-gray-600">Closed</p>
        <?php endif; ?>
        <a href="#" class="mt-5 w-full inline-flex justify-center rounded-xl bg-gray-900 text-white px-4 py-2 font-medium hover:bg-gray-800 transition">Apply Now</a>
      </div>
    </section>

    <aside class="lg:col-span-1">
      <div class="rounded-2xl border border-gray-200 p-5 sticky top-6">
        <div class="flex items-center justify-between">
          <h3 class="text-lg font-semibold">Relevant jobs</h3>
          <a href="<?= BASE_PUBLIC ?>/jobs" class="text-sm text-indigo-600 hover:underline">See all</a>
        </div>
        <div id="rel-list" class="mt-4 space-y-3"></div>
      </div>
    </aside>
  </div>
</main>

<script>
const BASE='<?= BASE_PUBLIC ?>';
const relWrap=document.getElementById('rel-list');
const jobId=<?= json_encode($jobId) ?>;

function jobLink(id){ return `${BASE}/jobs/show/${encodeURIComponent(id)}`; }
function escapeHtml(s){ return (s??'').toString().replace(/[&<>"']/g,m=>({ '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;' }[m])); }
function normStatus(s){
  s = String(s||'').toLowerCase();
  return s==='approved' ? 'recruiting' : s;
}
function capStatus(s){
  s = normStatus(s);
  return s==='recruiting' ? 'Recruiting'
       : s==='overdue'    ? 'Overdue'
       : (s||'').charAt(0).toUpperCase() + (s||'').slice(1);
}
function statusClass(st){
  st = normStatus(st);
  switch(st){
    case 'recruiting': return 'bg-emerald-100 text-emerald-700 border-emerald-200';
    case 'overdue':    return 'bg-rose-100 text-rose-700 border-rose-200';
    default:           return 'bg-gray-100 text-gray-700 border-gray-200';
  }
}
function relCard(j){
  const title = escapeHtml(j.title||'');
  const comp  = escapeHtml(j.company || j.company_name || j.employer_name || '');
  const loc   = escapeHtml(j.location||'');
  const st    = normStatus(j.public_status || j.status || '');
  const badge = st ? `<span class="inline-flex items-center gap-1 rounded-full border px-2 py-0.5 text-[11px] font-medium ${statusClass(st)}">${capStatus(st)}</span>` : '';
  return `
    <a href="${jobLink(j.id)}"
       class="group block rounded-xl border border-gray-200 bg-white px-4 py-3 hover:shadow-lg transition duration-300 will-change-transform transform hover:-translate-y-0.5 opacity-0 translate-y-2">
      <div class="flex items-start justify-between gap-2">
        <h4 class="text-sm font-semibold leading-snug line-clamp-2 group-hover:underline">${title}</h4>
        ${badge}
      </div>
      <p class="mt-1 text-xs text-gray-600">${comp}${comp&&loc?' • ':''}${loc}</p>
    </a>
  `;
}
async function loadRelated(){
  if(!jobId){ relWrap.innerHTML = '<div class="text-sm text-gray-500">No related jobs</div>'; return; }
  try{
    const url = `${BASE}/ajax/jobs_related.php?job_id=${encodeURIComponent(jobId)}&limit=10`;
    const r = await fetch(url);
    const d = await r.json();
    const rows = Array.isArray(d?.rows) ? d.rows : (Array.isArray(d) ? d : []);
    if(!rows.length){
      relWrap.innerHTML = '<div class="text-sm text-gray-500">No related jobs</div>';
      return;
    }
    relWrap.innerHTML = rows.map(relCard).join('');
    appearMotion(relWrap.querySelectorAll('.group'));
  }catch(e){
    relWrap.innerHTML = '<div class="text-sm text-gray-500">Failed to load related jobs</div>';
  }
}
function appearMotion(nodes){
  const io = new IntersectionObserver(entries=>{
    entries.forEach(en=>{
      if(en.isIntersecting){
        en.target.classList.add('opacity-100','translate-y-0');
        en.target.classList.remove('opacity-0','translate-y-2');
        io.unobserve(en.target);
      }
    });
  }, {threshold: 0.15});
  nodes.forEach(n=>{
    n.classList.add('transition','duration-500');
    io.observe(n);
  });
}
document.addEventListener('DOMContentLoaded', loadRelated);
</script>

<?php require dirname(__DIR__, 2) . '/layouts/public_footer.php'; ?>
