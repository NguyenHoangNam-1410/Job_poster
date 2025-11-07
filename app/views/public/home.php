<?php 
$pageTitle = 'Homepage | Job Poster';
include __DIR__ . '/../layouts/public_header.php';
?>

<style>
/* 🌈 Background images per section (không overlay) */
.hero-bg { background-image: url('/Job_poster/public/images/bg/bg111.png'); }
.about-bg { background-image: url('/Job_poster/public/images/bg/bg2.png'); }
.benefits-bg { background-image: url('/Job_poster/public/images/bg/bg4.png'); }
.news-bg { background-image: url('/Job_poster/public/images/bg/bg3.png'); }
.feedback-bg { background-image: url('/Job_poster/public/images/bg/bg5.png'); }

.hero-bg, .about-bg, .benefits-bg, .news-bg, .feedback-bg {
  background-size: cover;
  background-position: center;
  background-attachment: fixed;
  position: relative;
  overflow: hidden;
}

/* ✨ Motion */
@keyframes fadeInUp { from{opacity:0; transform:translateY(40px)} to{opacity:1; transform:translateY(0)} }
.animate-fadeInUp { animation: fadeInUp 1s ease-out forwards; }

/* 🧭 Smooth scroll */
html { scroll-behavior: smooth; }

/* 🎩 Header ẩn khi cuộn */
header {
  transition: transform 0.4s ease, opacity 0.3s ease;
}
header.-translate-y-full {
  transform: translateY(-100%);
  opacity: 0.9;
}
</style>

<!-- 🌟 HERO -->
<section class="hero-bg relative min-h-screen flex flex-col items-center justify-center text-center bg-cover bg-center">
  <div class="container mx-auto px-6 relative z-10">
    <!-- 🔹 Hộp nền trắng chứa toàn bộ nút -->
    <div class="mt-60 inline-flex flex-wrap justify-center gap-5 px-10 py-8
                bg-white border border-gray-200 rounded-3xl shadow-2xl animate-fadeInUp">
      
      <!-- Nút chính -->
      <a href="/Job_poster/public/jobs"
   class="bg-purple-600 text-white px-10 py-4 rounded-lg font-semibold text-lg 
          hover:bg-purple-700 transition shadow-md">
  Browse Jobs
</a>


      <!-- Nút phụ -->
      <a href="#about"
         class="px-7 py-4 rounded-lg bg-gray-100 text-gray-800 text-lg font-medium 
                hover:bg-gray-200 transition">
        About
      </a>

      <a href="#benefits"
         class="px-7 py-4 rounded-lg bg-gray-100 text-gray-800 text-lg font-medium 
                hover:bg-gray-200 transition">
        Benefits
      </a>

      <a href="#promo"
         class="px-7 py-4 rounded-lg bg-gray-100 text-gray-800 text-lg font-medium 
                hover:bg-gray-200 transition">
        Video
      </a>

      <a href="#news"
         class="px-7 py-4 rounded-lg bg-gray-100 text-gray-800 text-lg font-medium 
                hover:bg-gray-200 transition">
        News
      </a>

      <a href="#feedback"
         class="px-7 py-4 rounded-lg bg-gray-100 text-gray-800 text-lg font-medium 
                hover:bg-gray-200 transition">
        Feedback
      </a>
    </div>
  </div>
</section>

