<?php
const BASE_PUBLIC = '/Worknest/public';

// Check if job exists first
if (!isset($job) || !is_array($job)) {
  http_response_code(404);
  echo '<main class="max-w-3xl mx-auto px-6 py-12 text-center"><h1 class="text-xl font-semibold">Job not found</h1><p class="mt-2 text-gray-600">No job data available.</p></main>';
  require dirname(__DIR__, 2) . '/layouts/public_footer.php';
  return;
}

// SEO Meta Data
$jobTitle = isset($job['title']) ? htmlspecialchars($job['title'], ENT_QUOTES, 'UTF-8') : 'Job Details';
$companyName = isset($job['company']) ? htmlspecialchars($job['company'], ENT_QUOTES, 'UTF-8') : (isset($job['company_name']) ? htmlspecialchars($job['company_name'], ENT_QUOTES, 'UTF-8') : '');
$location = isset($job['location']) ? htmlspecialchars($job['location'], ENT_QUOTES, 'UTF-8') : '';
$salary = isset($job['salary']) && $job['salary'] > 0 ? number_format($job['salary'], 0, '.', ',') . ' VND' : 'Negotiable';

// Build page title
$pageTitle = $jobTitle . ' - ' . $companyName . ($location ? ' | ' . $location : '') . ' | WorkNest';

// Build meta description
$description = isset($job['description']) ? strip_tags($job['description']) : '';
$description = mb_substr($description, 0, 160);
$metaDescription = $jobTitle . ' at ' . $companyName . ($location ? ' in ' . $location : '') . '. ' . $description;

// Meta keywords
$metaKeywords = [
    strtolower($jobTitle),
    strtolower($companyName),
    $location ? strtolower($location) : '',
    'job', 'career', 'hiring', 'recruitment',
    'Vietnam jobs', 'work in Vietnam'
];
$metaKeywords = array_filter($metaKeywords);

// Meta image (company logo or default)
$logo = $job['logo'] ?? '';
$metaImage = !empty($logo) 
    ? (strpos($logo, 'http') === 0 ? $logo : BASE_PUBLIC . '/' . ltrim($logo, '/'))
    : BASE_PUBLIC . '/images/og-image.jpg';

// Breadcrumbs for structured data
$breadcrumbs = [
    ['name' => 'Home', 'url' => '/'],
    ['name' => 'Jobs', 'url' => '/jobs'],
    ['name' => $jobTitle, 'url' => '/jobs/show/' . (isset($job['id']) ? $job['id'] : '')]
];

$additionalCSS = ['/Worknest/public/css/jobs-artistic.css'];
$additionalJS = [];
require dirname(__DIR__, 2) . '/layouts/public_header.php';

function h($s)
{
  return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
}
function ph($v)
{
  return ($v === null || $v === '') ? 'No information provided yet' : $v;
}
function money_vn($n)
{
  if ($n === null || $n === '')
    return 'No information provided yet';
  if (!is_numeric($n))
    return h($n);
  return number_format((float) $n, 0, '.', ',') . ' VND';
}
function days_left($d)
{
  if (!$d)
    return null;
  $t = strtotime($d);
  if ($t === false)
    return null;
  $today = strtotime(date('Y-m-d'));
  return (int) floor(($t - $today) / 86400);
}
function capStatus($s)
{
  $s = strtolower((string) $s);
  if ($s === 'approved')
    $s = 'recruiting';
  if ($s === 'overdue')
    return 'Overdue';
  if ($s === 'recruiting')
    return 'Recruiting';
  return ucfirst($s);
}

