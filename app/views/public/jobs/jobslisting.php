<?php
$pageTitle = "Browse Jobs";
$additionalCSS = [];
$additionalJS  = [];
require dirname(__DIR__, 2) . '/layouts/public_header.php';

const BASE_PUBLIC = '/Job_poster/public';
function route(string $r, array $qs = []): string {
  $qs = array_merge(['r' => $r], $qs);
  return BASE_PUBLIC . '/index.php?' . http_build_query($qs);
}
function job_link($id){
  return BASE_PUBLIC . '/jobs/show/' . (int)$id;
}
function ic($name,$class='w-4 h-4'){
  if (class_exists('Icons') && method_exists('Icons', $name)) return Icons::$name($class);
  $map=['search'=>'🔎','mapPin'=>'📍','briefcase'=>'💼','clock'=>'⏱️','filter'=>'🧰'];
  return '<span class="inline-block" aria-hidden="true" style="line-height:1">'.($map[$name]??'•').'</span>';
}

$q    = trim($_GET['q'] ?? ($_POST['q'] ?? ''));
$page = max(1, (int)($_GET['page'] ?? 1));

$filters    = $filters ?? [];
$categories = $filters['categories'] ?? $filters['cats'] ?? [];
$locations  = $filters['locations']  ?? $filters['locs'] ?? [];
$statuses   = $filters['statuses']   ?? $filters['stts'] ?? [];

if (!isset($result) || !is_array($result)) {
  $sample = [
    ['id'=>1,'title'=>'Senior Frontend Engineer','company'=>'TechCorp Solutions','location'=>'San Francisco, CA','type'=>'Full-time','posted_at'=>'2025-10-18 13:00:00','deadline'=>'2025-12-01','public_status'=>'recruiting','thumbnail_url'=>'images/jobs/frontend.jpg','tags'=>['React','Tailwind']],
    ['id'=>2,'title'=>'Junior Backend Developer','company'=>'StartupXYZ','location'=>'Remote','type'=>'Full-time','posted_at'=>'2025-10-19 10:00:00','deadline'=>'2025-11-30','public_status'=>'recruiting','thumbnail_url'=>'images/jobs/backend.jpg','tags'=>['Node.js','SQL']],
    ['id'=>3,'title'=>'DevOps Engineer','company'=>'CloudTech Systems','location'=>'Singapore','type'=>'Full-time','posted_at'=>'2025-10-21 09:30:00','deadline'=>'2025-11-15','public_status'=>'overdue','thumbnail_url'=>'images/jobs/devops.jpg','tags'=>['AWS','CI/CD']],
  ];
  $result = ['total'=>count($sample),'rows'=>$sample];
}
$rows  = $result['rows'] ?? [];
$total = (int)($result['total'] ?? count($rows));

