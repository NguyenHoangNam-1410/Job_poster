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
<style href="/Job_poster/public/css/jobs-listing.css"></style>

<style>
/* Force Choices.js to have white background and black border like Status filter */
.choices.is-focused .choices__inner,
.choices.is-open .choices__inner {
  border-color: #0688B4 !important;
  box-shadow: 3px 3px 0 rgba(6, 136, 180, 0.3) !important;
  transform: translate(-1px, -1px) !important;
}

.choices__inner {
  background: white !important;
  background-color: white !important;
  border: 2px solid #1a1a1a !important;
  border-radius: 0 !important;
}

.choices__list--multiple .choices__item {
  background-color: transparent !important;
  background: transparent !important;
  border: 2px solid #1a1a1a !important;
  border-radius: 0 !important;
  color: #1a1a1a !important;
}

.choices__button {
  color: #1a1a1a !important;
  border-left-color: #1a1a1a !important;
}

/* Style for single-select Choices (Status) */
#statusSelect + .choices .choices__inner {
  cursor: pointer;
}

#statusSelect + .choices .choices__list--single {
  padding: 0;
}

/* Ensure all Choices dropdowns have consistent styling */
.choices__list--dropdown {
  border: 2px solid #1a1a1a !important;
  border-radius: 0 !important;
  border-top: none !important;
}

/* Add dropdown arrow like Status select */
.choices[data-type*="select-multiple"] .choices__inner::after,
.choices[data-type*="select-one"] .choices__inner::after {
  content: '' !important;
  position: absolute !important;
  right: 1rem !important;
  top: 50% !important;
  transform: translateY(-50%) !important;
  width: 0 !important;
  height: 0 !important;
  border-left: 5px solid transparent !important;
  border-right: 5px solid transparent !important;
  border-top: 6px solid #1a1a1a !important;
  pointer-events: none !important;
  margin: 0 !important;
}

.choices[data-type*="select-multiple"].is-open .choices__inner::after,
.choices[data-type*="select-one"].is-open .choices__inner::after {
  border-top: none !important;
  border-bottom: 6px solid #1a1a1a !important;
}

/* Make room for arrow */
.choices__inner {
  padding-right: 2.5rem !important;
}
</style>

<script>
document.body.classList.add('jobs-listing-page');
</script>
<main>
  <!-- Hero Section with Large Typography -->
  <section class="jobs-hero">
    <div class="jobs-hero-bg-text animate-fade-in">JOBS</div>
    <h1 class="jobs-hero-title animate-fade-in-up delay-100">FIND YOUR<br>DREAM JOB</h1>
    <p class="jobs-hero-subtitle animate-fade-in-up delay-200">Discover opportunities that match your passion. Explore our curated collection of jobs from innovative companies.</p>
  </section>

  <!-- Artistic Search Section -->
  <section class="jobs-search-section animate-fade-in-up delay-300">
    <div class="jobs-search-container">
      <form action="<?= htmlspecialchars(BASE_PUBLIC . '/index.php') ?>" method="GET" class="w-full">
        <input type="hidden" name="r" value="/jobs">
        <div class="relative mb-4">
          <input id="q" name="q" value="<?= htmlspecialchars($q) ?>" type="text" placeholder="Search job title, company, keywords…"
                 class="jobs-search-input">
          <span class="jobs-search-icon"><?= ic('search','w-6 h-6') ?></span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
          <!-- Categories: Multiple select with Choices.js (limit 3) -->
          <div class="relative">
            <label class="block text-sm font-medium mb-2" style="color: #4a4a4a;">Categories (max 3)</label>
            <select 
              name="category_ids[]" 
              id="categorySelect"
              multiple
              class="jobs-filter-select">
            </select>
          </div>

          <!-- Location: Multiple select with Choices.js (OR logic) -->
          <div class="relative">
            <label class="block text-sm font-medium mb-2" style="color: #4a4a4a;">Locations</label>
            <select 
              name="location_ids[]" 
              id="locationSelect"
              multiple
              class="jobs-filter-select">
            </select>
          </div>

          <!-- Status: Simple dropdown (only 2 options) -->
          <div class="relative">
            <label class="block text-sm font-medium mb-2" style="color: #4a4a4a;">Status</label>
            <select id="statusSelect" name="status" class="jobs-filter-select">
              <option value="">All Statuses</option>
              <option value="recruiting">Recruiting</option>
              <option value="overdue">Overdue</option>
            </select>
          </div>
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
const elCategorySelect=document.getElementById('categorySelect');
const elLocationSelect=document.getElementById('locationSelect');
const elStatusSelect=document.getElementById('statusSelect');
const grid=document.getElementById('grid');
const count=document.getElementById('count');
const pager=document.getElementById('pager');
const pageInfo=document.getElementById('pageInfo');
const clearAll=document.getElementById('clearAll');
const activeBadges=document.getElementById('activeBadges');
const activeCount=document.getElementById('activeCount');