$title = $job['title'] ?? '';
$jobId = (int) ($job['id'] ?? 0);
$companyRaw = $job['company'] ?? ($job['company_name'] ?? ($job['employer_name'] ?? ''));
$company = ph($companyRaw);
$location = ph($job['location'] ?? '');
$salary = money_vn($job['salary'] ?? null);
$deadlineRaw = $job['deadline'] ?? null;
$deadline = $deadlineRaw ? date('M j, Y', strtotime($deadlineRaw)) : 'No information provided yet';
$postedRaw = $job['created_at'] ?? ($job['posted_at'] ?? null);
$posted = $postedRaw ? date('M j, Y', strtotime($postedRaw)) : 'No information provided yet';
$statusRaw = $job['status'] ?? ($job['public_status'] ?? '');
$status = strtolower((string) $statusRaw) === 'approved' ? 'recruiting' : ($statusRaw ?? '');
$statusLbl = capStatus($status);
// Use logo from employer with proper path prefixing
$banner = $logo ? (strpos($logo, 'http') === 0 || strpos($logo, '/') === 0 ? $logo : BASE_PUBLIC . '/' . $logo) : BASE_PUBLIC . '/images/placeholder.jpg';
$chips = $job['categories'] ?? [];
$desc = $job['description'] ?? '';
$reqStr = $job['requirements'] ?? '';
$reqs = [];
if ($reqStr !== '') {
  foreach (preg_split('/\r\n|\n|,/', (string) $reqStr) as $p) {
    $p = trim($p);
    if ($p !== '')
      $reqs[] = $p;
  }
}
$left = days_left($deadlineRaw);
?>
<style>
  @keyframes float {

    0%,
    100% {
      transform: translateY(0px) translateX(0px);
    }

    33% {
      transform: translateY(-20px) translateX(10px);
    }

    66% {
      transform: translateY(10px) translateX(-10px);
    }
  }

  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(30px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  @keyframes fadeIn {
    from {
      opacity: 0;
    }

    to {
      opacity: 1;
    }
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

  /* Page load animations */
  .section-card {
    animation: fadeInUp 0.8s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    opacity: 0;
    transition: transform 0.3s ease;
  }

  .section-card:hover {
    transform: translateY(-2px);
  }

  .section-card:nth-child(1) {
    animation-delay: 0.1s;
  }

  .section-card:nth-child(2) {
    animation-delay: 0.2s;
  }

  .section-card:nth-child(3) {
    animation-delay: 0.3s;
  }

  .section-card:nth-child(4) {
    animation-delay: 0.4s;
  }

  .section-card:nth-child(5) {
    animation-delay: 0.5s;
  }

  .section-card:nth-child(6) {
    animation-delay: 0.6s;
  }

  /* Sidebar animation */
  .sidebar-animate {
    animation: fadeIn 0.8s ease-out 0.3s forwards;
    opacity: 0;
  }

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

  /* Apply Modal Scrollbar */
  #applyModal .overflow-y-auto::-webkit-scrollbar {
    width: 8px;
  }

  #applyModal .overflow-y-auto::-webkit-scrollbar-track {
    background: #f3f4f6;
    border-radius: 4px;
  }

  #applyModal .overflow-y-auto::-webkit-scrollbar-thumb {
    background: #0688B4;
    border-radius: 4px;
  }

  #applyModal .overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #056a8a;
  }

  /* Success Popup */
  #successPopup {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: white;
    border-radius: 12px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    padding: 0;
    z-index: 9999;
    max-width: 400px;
    width: 90%;
    animation: popupSlideIn 0.3s ease-out;
    border: 3px solid #10b981;
  }

  @keyframes popupSlideIn {
    from {
      opacity: 0;
      transform: translate(-50%, -60%);
    }

    to {
      opacity: 1;
      transform: translate(-50%, -50%);
    }
  }

  #successPopupOverlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 9998;
    animation: fadeIn 0.3s ease-out;
  }

  @keyframes fadeIn {
    from {
      opacity: 0;
    }

    to {
      opacity: 1;
    }
  }

  .success-popup-content {
    padding: 30px;
    text-align: center;
  }

  .success-popup-icon {
    width: 64px;
    height: 64px;
    margin: 0 auto 20px;
    background: #10b981;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: scaleIn 0.3s ease-out 0.1s both;
  }

  @keyframes scaleIn {
    from {
      transform: scale(0);
    }

    to {
      transform: scale(1);
    }
  }

  .success-popup-icon svg {
    width: 36px;
    height: 36px;
    color: white;
  }

  .success-popup-title {
    font-size: 24px;
    font-weight: bold;
    color: #1f2937;
    margin-bottom: 12px;
  }

  .success-popup-message {
    font-size: 16px;
    color: #6b7280;
    line-height: 1.5;
    margin-bottom: 24px;
  }

  .success-popup-button {
    background: #0688B4;
    color: white;
    border: none;
    padding: 12px 32px;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
  }

  .success-popup-button:hover {
    background: #056a8a;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(6, 136, 180, 0.3);
  }

  /* Remove white space below footer */
  footer.bg-gray-900 {
    margin-bottom: 0 !important;
  }

  /* Fix background animation overflow */
  .bg-animated {
    overflow: hidden;
  }