function status_badge_class($st){
  switch ($st) {
    case 'recruiting':
      return 'bg-emerald-100 text-emerald-700 border-emerald-200';
    case 'overdue':
      return 'bg-rose-100 text-rose-700 border-rose-200';
    default:
      return 'bg-gray-100 text-gray-700 border-gray-200';
  }
}
?>
<main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
  <div class="flex flex-wrap items-center gap-3">
    <form action="<?= htmlspecialchars(BASE_PUBLIC . '/index.php') ?>" method="GET" class="w-full">
      <input type="hidden" name="r" value="/jobs">
      <div class="relative">
        <input id="q" name="q" value="<?= htmlspecialchars($q) ?>" type="text" placeholder="Search job title, company, keywords…"
               class="w-full rounded-2xl border-gray-300 pl-10 pr-4 py-3 focus:border-gray-500 focus:ring-gray-500">
        <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none"><?= ic('search','w-5 h-5') ?></span>
      </div>

      <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <select id="category" name="category" class="rounded-xl border-gray-300">
          <option value="">All categories</option>
          <?php foreach ($categories as $c): ?>
            <option value="<?= htmlspecialchars($c['id']) ?>"><?= htmlspecialchars($c['name']) ?></option>
          <?php endforeach; ?>
        </select>

        <select id="location" name="location" class="rounded-xl border-gray-300">
          <option value="">All locations</option>
          <?php foreach ($locations as $l): ?>
            <option value="<?= htmlspecialchars($l) ?>"><?= htmlspecialchars($l) ?></option>
          <?php endforeach; ?>
        </select>

        <select id="status" name="status" class="rounded-xl border-gray-300">
          <option value="">All statuses</option>
          <?php
            $statuses = $statuses ?: ['all','recruiting','overdue'];
            foreach ($statuses as $s):
          ?>
            <option value="<?= htmlspecialchars($s) ?>"><?= $s==='recruiting'?'Recruiting':($s==='overdue'?'Overdue':ucfirst(htmlspecialchars($s))) ?></option>
          <?php endforeach; ?>
        </select>

        <div class="h-10"></div>
      </div>

      <div id="activeBar" class="mt-3 flex flex-wrap items-center gap-3">
        <div id="activeBadges" class="flex flex-wrap items-center gap-2"></div>
        <a id="clearAll" href="<?= htmlspecialchars(route('/jobs')) ?>" class="hidden text-sm text-gray-600 hover:underline">✕ Clear all</a>
        <span id="activeCount" class="ml-auto text-sm text-gray-500 hidden"></span>
      </div>
    </form>
  </div>

  <div class="mt-6 flex items-center justify-between">
    <h2 id="count" class="text-lg text-gray-700">Showing <?= count($rows) ?> jobs</h2>
    <div id="pageInfo" class="text-sm text-gray-500">Page 1</div>
  </div>

  <div id="grid" class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
    <?php foreach ($rows as $job): ?>
      <?php
        $thumb = $job['thumbnail_url'] ?: 'images/jobs/placeholder.jpg';
        $posted = isset($job['posted_at']) ? strtotime($job['posted_at']) : time();
        $st = $job['public_status'] ?? ($job['status'] ?? '');
        $label = $st === 'recruiting' ? 'Recruiting' : ucfirst($st);
      ?>
      <article class="group overflow-hidden rounded-2xl bg-white shadow border border-gray-100 hover:shadow-md transition">
        <a href="<?= htmlspecialchars(job_link($job['id'])) ?>">
          <div class="aspect-[16/9] bg-gray-100 overflow-hidden">
            <img src="<?= htmlspecialchars($thumb) ?>" alt="<?= htmlspecialchars($job['title']) ?>"
                 class="h-full w-full object-cover group-hover:scale-[1.02] transition" loading="lazy">
          </div>
        </a>
        <div class="p-4">
          <div class="flex items-start justify-between gap-3">
            <h3 class="text-base font-semibold leading-tight line-clamp-2"><?= htmlspecialchars($job['title']) ?></h3>
            <?php if (!empty($job['type'])): ?>
              <span class="text-xs rounded-full bg-gray-100 px-2 py-1 whitespace-nowrap"><?= htmlspecialchars($job['type']) ?></span>
            <?php endif; ?>
          </div>
          <p class="mt-1 text-sm text-gray-600"><?= htmlspecialchars($job['company'] ?? '') ?></p>
          <div class="mt-3 flex flex-wrap items-center gap-3 text-sm text-gray-700">
            <span class="inline-flex items-center gap-1">📍<?= htmlspecialchars($job['location'] ?? '') ?></span>
            <span class="inline-flex items-center gap-1">⏱️<?= date('M j, Y', $posted) ?></span>
            <?php if ($st): ?>
              <span class="inline-flex items-center gap-1 rounded-full border px-2 py-0.5 text-xs font-medium <?= status_badge_class($st) ?>"><?= htmlspecialchars($label) ?></span>
            <?php endif; ?>
          </div>
          <?php if (!empty($job['tags'])): ?>
            <div class="mt-3 flex flex-wrap gap-2">
              <?php foreach ($job['tags'] as $tg): ?>
                <span class="inline-flex items-center rounded-full border border-gray-200 px-2.5 py-1 text-xs font-medium text-gray-700 bg-gray-50"><?= htmlspecialchars($tg) ?></span>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
          <div class="mt-4 flex justify-between items-center">
            <span class="text-xs text-gray-500"><?= htmlspecialchars(($job['field']??'') . (($job['field']??'') && ($job['expertise']??'')?' • ':'') . ($job['expertise']??'')) ?></span>
            <a href="<?= htmlspecialchars(job_link($job['id'])) ?>" class="text-indigo-600 hover:underline">View details</a>
          </div>
        </div>
      </article>
    <?php endforeach; ?>
  </div>

  <nav id="pager" class="mt-6 flex items-center justify-center gap-2" aria-label="Pagination"></nav>
</main>

<script>
const BASE='<?= BASE_PUBLIC ?>';
const elQ=document.getElementById('q');
const elCat=document.getElementById('category');
const elLoc=document.getElementById('location');
const elSt=document.getElementById('status');
const grid=document.getElementById('grid');
const count=document.getElementById('count');
const pager=document.getElementById('pager');
const pageInfo=document.getElementById('pageInfo');
const clearAll=document.getElementById('clearAll');
const activeBadges=document.getElementById('activeBadges');
const activeCount=document.getElementById('activeCount');