<!-- 💡 ABOUT -->
<section id="about" class="about-bg py-20 text-center relative">
  <div class="container mx-auto px-6 max-w-5xl relative z-10 animate-fadeInUp space-y-10">
    <div class="rounded-2xl shadow-xl p-10 md:p-14 border border-white/40 bg-white">
      <h2 class="text-3xl font-bold text-gray-800 mb-6">About Job Poster</h2>
      <p class="text-gray-700 text-lg leading-relaxed">
        Job Poster is a modern platform connecting talented individuals with top companies.
        Our mission is to simplify job discovery, empower job seekers with insights,
        and help employers find their perfect match quickly and efficiently.
      </p>
    </div>

    <div class="flex flex-wrap justify-center gap-8">
      <div class="rounded-2xl shadow-lg p-8 w-72 hover:shadow-2xl hover:scale-105 transition border border-white/40 bg-white">
        <div class="bg-purple-100 p-4 rounded-full mb-4 shadow-md w-fit mx-auto">
          <?= Icons::users('w-8 h-8 text-purple-600') ?>
        </div>
        <h4 class="font-semibold text-gray-800 mb-2">For Job Seekers</h4>
        <p class="text-gray-600 text-sm">Discover jobs, apply easily, and manage your career goals in one place.</p>
      </div>

      <div class="rounded-2xl shadow-lg p-8 w-72 hover:shadow-2xl hover:scale-105 transition border border-white/40 bg-white">
        <div class="bg-blue-100 p-4 rounded-full mb-4 shadow-md w-fit mx-auto">
          <?= Icons::briefcase('w-8 h-8 text-blue-600') ?>
        </div>
        <h4 class="font-semibold text-gray-800 mb-2">For Employers</h4>
        <p class="text-gray-600 text-sm">Post job openings, find skilled candidates, and build your dream team.</p>
      </div>

      <div class="rounded-2xl shadow-lg p-8 w-72 hover:shadow-2xl hover:scale-105 transition border border-white/40 bg-white">
        <div class="bg-green-100 p-4 rounded-full mb-4 shadow-md w-fit mx-auto">
          <?= Icons::bar_chart('w-8 h-8 text-green-600') ?>
        </div>
        <h4 class="font-semibold text-gray-800 mb-2">Smart Matching</h4>
        <p class="text-gray-600 text-sm">Our system uses analytics to recommend the best matches for you.</p>
      </div>
    </div>
  </div>
</section>

<!-- 🎯 BENEFITS -->
<section id="benefits" class="benefits-bg relative py-20">
  <div class="container mx-auto px-6 text-center animate-fadeInUp relative z-10">
    <div class="inline-block mx-auto mb-10 px-10 py-6 rounded-2xl shadow-lg border border-white/40 bg-white">
      <h2 class="text-3xl font-bold text-gray-800">Why Choose Job Poster?</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
      <div class="rounded-2xl shadow-lg p-10 hover:shadow-2xl hover:-translate-y-2 transition border border-white/40 bg-white">
        <div class="bg-purple-100 p-4 rounded-full w-fit mx-auto mb-4 shadow-md">
          <?= Icons::zap('w-10 h-10 text-purple-600') ?>
        </div>
        <h4 class="font-semibold text-lg text-gray-800 mb-2">Fast & Simple</h4>
        <p class="text-gray-700 text-sm leading-relaxed">Quickly apply and manage applications with a clean interface.</p>
      </div>

      <div class="rounded-2xl shadow-lg p-10 hover:shadow-2xl hover:-translate-y-2 transition border border-white/40 bg-white">
        <div class="bg-blue-100 p-4 rounded-full w-fit mx-auto mb-4 shadow-md">
          <?= Icons::shield_check('w-10 h-10 text-blue-600') ?>
        </div>
        <h4 class="font-semibold text-lg text-gray-800 mb-2">Secure Platform</h4>
        <p class="text-gray-700 text-sm leading-relaxed">Your personal data and activity are encrypted and stored safely.</p>
      </div>

      <div class="rounded-2xl shadow-lg p-10 hover:shadow-2xl hover:-translate-y-2 transition border border-white/40 bg-white">
        <div class="bg-green-100 p-4 rounded-full w-fit mx-auto mb-4 shadow-md">
          <?= Icons::award('w-10 h-10 text-green-600') ?>
        </div>
        <h4 class="font-semibold text-lg text-gray-800 mb-2">Verified Employers</h4>
        <p class="text-gray-700 text-sm leading-relaxed">Collaborate with trusted companies verified by our team.</p>
      </div>
    </div>
  </div>
</section>

<!-- 🎥 PROMO VIDEO -->
<section id="promo" class="relative py-24 bg-gray-100 bg-opacity-70 backdrop-blur-sm">
  <div class="container mx-auto px-6 text-center animate-fadeInUp">
    <div class="inline-block mx-auto mb-10 px-10 py-6 rounded-2xl shadow-lg bg-white border border-gray-200">
      <h2 class="text-3xl font-bold text-gray-800">Watch Our Introduction Video</h2>
      <p class="text-gray-600 mt-3">Learn how Job Poster helps you find jobs and grow your career in minutes.</p>
    </div>

    <!-- Video container -->
    <div class="relative max-w-5xl mx-auto rounded-2xl shadow-2xl overflow-hidden border border-gray-200 bg-white">
      <div class="aspect-w-16 aspect-h-9">
        <iframe class="w-full h-[520px]" src="https://www.youtube.com/embed/your_video_id_here"
                title="Job Poster Intro Video" frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen>
        </iframe>
      </div>
    </div>
  </div>
