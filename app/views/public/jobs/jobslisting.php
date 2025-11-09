<?php
$pageTitle = "Browse Jobs";
$additionalCSS = ['/Job_poster/public/css/jobs-artistic.css'];
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
<script>
document.body.classList.add('jobs-listing-page');
</script>
<main>
  <!-- Hero Section with Large Typography -->
  <section class="jobs-hero">
    <div class="jobs-hero-bg-text">JOBS</div>
    <h1 class="jobs-hero-title">FIND YOUR<br>DREAM JOB</h1>
    <p class="jobs-hero-subtitle">Discover opportunities that match your passion. Explore our curated collection of jobs from innovative companies.</p>
  </section>

  <!-- Artistic Search Section -->
  <section class="jobs-search-section">
    <div class="jobs-search-container">
      <form action="<?= htmlspecialchars(BASE_PUBLIC . '/index.php') ?>" method="GET" class="w-full">
        <input type="hidden" name="r" value="/jobs">
        <div class="relative mb-4">
          <input id="q" name="q" value="<?= htmlspecialchars($q) ?>" type="text" placeholder="Search job title, company, keywords…"
                 class="jobs-search-input">
          <span class="jobs-search-icon"><?= ic('search','w-6 h-6') ?></span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
          <select id="category" name="category" class="jobs-filter-select">
            <option value="">All categories</option>
            <?php foreach ($categories as $c): ?>
              <option value="<?= htmlspecialchars($c['id']) ?>"><?= htmlspecialchars($c['name']) ?></option>
            <?php endforeach; ?>
          </select>

          <select id="location" name="location" class="jobs-filter-select">
            <option value="">All locations</option>
            <?php foreach ($locations as $l): ?>
              <option value="<?= htmlspecialchars($l) ?>"><?= htmlspecialchars($l) ?></option>
            <?php endforeach; ?>
          </select>

          <select id="status" name="status" class="jobs-filter-select">
            <option value="">All statuses</option>
            <?php
              $statuses = $statuses ?: ['all','recruiting','overdue'];
              foreach ($statuses as $s):
            ?>
              <option value="<?= htmlspecialchars($s) ?>"><?= $s==='recruiting'?'Recruiting':($s==='overdue'?'Overdue':ucfirst(htmlspecialchars($s))) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </form>

      <div id="activeBar" class="jobs-active-filters">
        <div id="activeBadges" class="flex flex-wrap items-center gap-2"></div>
        <a id="clearAll" href="<?= htmlspecialchars(route('/jobs')) ?>" class="hidden text-sm" style="color: #0688B4; text-decoration: underline; margin-left: 1rem;">✕ Clear all</a>
        <span id="activeCount" class="ml-auto text-sm hidden" style="color: #4a4a4a;"></span>
      </div>
    </div>
  </section>

  <!-- Count Section -->
  <section class="jobs-count-section">
    <h2 id="count" class="jobs-count-text">Showing <?= count($rows) ?> jobs</h2>
    <div id="pageInfo" class="text-sm" style="color: #4a4a4a; margin-top: 0.5rem;">Page 1</div>
  </section>

  <!-- Jobs Grid -->
  <div id="grid" class="jobs-grid">
    <?php foreach ($rows as $job): ?>
      <?php
        $thumb = $job['thumbnail_url'] ?: 'images/jobs/placeholder.jpg';
        $posted = isset($job['posted_at']) ? strtotime($job['posted_at']) : time();
        $st = $job['public_status'] ?? ($job['status'] ?? '');
        $label = $st === 'recruiting' ? 'Recruiting' : ucfirst($st);
      ?>
      <a href="<?= htmlspecialchars(job_link($job['id'])) ?>" class="job-card-wrapper">
        <article class="job-card-artistic">
          <div class="job-card-image-wrapper">
            <img src="<?= htmlspecialchars($thumb) ?>" alt="<?= htmlspecialchars($job['title']) ?>"
                 class="job-card-image" loading="lazy">
            <?php if ($st): ?>
              <span class="job-card-status <?= htmlspecialchars($st) ?>"><?= htmlspecialchars($label) ?></span>
            <?php endif; ?>
          </div>
          <div class="job-card-content">
            <h3 class="job-card-title"><?= htmlspecialchars($job['title']) ?></h3>
            <p class="job-card-company"><?= htmlspecialchars($job['company'] ?? '') ?></p>
            <div class="job-card-meta">
              <div class="job-card-meta-item">
                <span>📍</span>
                <span><?= htmlspecialchars($job['location'] ?? '') ?></span>
              </div>
              <div class="job-card-meta-item">
                <span>⏱️</span>
                <span><?= date('M j, Y', $posted) ?></span>
              </div>
              <?php if (!empty($job['type'])): ?>
                <div class="job-card-meta-item">
                  <span>💼</span>
                  <span><?= htmlspecialchars($job['type']) ?></span>
                </div>
              <?php endif; ?>
            </div>
            <?php if (!empty($job['tags'])): ?>
              <div class="job-card-tags">
                <?php foreach ($job['tags'] as $tg): ?>
                  <span class="job-card-tag"><?= htmlspecialchars($tg) ?></span>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
            <div class="job-card-footer">
              <span class="text-xs"><?= htmlspecialchars(($job['field']??'') . (($job['field']??'') && ($job['expertise']??'')?' • ':'') . ($job['expertise']??'')) ?></span>
            </div>
          </div>
        </article>
      </a>
    <?php endforeach; ?>
  </div>

  <nav id="pager" class="jobs-pagination" aria-label="Pagination"></nav>
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
    count.textContent = `${(d.rows||[]).length} jobs found`;
    if((d.rows||[]).length === 0) {
      grid.innerHTML = `<div class="jobs-no-results"><h3 class="jobs-no-results-title">NO JOBS FOUND</h3><p class="jobs-no-results-text">Try adjusting your search or filters to find more opportunities.</p></div>`;
    } else {
      grid.innerHTML = (d.rows||[]).map(card).join('');
    }
    pageInfo.textContent = `Page ${page}`;
    renderPager();
    renderBadges();
  }catch(e){}
}