let page=1, perPage=12, lastTotal=0, typingTimer=0;

async function loadFilters(){
  try{
    const r=await fetch(`${BASE}/ajax/jobs_filters.php`);
    const d=await r.json() || {};
    const cats = d.categories ?? d.cats ?? [];
    const locs = d.locations  ?? d.locs ?? [];
    const stts = d.statuses   ?? d.stts ?? ['all','recruiting','overdue'];
    elCat.innerHTML = '<option value="">All categories</option>' + cats.map(c=>`<option value="${c.id}">${escapeHtml(c.name)}</option>`).join('');
    elLoc.innerHTML = '<option value="">All locations</option>' + locs.map(l=>`<option value="${escapeHtml(l)}">${escapeHtml(l)}</option>`).join('');
    elSt.innerHTML  = '<option value="">All statuses</option>'   + stts.map(s=>`<option value="${s}">${capStatus(s)}</option>`).join('');
  }catch(e){}
}

function makeURL(){
  const u=new URL(`${BASE}/ajax/jobs_list.php`, window.location.origin);
  if(elQ.value.trim()) u.searchParams.set('q', elQ.value.trim());
  if(elCat.value) u.searchParams.set('category_id', elCat.value);
  if(elLoc.value) u.searchParams.set('location', elLoc.value);
  if(elSt.value) u.searchParams.set('status', elSt.value);
  u.searchParams.set('page', page);
  u.searchParams.set('per_page', perPage);
  return u.toString();
}

async function loadJobs(){
  try{
    const r=await fetch(makeURL());
    const d=await r.json();
    lastTotal = parseInt(d.total||0,10);
    count.textContent = `Showing ${(d.rows||[]).length} of ${lastTotal} jobs`;
    grid.innerHTML = (d.rows||[]).map(card).join('') || `<div class="col-span-full text-center text-gray-500 border border-dashed rounded-2xl py-12">No jobs found</div>`;
    pageInfo.textContent = `Page ${page}`;
    renderPager();
    renderBadges();
  }catch(e){}
}

function card(job){
  const thumb = job.thumbnail_url || 'images/jobs/placeholder.jpg';
  const posted = job.posted_at ? formatDate(job.posted_at) : '';
  const st = job.public_status || job.status || '';
  const tags = (job.tags||[]).map(t=>`<span class="inline-flex items-center rounded-full border border-gray-200 px-2.5 py-1 text-xs font-medium text-gray-700 bg-gray-50">${escapeHtml(t)}</span>`).join('');
  const link = `${BASE}/jobs/show/${encodeURIComponent(job.id)}`;
  return `
    <article class="group overflow-hidden rounded-2xl bg-white shadow border border-gray-100 hover:shadow-md transition">
      <a href="${link}">
        <div class="aspect-[16/9] bg-gray-100 overflow-hidden">
          <img src="${escapeAttr(thumb)}" alt="${escapeAttr(job.title)}" class="h-full w-full object-cover group-hover:scale-[1.02] transition" loading="lazy">
        </div>
      </a>
      <div class="p-4">
        <div class="flex items-start justify-between gap-3">
          <h3 class="text-base font-semibold leading-tight line-clamp-2">${escapeHtml(job.title)}</h3>
          ${job.type ? `<span class="text-xs rounded-full bg-gray-100 px-2 py-1 whitespace-nowrap">${escapeHtml(job.type)}</span>` : ``}
        </div>
        <p class="mt-1 text-sm text-gray-600">${escapeHtml(job.company||'')}</p>
        <div class="mt-3 flex flex-wrap items-center gap-3 text-sm text-gray-700">
          <span class="inline-flex items-center gap-1">📍${escapeHtml(job.location||'')}</span>
          ${posted?`<span class="inline-flex items-center gap-1">⏱️${posted}</span>`:''}
          ${st?`<span class="inline-flex items-center gap-1 rounded-full border px-2 py-0.5 text-xs font-medium ${statusClass(st)}">${capStatus(st)}</span>`:''}
        </div>
        ${tags?`<div class="mt-3 flex flex-wrap gap-2">${tags}</div>`:''}
        <div class="mt-4 flex justify-between items-center">
          <span class="text-xs text-gray-500">${escapeHtml((job.field||''))}${job.field&&job.expertise?' • ':''}${escapeHtml(job.expertise||'')}</span>
          <a href="${link}" class="text-indigo-600 hover:underline">View details</a>
        </div>
      </div>
    </article>
  `;
}