</section>

<!-- 📰 NEWS -->
<section id="news" class="news-bg relative py-20">
  <div class="container mx-auto px-6 animate-fadeInUp relative z-10">
    <div class="inline-block mx-auto mb-12 px-10 py-6 rounded-2xl shadow-lg border border-white/40 bg-white text-center">
      <h2 class="text-3xl font-bold text-gray-800">Latest Job News & Insights</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <?php 
      $articles = [
        ['img'=>'1.png','title'=>'Top 5 In-demand Tech Skills in 2025','desc'=>'Stay ahead with the most sought-after skills in software development and AI.'],
        ['img'=>'2.png','title'=>'How to Build a Remote Career Successfully','desc'=>'Learn the habits and tools to thrive in the modern remote workspace.'],
        ['img'=>'3.png','title'=>'Navigating Job Market Trends in 2025','desc'=>'Insights into hiring shifts, automation, and emerging industries.']
      ];
      foreach ($articles as $a): ?>
      <article class="rounded-2xl shadow-xl overflow-hidden transition transform hover:-translate-y-2 hover:shadow-2xl border border-white/40 bg-white">
        <img src="/Job_poster/public/images/jobs/<?= htmlspecialchars($a['img']) ?>" 
             class="w-full h-48 object-cover border-b border-white/40" alt="">
        <div class="p-6 text-left">
          <h4 class="font-semibold text-lg text-gray-800 mb-2"><?= htmlspecialchars($a['title']) ?></h4>
          <p class="text-gray-700 text-sm mb-4"><?= htmlspecialchars($a['desc']) ?></p>
          <a href="#" class="text-purple-600 font-semibold hover:underline">Read more →</a>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- 💬 FEEDBACK -->
<section id="feedback" class="feedback-bg relative py-20">
  <div class="container mx-auto px-6 text-center animate-fadeInUp relative z-10">
    <div class="inline-block mx-auto mb-12 px-10 py-6 rounded-2xl shadow-lg border border-white/40 bg-white">
      <h2 class="text-3xl font-bold text-gray-800">What Our Users Say</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <?php 
      $feedbacks = [
        ['text'=>'“I found my dream job in less than a week! The process was smooth and easy.”','name'=>'Anna Nguyen','role'=>'Software Engineer'],
        ['text'=>'“A clean design, useful filters, and verified companies. Highly recommended!”','name'=>'Minh Tran','role'=>'UI/UX Designer'],
        ['text'=>'“Posting jobs and managing applicants has never been this efficient.”','name'=>'HR at CloudTech','role'=>'Recruitment Manager']
      ];
      foreach ($feedbacks as $f): ?>
      <div class="rounded-2xl shadow-lg p-10 hover:shadow-2xl hover:-translate-y-2 transition border border-white/40 bg-white">
        <p class="text-gray-700 italic mb-4 leading-relaxed"><?= htmlspecialchars($f['text']) ?></p>
        <h4 class="font-semibold text-gray-900 mb-1"><?= htmlspecialchars($f['name']) ?></h4>
        <p class="text-gray-500 text-sm"><?= htmlspecialchars($f['role']) ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- 🧠 Script ẩn header khi scroll -->
<script>
document.addEventListener("DOMContentLoaded", () => {
  const header = document.querySelector("header");
  if (!header) return;

  let lastScrollY = window.scrollY;
  window.addEventListener("scroll", () => {
    if (window.scrollY > lastScrollY && window.scrollY > 100) {
      header.classList.add("-translate-y-full");
    } else {
      header.classList.remove("-translate-y-full");
    }
    lastScrollY = window.scrollY;
  });
});
</script>

<?php include __DIR__ . '/../layouts/public_footer.php'; ?>