let page=1, perPage=12, lastTotal=0, typingTimer=0;
let categoryChoices, locationChoices;

function makeURL(){
  const u=new URL(`${BASE}/ajax/jobs_list.php`, window.location.origin);
  if(elQ.value.trim()) u.searchParams.set('q', elQ.value.trim());
  
  // Add selected categories (AND logic)
  const selectedCategories = categoryChoices ? categoryChoices.getValue(true) : [];
  if(selectedCategories.length > 0) {
    selectedCategories.forEach(cat => u.searchParams.append('category_ids[]', cat));
  }
  
  // Add selected locations (OR logic)
  const selectedLocations = locationChoices ? locationChoices.getValue(true) : [];
  if(selectedLocations.length > 0) {
    selectedLocations.forEach(loc => u.searchParams.append('location_ids[]', loc));
  }
  
  // Add status
  if(elStatusSelect.value) {
    u.searchParams.set('status', elStatusSelect.value);
  }
  
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
      setTimeout(() => animateJobCards(), 50);
    }
    pageInfo.textContent = `Page ${page}`;
    renderPager();
    renderBadges();
  }catch(e){console.error(e);}
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
  
  let pageNumbers = [];
  const maxVisible = 5;
  
  if (totalPages <= maxVisible) {
    for (let i = 1; i <= totalPages; i++) {
      pageNumbers.push(i);
    }
  } else {
    let start = Math.max(1, page - 2);
    let end = Math.min(totalPages, page + 2);
    
    if (page <= 3) {
      end = Math.min(maxVisible, totalPages);
    } else if (page >= totalPages - 2) {
      start = Math.max(1, totalPages - maxVisible + 1);
    }
    
    for (let i = start; i <= end; i++) {
      pageNumbers.push(i);
    }
  }
  
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
  if(elQ.value.trim()){ items.push({type:'search', label:elQ.value.trim()}); }
  
  // Add category badges
  if(categoryChoices) {
    categoryChoices.getValue(true).forEach((val, idx) => {
      const option = Array.from(elCategorySelect.options).find(opt => opt.value === val);
      if(option) {
        items.push({type:'category', value:val, label:option.textContent, index:idx});
      }
    });
  }
  
  // Add location badges
  if(locationChoices) {
    locationChoices.getValue(true).forEach((val, idx) => {
      items.push({type:'location', value:val, label:val, index:idx});
    });
  }
  
  // Add status badge
  if(elStatusSelect.value) {
    items.push({type:'status', value:elStatusSelect.value, label:capStatus(elStatusSelect.value)});
  }

  activeBadges.innerHTML = items.map(it=>`
    <button type="button" class="jobs-filter-badge"
            data-type="${it.type}" data-value="${escapeAttr(it.value||'')}" data-index="${it.index||''}">
      <span>${escapeHtml(it.label)}</span>
      <span aria-hidden="true">✕</span>
    </button>
  `).join('');

  activeBadges.querySelectorAll('button').forEach(b=>{
    b.addEventListener('click',()=>{
      const type=b.getAttribute('data-type');
      const value=b.getAttribute('data-value');
      
      if(type==='search') {
        elQ.value='';
      } else if(type==='category' && categoryChoices) {
        categoryChoices.removeActiveItemsByValue(value);
      } else if(type==='location' && locationChoices) {
        locationChoices.removeActiveItemsByValue(value);
      } else if(type==='status') {
        elStatusSelect.value='';
      }
      
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

function animateJobCards() {
  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry, index) => {
      if (entry.isIntersecting) {
        setTimeout(() => {
          entry.target.classList.add('loaded');
        }, index * 50);
        observer.unobserve(entry.target);
      }
    });
  }, {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
  });

  document.querySelectorAll('.job-card-wrapper').forEach(card => {
    observer.observe(card);
  });
}