</style>

<main class="flex-grow mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 py-8 text-left bg-animated">
  <div class="content-wrapper">
    <nav class="text-sm text-gray-500 mb-6">
      <a href="<?= BASE_PUBLIC ?>/jobs" class="hover:text-gray-900 transition-colors">Jobs</a> <span
        class="mx-2">/</span> <span class="text-gray-900"><?= h($title) ?></span>
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
              <?php foreach ($chips as $c):
                if (!$c)
                  continue; ?>
                <span
                  class="px-3 py-1 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300"><?= h($c) ?></span>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <div class="text-gray-400 text-sm">No categories available</div>
          <?php endif; ?>
        </header>

        <?php if ($salary !== 'No information provided yet'): ?>
          <!-- Salary -->
          <div
            class="section-card border-l-4 border-teal-600 pl-6 py-2 bg-white shadow-sm hover:shadow-md transition-all duration-300"
            style="border-color: #0688B4;">
            <h3 class="text-sm font-semibold uppercase tracking-wide mb-2" style="color: #0688B4;">Salary</h3>
            <p class="text-2xl font-bold text-gray-900"><?= $salary ?></p>
          </div>
        <?php endif; ?>

        <!-- Job Description -->
        <div class="section-card bg-white p-6 shadow-sm hover:shadow-md transition-shadow duration-300 border-l-2"
          style="border-color: #0688B4;">
          <h2 class="text-xl font-bold text-gray-900 mb-4">Job Description</h2>
          <div class="text-gray-700 whitespace-pre-wrap leading-relaxed">
            <?php if ($desc === ''): ?>
              <p class="text-gray-400">No information provided yet</p>
            <?php else: ?>
              <?= nl2br(h($desc)) ?>
            <?php endif; ?>
          </div>
        </div>

        <!-- Requirements -->
        <div class="section-card bg-white p-6 shadow-sm hover:shadow-md transition-shadow duration-300 border-l-2"
          style="border-color: #0688B4;">
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
        <div class="section-card bg-white p-6 shadow-sm hover:shadow-md transition-shadow duration-300 border-l-2"
          style="border-color: #0688B4;">
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
            <div class="mt-4 p-3 text-center"
              style="background-color: <?= $left <= 3 ? '#fee2e2' : 'rgba(6, 136, 180, 0.1)' ?>; border: 1px solid <?= $left <= 3 ? '#fca5a5' : '#0688B4' ?>;">
              <p class="text-sm font-medium <?= $left <= 3 ? 'text-red-600' : '' ?>"
                style="<?= $left <= 3 ? '' : 'color: #0688B4;' ?>"><?= $left >= 0 ? $left . ' days left' : 'Closed' ?></p>
            </div>
          <?php else: ?>
            <div class="mt-4 p-3 bg-gray-50 border border-gray-200 text-center">
              <p class="text-sm font-medium text-gray-700">Closed</p>
            </div>
          <?php endif; ?>
          <button onclick="openApplyModal()"
            class="mt-5 w-full inline-flex justify-center text-white px-6 py-3 font-semibold hover:opacity-90 transition-all duration-300 shadow-md hover:shadow-lg"
            style="background-color: #0688B4;">
            Apply Now
          </button>
        </div>
      </section>

      <aside class="lg:col-span-1 sidebar-animate">
        <!-- Relevant Jobs -->
        <div
          class="bg-white border border-gray-200 sticky top-6 shadow-sm hover:shadow-md transition-shadow duration-300"
          style="max-height: calc(100vh - 3rem); display: flex; flex-direction: column;">
          <div class="p-6 pb-4 border-b border-gray-100">
            <div class="flex items-center justify-between">
              <h3 class="text-lg font-bold text-gray-900">Relevant Jobs</h3>
              <a href="<?= BASE_PUBLIC ?>/jobs" class="text-sm font-medium hover:underline transition-colors"
                style="color: #0688B4;">See all →</a>
            </div>
          </div>
          <div id="rel-list" class="p-6 pt-4 space-y-3 overflow-y-auto"
            style="flex: 1; scrollbar-width: thin; scrollbar-color: #0688B4 #f3f4f6;"></div>
        </div>
      </aside>
    </div>
  </div>