function renderPager(){
  const totalPages = Math.max(1, Math.ceil(lastTotal / perPage));
  const prevDisabled = page<=1 ? 'opacity-50 pointer-events-none' : '';
  const nextDisabled = page>=totalPages ? 'opacity-50 pointer-events-none' : '';
  pager.innerHTML = `
    <a class="px-3 py-2 rounded-lg border border-gray-200 text-sm text-gray-700 hover:bg-gray-50 ${prevDisabled}" href="#" data-page="${page-1}">Prev</a>
    <span class="px-3 py-2 rounded-lg bg-gray-900 text-white text-sm">${page}</span>
    <a class="px-3 py-2 rounded-lg border border-gray-200 text-sm text-gray-700 hover:bg-gray-50 ${nextDisabled}" href="#" data-page="${page+1}">Next</a>
  `;
  pager.querySelectorAll('a[data-page]').forEach(a=>{
    a.addEventListener('click',e=>{
      e.preventDefault();
      const p = parseInt(a.getAttribute('data-page')||'1',10);
      const totalPages = Math.max(1, Math.ceil(lastTotal / perPage));
      if (p>=1 && p<=totalPages) { page = p; loadJobs(); window.scrollTo({top:0, behavior:'smooth'}); }
    });
  });
}

function statusClass(st){
  switch(st){
    case 'recruiting': return 'bg-emerald-100 text-emerald-700 border-emerald-200';
    case 'overdue':    return 'bg-rose-100 text-rose-700 border-rose-200';
    default:           return 'bg-gray-100 text-gray-700 border-gray-200';
  }
}
function capStatus(s){
  return s==='recruiting' ? 'Recruiting'
       : s==='overdue'    ? 'Overdue'
       : (s||'').charAt(0).toUpperCase() + (s||'').slice(1);
}
function formatDate(iso){ const d = new Date(iso.replace(' ','T')); return d.toLocaleDateString('en-US',{month:'short',day:'numeric',year:'numeric'}); }
function escapeHtml(s){ return (s??'').toString().replace(/[&<>"']/g,m=>({ '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;' }[m])); }
function escapeAttr(s){ return escapeHtml(s); }

function renderBadges(){
  const items=[];
  if(elQ.value.trim()){ items.push({k:'q', label:elQ.value.trim()}); }
  if(elCat.value){ items.push({k:'category', label:elCat.options[elCat.selectedIndex].text}); }
  if(elLoc.value){ items.push({k:'location', label:elLoc.options[elLoc.selectedIndex].text}); }
  if(elSt.value){ items.push({k:'status', label:capStatus(elSt.value)}); }

  activeBadges.innerHTML = items.map(it=>`
    <button type="button" class="inline-flex items-center gap-2 rounded-xl border px-3 py-1.5 text-sm text-gray-700 bg-gray-50 hover:bg-gray-100"
            data-k="${it.k}">
      <span>${escapeHtml(it.label)}</span>
      <span aria-hidden="true">✕</span>
    </button>
  `).join('');

  activeBadges.querySelectorAll('button[data-k]').forEach(b=>{
    b.addEventListener('click',()=>{
      const k=b.getAttribute('data-k');
      if(k==='q') elQ.value='';
      if(k==='category') elCat.value='';
      if(k==='location') elLoc.value='';
      if(k==='status') elSt.value='';
      page=1;
      loadJobs();
    });
  });

  const n = items.length;
  clearAll.classList.toggle('hidden', n===0);
  activeCount.classList.toggle('hidden', n===0);
  if(n>0) activeCount.textContent = `${n} filters active`;
}

function resetAndLoad(){ page=1; loadJobs(); }

document.addEventListener('DOMContentLoaded', async () => {
  await loadFilters();
  await loadJobs();

  [elCat, elLoc, elSt].forEach(el=>{
    el.addEventListener('change', ()=>{ resetAndLoad(); });
  });

  elQ.addEventListener('keydown', e => { if (e.key === 'Enter') { e.preventDefault(); resetAndLoad(); }});
  elQ.addEventListener('input', () => {
    if(typingTimer) clearTimeout(typingTimer);
    typingTimer = setTimeout(()=>{ resetAndLoad(); }, 400);
  });

  clearAll.addEventListener('click', e => {
    e.preventDefault();
    elQ.value=''; elCat.value=''; elLoc.value=''; elSt.value='';
    resetAndLoad();
  });
});
</script>

<?php require dirname(__DIR__, 2) . '/layouts/public_footer.php'; ?>
