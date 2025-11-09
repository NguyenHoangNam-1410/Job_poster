<?php
$pageTitle = "Job Details";
$additionalCSS = ['/Job_poster/public/css/jobs-artistic.css'];
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
<style>
@keyframes float {
  0%, 100% { transform: translateY(0px) translateX(0px); }
  33% { transform: translateY(-20px) translateX(10px); }
  66% { transform: translateY(10px) translateX(-10px); }
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

.bg-animated {
  position: relative;
  background: linear-gradient(135deg, #fafafa 0%, #f5f5f5 100%);
}

.bg-animated::before {
  content: '';
  position: absolute;
  top: -50%;
  right: -10%;
  width: 500px;
  height: 500px;
  background: radial-gradient(circle, rgba(6, 136, 180, 0.08) 0%, transparent 70%);
  border-radius: 50%;
  animation: float 20s ease-in-out infinite;
  pointer-events: none;
}

.bg-animated::after {
  content: '';
  position: absolute;
  bottom: -30%;
  left: -5%;
  width: 400px;
  height: 400px;
  background: radial-gradient(circle, rgba(6, 136, 180, 0.05) 0%, transparent 70%);
  border-radius: 50%;
  animation: float 25s ease-in-out infinite reverse;
  pointer-events: none;
}

.content-wrapper {
  position: relative;
  z-index: 1;
}

.section-card {
  animation: fadeIn 0.6s ease-out forwards;
  opacity: 0;
}

.section-card:nth-child(1) { animation-delay: 0.1s; }
.section-card:nth-child(2) { animation-delay: 0.2s; }
.section-card:nth-child(3) { animation-delay: 0.3s; }
.section-card:nth-child(4) { animation-delay: 0.4s; }
.section-card:nth-child(5) { animation-delay: 0.5s; }
.section-card:nth-child(6) { animation-delay: 0.6s; }

/* Custom Scrollbar for Sidebar */
#rel-list::-webkit-scrollbar {
  width: 6px;
}

#rel-list::-webkit-scrollbar-track {
  background: #f3f4f6;
  border-radius: 3px;
}

#rel-list::-webkit-scrollbar-thumb {
  background: #0688B4;
  border-radius: 3px;
}

#rel-list::-webkit-scrollbar-thumb:hover {
  background: #056a8a;
}
</style>