</main>

<script>
  const BASE = '<?= BASE_PUBLIC ?>';
  const relWrap = document.getElementById('rel-list');
  const jobId = <?= json_encode($jobId) ?>;

  function jobLink(id) { return `${BASE}/jobs/show/${encodeURIComponent(id)}`; }
  function escapeHtml(s) { return (s ?? '').toString().replace(/[&<>"']/g, m => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[m])); }
  function normStatus(s) {
    s = String(s || '').toLowerCase();
    return s === 'approved' ? 'recruiting' : s;
  }
  function capStatus(s) {
    s = normStatus(s);
    return s === 'recruiting' ? 'Recruiting'
      : s === 'overdue' ? 'Overdue'
        : (s || '').charAt(0).toUpperCase() + (s || '').slice(1);
  }
  function statusClass(st) {
    st = normStatus(st);
    switch (st) {
      case 'recruiting': return 'bg-emerald-100 text-emerald-700 border-emerald-200';
      case 'overdue': return 'bg-rose-100 text-rose-700 border-rose-200';
      default: return 'bg-gray-100 text-gray-700 border-gray-200';
    }
  }
  function relCard(j) {
    const title = escapeHtml(j.title || '');
    const comp = escapeHtml(j.company || j.company_name || j.employer_name || '');
    const loc = escapeHtml(j.location || '');
    const st = normStatus(j.public_status || j.status || '');
    const badge = st ? `<span class="inline-flex items-center px-2 py-0.5 text-[10px] font-medium ${statusClass(st)}">${capStatus(st)}</span>` : '';
    return `
    <a href="${jobLink(j.id)}"
       class="group block border border-gray-200 bg-white px-4 py-3 hover:border-gray-900 transition-all duration-200 opacity-0 translate-y-2">
      <div class="flex items-start justify-between gap-2 mb-1">
        <h4 class="text-sm font-semibold leading-snug line-clamp-2 group-hover:text-gray-900">${title}</h4>
        ${badge}
      </div>
      <p class="mt-1 text-xs text-gray-500">${comp}${comp && loc ? ' • ' : ''}${loc}</p>
    </a>
  `;
  }
  async function loadRelated() {
    if (!jobId) { relWrap.innerHTML = '<div class="text-sm text-gray-500">No related jobs</div>'; return; }
    try {
      const url = `${BASE}/ajax/jobs_related.php?job_id=${encodeURIComponent(jobId)}&limit=10`;
      const r = await fetch(url);
      const d = await r.json();
      const rows = Array.isArray(d?.rows) ? d.rows : (Array.isArray(d) ? d : []);
      if (!rows.length) {
        relWrap.innerHTML = '<div class="text-sm text-gray-500">No related jobs</div>';
        return;
      }
      relWrap.innerHTML = rows.map(relCard).join('');
      appearMotion(relWrap.querySelectorAll('.group'));
    } catch (e) {
      relWrap.innerHTML = '<div class="text-sm text-gray-500">Failed to load related jobs</div>';
    }
  }
  function appearMotion(nodes) {
    const io = new IntersectionObserver(entries => {
      entries.forEach(en => {
        if (en.isIntersecting) {
          en.target.classList.add('opacity-100', 'translate-y-0');
          en.target.classList.remove('opacity-0', 'translate-y-2');
          io.unobserve(en.target);
        }
      });
    }, { threshold: 0.15 });
    nodes.forEach(n => {
      n.classList.add('transition', 'duration-500');
      io.observe(n);
    });
  }
  // File input handler - declare variable first
  let fileInputSetup = false;

  function handleFileSelect(input) {
    const file = input.files[0];
    if (file) {
      // Validate file type (PDF and images)
      const allowedTypes = [
        'application/pdf',
        'image/jpeg',
        'image/jpg',
        'image/png',
        'image/gif',
        'image/webp'
      ];
      const fileExtension = file.name.toLowerCase().split('.').pop();
      const allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'webp'];

      if (!allowedTypes.includes(file.type) && !allowedExtensions.includes(fileExtension)) {
        alert('Please upload a PDF or image file (JPG, PNG, GIF, WebP).');
        input.value = '';
        const fileNameEl = document.getElementById('cvFileName');
        if (fileNameEl) fileNameEl.textContent = 'No file chosen';
        const uploadArea = document.getElementById('cvUploadArea');
        if (uploadArea) uploadArea.classList.remove('border-green-400', 'bg-green-50');
        return;
      }
      // Validate file size (5MB)
      if (file.size > 5 * 1024 * 1024) {
        alert('File size must be less than 5MB.');
        input.value = '';
        const fileNameEl = document.getElementById('cvFileName');
        if (fileNameEl) fileNameEl.textContent = 'No file chosen';
        const uploadArea = document.getElementById('cvUploadArea');
        if (uploadArea) uploadArea.classList.remove('border-green-400', 'bg-green-50');
        return;
      }
      // Success - show file name
      const fileNameEl = document.getElementById('cvFileName');
      if (fileNameEl) fileNameEl.textContent = file.name;
      const uploadArea = document.getElementById('cvUploadArea');
      if (uploadArea) uploadArea.classList.add('border-green-400', 'bg-green-50');
    } else {
      const fileNameEl = document.getElementById('cvFileName');
      if (fileNameEl) fileNameEl.textContent = 'No file chosen';
      const uploadArea = document.getElementById('cvUploadArea');
      if (uploadArea) uploadArea.classList.remove('border-green-400', 'bg-green-50');
    }
  }

  function setupFileInput() {
    if (fileInputSetup) return;

    const cvFile = document.getElementById('cvFile');
    const cvUploadArea = document.getElementById('cvUploadArea');

    if (!cvFile || !cvUploadArea) {
      // Retry if elements not ready
      setTimeout(setupFileInput, 100);
      return;
    }

    fileInputSetup = true;

    // Click on upload area to trigger file input
    cvUploadArea.addEventListener('click', function (e) {
      // Don't trigger if clicking on the file input itself
      if (e.target === cvFile) return;
      e.preventDefault();
      e.stopPropagation();
      cvFile.click();
    });

    // Prevent default drag behaviors
    cvUploadArea.addEventListener('dragover', function (e) {
      e.preventDefault();
      e.stopPropagation();
      this.classList.add('border-blue-500', 'bg-blue-50');
    });

    cvUploadArea.addEventListener('dragleave', function (e) {
      e.preventDefault();
      e.stopPropagation();
      this.classList.remove('border-blue-500', 'bg-blue-50');
    });

    cvUploadArea.addEventListener('drop', function (e) {
      e.preventDefault();
      e.stopPropagation();
      this.classList.remove('border-blue-500', 'bg-blue-50');

      const files = e.dataTransfer.files;
      if (files.length > 0) {
        // Create a new FileList and assign it
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(files[0]);
        cvFile.files = dataTransfer.files;
        handleFileSelect(cvFile);
      }
    });

    // File input change handler
    cvFile.addEventListener('change', function (e) {
      handleFileSelect(e.target);
    });
  }

  // Apply Modal Functions
  function openApplyModal() {
    const modal = document.getElementById('applyModal');
    if (!modal) return;

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    // Auto-fill form with user data if logged in (optional)
    <?php if (isset($_SESSION['user'])): ?>
      document.getElementById('fullName').value = '<?= addslashes($_SESSION['user']['name'] ?? '') ?>';
      document.getElementById('email').value = '<?= addslashes($_SESSION['user']['email'] ?? '') ?>';
    <?php endif; ?>

    // Setup file input when modal opens
    fileInputSetup = false; // Reset to allow setup again
    setTimeout(setupFileInput, 100);
  }

  function closeApplyModal() {
    const modal = document.getElementById('applyModal');
    if (!modal) return;

    modal.classList.add('hidden');
    document.body.style.overflow = '';

    const form = document.getElementById('applyForm');
    if (form) form.reset();

    const fileNameEl = document.getElementById('cvFileName');
    if (fileNameEl) fileNameEl.textContent = 'No file chosen';

    const uploadArea = document.getElementById('cvUploadArea');
    if (uploadArea) uploadArea.classList.remove('border-green-400', 'bg-green-50');

    const errorEl = document.getElementById('applyError');
    if (errorEl) errorEl.classList.add('hidden');

    const successEl = document.getElementById('applySuccess');
    if (successEl) successEl.classList.add('hidden');
  }

  // Success Popup Functions
  function showSuccessPopup(message) {
    // Remove existing popup if any
    const existingPopup = document.getElementById('successPopup');
    const existingOverlay = document.getElementById('successPopupOverlay');
    if (existingPopup) existingPopup.remove();
    if (existingOverlay) existingOverlay.remove();

    // Create overlay
    const overlay = document.createElement('div');
    overlay.id = 'successPopupOverlay';
    document.body.appendChild(overlay);

    // Create popup
    const popup = document.createElement('div');
    popup.id = 'successPopup';
    popup.innerHTML = `
    <div class="success-popup-content">
      <div class="success-popup-icon">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
        </svg>
      </div>
      <h3 class="success-popup-title">Success!</h3>
      <p class="success-popup-message">${message}</p>
      <button class="success-popup-button" onclick="closeSuccessPopup()">OK</button>
    </div>
  `;
    document.body.appendChild(popup);

    // Auto close after 5 seconds
    setTimeout(() => {
      closeSuccessPopup();
    }, 5000);

    // Close on overlay click
    overlay.addEventListener('click', closeSuccessPopup);
  }

  function closeSuccessPopup() {
    const popup = document.getElementById('successPopup');
    const overlay = document.getElementById('successPopupOverlay');

    if (popup) {
      popup.style.animation = 'popupSlideIn 0.3s ease-out reverse';
      setTimeout(() => popup.remove(), 300);
    }
    if (overlay) {
      overlay.style.animation = 'fadeIn 0.3s ease-out reverse';
      setTimeout(() => overlay.remove(), 300);
    }
  }

  document.addEventListener('DOMContentLoaded', function () {
    loadRelated();

    // Setup modal close handler
    const modal = document.getElementById('applyModal');
    if (modal) {
      modal.addEventListener('click', function (e) {
        if (e.target.id === 'applyModal') {
          closeApplyModal();
        }
      });
    }

    // Setup form submission handler
    const applyForm = document.getElementById('applyForm');
    if (applyForm) {
      applyForm.addEventListener('submit', async function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        formData.append('job_id', jobId);

        const submitBtn = document.getElementById('submitBtn');
        const errorEl = document.getElementById('applyError');
        const successEl = document.getElementById('applySuccess');

        if (!submitBtn) return;

        const originalText = submitBtn.textContent;
        submitBtn.disabled = true;
        submitBtn.textContent = 'Sending...';

        if (errorEl) errorEl.classList.add('hidden');
        if (successEl) successEl.classList.add('hidden');

        try {
          const response = await fetch(`${BASE}/jobs/apply`, {
            method: 'POST',
            body: formData
          });

          // Check if response is ok
          if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
          }

          // Try to parse JSON
          let result;
          try {
            result = await response.json();
          } catch (jsonError) {
            const text = await response.text();
            console.error('Response is not JSON:', text);
            throw new Error('Invalid response from server');
          }

          if (result.success) {
            // Show success popup
            showSuccessPopup('Application sent successfully! The employer will contact you soon.');
            // Close modal after showing popup
            setTimeout(() => {
              closeApplyModal();
            }, 500);
          } else {
            if (errorEl) {
              errorEl.textContent = result.message || 'Failed to send application. Please try again.';
              errorEl.classList.remove('hidden');
            }
          }
        } catch (error) {
          console.error('Apply error:', error);
          if (errorEl) {
            errorEl.textContent = error.message || 'An error occurred. Please try again.';
            errorEl.classList.remove('hidden');
          }
        } finally {
          submitBtn.disabled = false;
          submitBtn.textContent = originalText;
        }
      });
    }
  });
