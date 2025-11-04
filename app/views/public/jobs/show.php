<?php
// Job Detail (JD)
$pageTitle = "Job Details";
$additionalCSS = [];
$additionalJS  = [];

require dirname(__DIR__, 2) . '/layouts/public_header.php';

function ic($n,$c='w-4 h-4'){ if (class_exists('Icons') && method_exists('Icons',$n)) return Icons::$n($c); return ''; }

// Controller nên load job theo id:
$jobId = (int)($_GET['id'] ?? 0);
// ví dụ sample tạm:
$job = $job ?? [
  'id'=>$jobId,'title'=>'Senior Frontend Engineer','company'=>'TechCorp Solutions',
  'location'=>'San Francisco, CA','type'=>'Full-time','field'=>'Software','expertise'=>'Senior',
  'salary_min'=>2500,'salary_max'=>4000,'currency'=>'USD','posted_at'=>'2025-10-18 13:00:00',
  'thumbnail_url'=>'images/jobs/frontend.jpg',
  'desc'=>"We are looking for a Senior Frontend Engineer to own UI architecture...\n- Build UI with Tailwind\n- Collaborate with backend team\n- Ensure accessibility & performance",
  'requirements'=>["3+ years Frontend","Experience with Tailwind or CSS-in-JS","Git workflow"],
  'benefits'=>["Competitive salary","Health insurance","Flexible hours"],
];
function money_range($min,$max,$cur){ if(!$min&&!$max) return 'Negotiable'; return ($cur?:'USD')." ".number_format($min,0). " - " . number_format($max,0); }
?>
<main class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-8">
  <nav class="text-sm text-gray-600"><a href="jobs" class="hover:underline">Jobs</a> / <span><?= htmlspecialchars($job['title']) ?></span></nav>

  <header class="mt-3">
    <h1 class="text-3xl font-bold"><?= htmlspecialchars($job['title']) ?></h1>
    <p class="mt-1 text-gray-700"><?= htmlspecialchars($job['company']) ?> • <?= htmlspecialchars($job['location']) ?> • <?= htmlspecialchars($job['type']) ?></p>
    <p class="mt-1 text-gray-700"><?= money_range($job['salary_min'],$job['salary_max'],$job['currency']) ?></p>
  </header>

  <div class="mt-6 rounded-2xl overflow-hidden">
    <img src="<?= htmlspecialchars($job['thumbnail_url']) ?>" alt="" class="w-full h-64 object-cover">
  </div>

  <section class="mt-6 grid md:grid-cols-3 gap-6">
    <div class="md:col-span-2">
      <h2 class="text-xl font-semibold">Job Description</h2>
      <pre class="mt-2 whitespace-pre-wrap text-gray-800"><?= htmlspecialchars($job['desc']) ?></pre>

      <h3 class="mt-6 text-lg font-semibold">Requirements</h3>
      <ul class="mt-2 list-disc ms-5 text-gray-800">
        <?php foreach (($job['requirements'] ?? []) as $req): ?>
          <li><?= htmlspecialchars($req) ?></li>
        <?php endforeach; ?>
      </ul>

      <h3 class="mt-6 text-lg font-semibold">Benefits</h3>
      <ul class="mt-2 list-disc ms-5 text-gray-800">
        <?php foreach (($job['benefits'] ?? []) as $bf): ?>
          <li><?= htmlspecialchars($bf) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>

    <aside>
      <div class="rounded-2xl border border-gray-200 p-4">
        <h3 class="text-lg font-semibold">Quick info</h3>
        <dl class="mt-2 text-gray-700 space-y-1">
          <div><dt class="font-medium inline">Field:</dt> <dd class="inline"><?= htmlspecialchars($job['field']) ?></dd></div>
          <div><dt class="font-medium inline">Expertise:</dt> <dd class="inline"><?= htmlspecialchars($job['expertise']) ?></dd></div>
          <div><dt class="font-medium inline">Posted:</dt> <dd class="inline"><?= date('M j, Y', strtotime($job['posted_at'])) ?></dd></div>
        </dl>
        <a href="#" class="mt-4 w-full inline-flex justify-center rounded-xl bg-gray-900 text-white px-4 py-2 font-medium hover:bg-gray-800 transition">Apply Now</a>
      </div>
    </aside>
  </section>
</main>
<?php require dirname(__DIR__, 2) . '/layouts/public_footer.php'; ?>