<main class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 py-8 text-left bg-animated">
  <div class="content-wrapper">
    <nav class="text-sm text-gray-500 mb-6">
      <a href="<?= BASE_PUBLIC ?>/jobs" class="hover:text-gray-900 transition-colors">Jobs</a> <span class="mx-2">/</span> <span class="text-gray-900"><?= h($title) ?></span>
    </nav>

    <div class="mt-6 grid lg:grid-cols-3 gap-8">
      <section class="lg:col-span-2 space-y-8 text-left">
        <!-- Banner -->
        <div class="section-card overflow-hidden bg-gray-100 shadow-sm hover:shadow-md transition-shadow duration-300">
        <img src="<?= h($banner) ?>" alt="" class="w-full h-64 object-cover">
      </div>

        <!-- Header -->
        <header class="section-card text-left border-b border-gray-200 pb-6">
        <h1 class="text-4xl font-bold text-gray-900 leading-tight mb-3"><?= h($title) ?></h1>
        <p class="text-lg text-gray-600 mb-4"><?= h($company) ?> <span class="mx-2">•</span> <?= h($location) ?></p>
        <?php if (!empty($chips)): ?>
          <div class="flex flex-wrap gap-2">
            <?php foreach ($chips as $c): if(!$c) continue; ?>
              <span class="px-3 py-1 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300"><?= h($c) ?></span>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="text-gray-400 text-sm">No categories available</div>
        <?php endif; ?>
      </header>

        <?php if ($salary !== 'No information provided yet'): ?>
          <!-- Salary -->
          <div class="section-card border-l-4 border-teal-600 pl-6 py-2 bg-white shadow-sm hover:shadow-md transition-all duration-300" style="border-color: #0688B4;">
            <h3 class="text-sm font-semibold uppercase tracking-wide mb-2" style="color: #0688B4;">Salary</h3>
            <p class="text-2xl font-bold text-gray-900"><?= $salary ?></p>
          </div>
        <?php endif; ?>

        <!-- Job Description -->
        <div class="section-card bg-white p-6 shadow-sm hover:shadow-md transition-shadow duration-300 border-l-2" style="border-color: #0688B4;">
          <h2 class="text-xl font-bold text-gray-900 mb-4">Job Description</h2>
          <div class="text-gray-700 whitespace-pre-wrap leading-relaxed">
            <?php if ($desc===''): ?>
              <p class="text-gray-400">No information provided yet</p>
            <?php else: ?>
              <?= nl2br(h($desc)) ?>
            <?php endif; ?>
          </div>
        </div>

        <!-- Requirements -->
        <div class="section-card bg-white p-6 shadow-sm hover:shadow-md transition-shadow duration-300 border-l-2" style="border-color: #0688B4;">
          <h3 class="text-xl font-bold text-gray-900 mb-4">Requirements</h3>
          <?php if (!empty($reqs)): ?>
            <ul class="space-y-2 list-disc list-inside text-gray-700">
              <?php foreach ($reqs as $r): ?>
                <li><?= h($r) ?></li>
              <?php endforeach; ?>
            </ul>
          <?php else: ?>
            <p class="text-gray-400">No information provided yet</p>
          <?php endif; ?>
        </div>

        <!-- Quick Info -->
        <div class="section-card bg-white p-6 shadow-sm hover:shadow-md transition-shadow duration-300 border-l-2" style="border-color: #0688B4;">
          <h3 class="text-xl font-bold text-gray-900 mb-4">Quick Info</h3>
          <dl class="space-y-3">
            <div class="flex justify-between py-2 border-b border-gray-100">
              <dt class="text-sm font-medium text-gray-500">Company</dt>
              <dd class="text-sm font-semibold text-gray-900"><?= h($company) ?></dd>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-100">
              <dt class="text-sm font-medium text-gray-500">Location</dt>
              <dd class="text-sm font-semibold text-gray-900"><?= h($location) ?></dd>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-100">
              <dt class="text-sm font-medium text-gray-500">Deadline</dt>
              <dd class="text-sm font-semibold text-gray-900"><?= h($deadline) ?></dd>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-100">
              <dt class="text-sm font-medium text-gray-500">Posted</dt>
              <dd class="text-sm font-semibold text-gray-900"><?= h($posted) ?></dd>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-100">
              <dt class="text-sm font-medium text-gray-500">Status</dt>
              <dd class="text-sm font-semibold text-gray-900 capitalize"><?= h($statusLbl) ?></dd>
            </div>
          </dl>
          <?php if ($left !== null): ?>
            <div class="mt-4 p-3 text-center" style="background-color: <?= $left<=3 ? '#fee2e2' : 'rgba(6, 136, 180, 0.1)' ?>; border: 1px solid <?= $left<=3 ? '#fca5a5' : '#0688B4' ?>;">
              <p class="text-sm font-medium <?= $left<=3 ? 'text-red-600' : '' ?>" style="<?= $left<=3 ? '' : 'color: #0688B4;' ?>"><?= $left>=0 ? $left.' days left' : 'Closed' ?></p>
            </div>
          <?php else: ?>
            <div class="mt-4 p-3 bg-gray-50 border border-gray-200 text-center">
              <p class="text-sm font-medium text-gray-700">Closed</p>
            </div>
          <?php endif; ?>
          <a href="#" class="mt-5 w-full inline-flex justify-center text-white px-6 py-3 font-semibold hover:opacity-90 transition-all duration-300 shadow-md hover:shadow-lg" style="background-color: #0688B4;">
            Apply Now
          </a>
        </div>
      </section>

      <aside class="lg:col-span-1">
        <!-- Relevant Jobs -->
        <div class="section-card bg-white border border-gray-200 sticky top-6 shadow-sm hover:shadow-md transition-shadow duration-300" style="max-height: calc(100vh - 3rem); display: flex; flex-direction: column;">
          <div class="p-6 pb-4 border-b border-gray-100">
            <div class="flex items-center justify-between">
              <h3 class="text-lg font-bold text-gray-900">Relevant Jobs</h3>
              <a href="<?= BASE_PUBLIC ?>/jobs" class="text-sm font-medium hover:underline transition-colors" style="color: #0688B4;">See all →</a>
            </div>
          </div>
          <div id="rel-list" class="p-6 pt-4 space-y-3 overflow-y-auto" style="flex: 1; scrollbar-width: thin; scrollbar-color: #0688B4 #f3f4f6;"></div>
        </div>
      </aside>
    </div>
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
  const badge = st ? `<span class="inline-flex items-center px-2 py-0.5 text-[10px] font-medium ${statusClass(st)}">${capStatus(st)}</span>` : '';
  return `
    <a href="${jobLink(j.id)}"
       class="group block border border-gray-200 bg-white px-4 py-3 hover:border-gray-900 transition-all duration-200 opacity-0 translate-y-2">
      <div class="flex items-start justify-between gap-2 mb-1">
        <h4 class="text-sm font-semibold leading-snug line-clamp-2 group-hover:text-gray-900">${title}</h4>
        ${badge}
      </div>
      <p class="mt-1 text-xs text-gray-500">${comp}${comp&&loc?' • ':''}${loc}</p>
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