function card(job){
  const thumb = job.thumbnail_url || 'images/jobs/placeholder.jpg';
  const posted = job.posted_at ? formatDate(job.posted_at) : '';
  const st = job.public_status || job.status || '';
  const tags = (job.tags||[]).map(t=>`<span class="job-card-tag">${escapeHtml(t)}</span>`).join('');
  const link = `${BASE}/jobs/show/${encodeURIComponent(job.id)}`;
  const statusBadge = st ? `<span class="job-card-status ${st}">${capStatus(st)}</span>` : '';
  const typeMeta = job.type ? `<div class="job-card-meta-item"><span>💼</span><span>${escapeHtml(job.type)}</span></div>` : '';
  const fieldInfo = `${escapeHtml(job.field||'')}${job.field&&job.expertise?' • ':''}${escapeHtml(job.expertise||'')}`;
  
  return `
    <a href="${link}" class="job-card-wrapper">
      <article class="job-card-artistic">
        <div class="job-card-image-wrapper">
          <img src="${escapeAttr(thumb)}" alt="${escapeAttr(job.title)}" class="job-card-image" loading="lazy">
          ${statusBadge}
        </div>
        <div class="job-card-content">
          <h3 class="job-card-title">${escapeHtml(job.title)}</h3>
          <p class="job-card-company">${escapeHtml(job.company||'')}</p>
          <div class="job-card-meta">
            <div class="job-card-meta-item">
              <span>📍</span>
              <span>${escapeHtml(job.location||'')}</span>
            </div>
            ${posted?`<div class="job-card-meta-item"><span>⏱️</span><span>${posted}</span></div>`:''}
            ${typeMeta}
          </div>
          ${tags?`<div class="job-card-tags">${tags}</div>`:''}
          <div class="job-card-footer">
            <span class="text-xs">${fieldInfo}</span>
          </div>
        </div>
      </article>
    </a>
  `;
}

function renderPager(){
  const totalPages = Math.max(1, Math.ceil(lastTotal / perPage));
  const prevDisabled = page<=1;
  const nextDisabled = page>=totalPages;
  
  // Generate page numbers to display
  let pageNumbers = [];
  const maxVisible = 5; // Maximum page numbers to show
  
  if (totalPages <= maxVisible) {
    // Show all pages if total is small
    for (let i = 1; i <= totalPages; i++) {
      pageNumbers.push(i);
    }
  } else {
    // Show pages around current page
    let start = Math.max(1, page - 2);
    let end = Math.min(totalPages, page + 2);
    
    // Adjust if at the beginning or end
    if (page <= 3) {
      end = Math.min(maxVisible, totalPages);
    } else if (page >= totalPages - 2) {
      start = Math.max(1, totalPages - maxVisible + 1);
    }
    
    for (let i = start; i <= end; i++) {
      pageNumbers.push(i);
    }
  }
  
  // Build pagination HTML
  let pagesHTML = pageNumbers.map(p => {
    if (p === page) {
      return `<span class="jobs-pagination-current">${p}</span>`;
    } else {
      return `<button class="jobs-pagination-btn" data-page="${p}">${p}</button>`;
    }
  }).join('');
  
  pager.innerHTML = `
    <button class="jobs-pagination-btn" ${prevDisabled ? 'disabled' : ''} data-page="${page-1}">← Prev</button>
    ${pagesHTML}
    <button class="jobs-pagination-btn" ${nextDisabled ? 'disabled' : ''} data-page="${page+1}">Next →</button>
  `;
  
  pager.querySelectorAll('button[data-page]').forEach(btn=>{
    if(!btn.disabled) {
      btn.addEventListener('click',e=>{
        e.preventDefault();
        const p = parseInt(btn.getAttribute('data-page')||'1',10);
        const totalPages = Math.max(1, Math.ceil(lastTotal / perPage));
        if (p>=1 && p<=totalPages) { page = p; loadJobs(); window.scrollTo({top:0, behavior:'smooth'}); }
      });
    }
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
    <button type="button" class="jobs-filter-badge"
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