async function loadFilters() {
  try {
    const r = await fetch(`${BASE}/ajax/jobs_filters.php`);
    const d = await r.json() || {};
    const cats = d.categories ?? d.cats ?? [];
    const locs = d.locations ?? d.locs ?? [];
    
    // Populate categories
    elCategorySelect.innerHTML = cats.map(c => 
      `<option value="${c.id}">${escapeHtml(c.name)}</option>`
    ).join('');
    
    // Populate locations
    elLocationSelect.innerHTML = locs.map(l => 
      `<option value="${escapeHtml(l)}">${escapeHtml(l)}</option>`
    ).join('');
    
  } catch(e) {
    console.error('Failed to load filters:', e);
  }
}

document.addEventListener('DOMContentLoaded', async () => {
  // Load filter data first
  await loadFilters();
  
  // Initialize Choices.js for categories (max 3)
  if(elCategorySelect) {
    categoryChoices = new Choices(elCategorySelect, {
      removeItemButton: true,
      searchEnabled: true,
      searchPlaceholderValue: 'Search categories...',
      placeholderValue: '',
      maxItemCount: 3,
      maxItemText: (maxItemCount) => `Only ${maxItemCount} categories allowed`,
      noResultsText: 'No categories found',
    });
    
    elCategorySelect.addEventListener('change', () => {
      resetAndLoad();
    });
  }
  
  // Initialize Choices.js for locations (unlimited, OR logic)
  if(elLocationSelect) {
    locationChoices = new Choices(elLocationSelect, {
      removeItemButton: true,
      searchEnabled: true,
      searchPlaceholderValue: 'Search locations...',
      placeholderValue: '',
      maxItemCount: -1,
      noResultsText: 'No locations found',
    });
    
    elLocationSelect.addEventListener('change', () => {
      resetAndLoad();
    });
  }
  
  // Initialize Choices.js for status (single select)
  if(elStatusSelect) {
    statusChoices = new Choices(elStatusSelect, {
      searchEnabled: false,
      itemSelectText: '',
      shouldSort: false,
      placeholder: false,
    });
    
    elStatusSelect.addEventListener('change', () => {
      resetAndLoad();
    });
  }

  await loadJobs();
  
  setTimeout(() => animateJobCards(), 100);

  elQ.addEventListener('keydown', e => { if (e.key === 'Enter') { e.preventDefault(); resetAndLoad(); }});
  elQ.addEventListener('input', () => {
    if(typingTimer) clearTimeout(typingTimer);
    typingTimer = setTimeout(()=>{ resetAndLoad(); }, 400);
  });

  clearAll.addEventListener('click', e => {
    e.preventDefault();
    elQ.value='';
    if(categoryChoices) categoryChoices.removeActiveItems();
    if(locationChoices) locationChoices.removeActiveItems();
    elStatusSelect.value='';
    renderBadges();
    resetAndLoad();
  });
});
</script>

<?php require dirname(__DIR__, 2) . '/layouts/public_footer.php'; ?>