</script>

<!-- Apply Modal -->
<div id="applyModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
  <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[95vh] flex flex-col">
    <div
      class="flex-shrink-0 bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center sticky top-0 z-10">
      <h2 class="text-xl font-bold text-gray-900">Apply for this Position</h2>
      <button onclick="closeApplyModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
    </div>

    <div class="flex-1 overflow-y-auto" style="max-height: calc(95vh - 80px);">
      <form id="applyForm" class="p-4 sm:p-6 space-y-4">
        <div id="applyError" class="hidden bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded"></div>
        <div id="applySuccess" class="hidden bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">
          Application sent successfully! The employer will contact you soon.
        </div>

        <div>
          <label for="fullName" class="block text-sm font-medium text-gray-700 mb-2">
            Full Name <span class="text-red-500">*</span>
          </label>
          <input type="text" id="fullName" name="full_name" required
            value="<?= isset($_SESSION['user']['name']) ? h($_SESSION['user']['name']) : '' ?>"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 <?= isset($_SESSION['user']['name']) ? 'bg-gray-50' : '' ?>"
            <?= isset($_SESSION['user']['name']) ? 'readonly' : '' ?>>
          <?php if (isset($_SESSION['user']['name'])): ?>
            <p class="mt-1 text-xs text-gray-500">This information is from your account.</p>
          <?php endif; ?>
        </div>

        <div>
          <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
            Your Email <span class="text-red-500">*</span>
          </label>
          <input type="email" id="email" name="email" required
            value="<?= isset($_SESSION['user']['email']) ? h($_SESSION['user']['email']) : '' ?>"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 <?= isset($_SESSION['user']['email']) ? 'bg-gray-50' : '' ?>"
            placeholder="your.email@example.com" <?= isset($_SESSION['user']['email']) ? 'readonly' : '' ?>>
          <p class="mt-1 text-xs text-gray-500">This email will be used to send your application to the employer.</p>
        </div>

        <div>
          <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
            Phone Number
          </label>
          <input type="tel" id="phone" name="phone"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            placeholder="+84 xxx xxx xxx">
        </div>

        <div>
          <label for="coverLetter" class="block text-sm font-medium text-gray-700 mb-2">
            Cover Letter <span class="text-red-500">*</span>
          </label>
          <textarea id="coverLetter" name="cover_letter" rows="4" required
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            placeholder="Write a cover letter expressing your interest and suitability for this position..."></textarea>
          <p class="mt-1 text-xs text-gray-500">A cover letter helps you express your aspirations and demonstrate your
            suitability for this position.</p>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Upload CV <span class="text-red-500">*</span>
          </label>
          <div id="cvUploadArea"
            class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-400 transition-colors cursor-pointer">
            <input type="file" id="cvFile" name="cv" accept=".pdf,.jpg,.jpeg,.png,.gif,.webp,image/*,application/pdf"
              required class="hidden">
            <div id="cvUploadContent">
              <svg class="mx-auto h-10 w-10 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                <path
                  d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                  stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
              <p class="mt-2 text-sm text-gray-600">
                <span class="font-medium text-blue-600 hover:text-blue-500">Click to upload</span> or drag and drop
              </p>
              <p class="mt-1 text-xs text-gray-500">PDF or Image (JPG, PNG, GIF, WebP) - Max 5MB</p>
              <p id="cvFileName" class="mt-2 text-xs text-gray-500">No file chosen</p>
            </div>
          </div>
          <p class="mt-1 text-xs text-gray-500">Upload your CV in PDF or image format (JPG, PNG, GIF, WebP).</p>
        </div>

        <div
          class="flex gap-3 pt-3 sticky bottom-0 bg-white border-t border-gray-200 -mx-4 sm:-mx-6 px-4 sm:px-6 py-3 mt-4">
          <button type="button" onclick="closeApplyModal()"
            class="flex-1 px-4 py-2.5 text-sm border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
            Cancel
          </button>
          <button type="submit" id="submitBtn"
            class="flex-1 px-4 py-2.5 text-sm text-white rounded-lg font-medium hover:opacity-90 transition-all"
            style="background-color: #0688B4;">
            Send Application
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php require dirname(__DIR__, 2) . '/layouts/public_footer.php'; ?>