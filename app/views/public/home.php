<?php 
$pageTitle = 'Homepage | Job Poster';
include __DIR__ . '/../layouts/public_header.php';
?>

<style>
/* Background images without overlay */
.hero-bg { 
  background-image: url('/Job_poster/public/images/bg/bg1.png'); 
}
.about-bg { 
  background-image: url('/Job_poster/public/images/bg/bg2.png'); 
}
.benefits-bg { 
  background-image: url('/Job_poster/public/images/bg/bg4.png'); 
}
.news-bg { 
  background-image: url('/Job_poster/public/images/bg/bg3.png'); 
}
.feedback-bg { 
  background-image: url('/Job_poster/public/images/bg/bg5.png'); 
}

.hero-bg, .about-bg, .benefits-bg, .news-bg, .feedback-bg {
  background-size: cover;
  background-position: center;
  background-attachment: fixed;
  position: relative;
  overflow: hidden;
}

/* Floating animation for background elements */
@keyframes float {
  0%, 100% { transform: translateY(0px) translateX(0px); }
  33% { transform: translateY(-20px) translateX(10px); }
  66% { transform: translateY(10px) translateX(-10px); }
}

@keyframes fadeInUp { 
  from { opacity: 0; transform: translateY(40px); } 
  to { opacity: 1; transform: translateY(0); } 
}

.animate-fadeInUp { 
  animation: fadeInUp 0.8s ease-out forwards; 
}

/* Smooth scroll */
html { scroll-behavior: smooth; }

/* Header hide on scroll */
header {
  transition: transform 0.4s ease, opacity 0.3s ease;
}
header.-translate-y-full {
  transform: translateY(-100%);
  opacity: 0.9;
}

/* Teal accent color */
:root {
  --primary-teal: #0688B4;
  --primary-teal-dark: #056a8a;
  --primary-teal-light: #0aa5d1;
}
</style>

<!-- HERO -->
<section class="hero-bg relative min-h-screen flex flex-col items-center justify-center text-center bg-cover bg-center">
  <div class="container mx-auto px-6 relative z-10">
    <div class="mt-60 inline-flex flex-wrap justify-center gap-4 px-8 py-6 bg-white border-2 border-gray-200 shadow-lg animate-fadeInUp">
      <!-- Main button -->
      <a href="/Job_poster/public/jobs"
         class="text-white px-10 py-3 font-semibold text-lg hover:opacity-90 transition-all shadow-md hover:shadow-lg"
         style="background-color: #0688B4;">
        Browse Jobs
      </a>

      <!-- Secondary buttons -->
      <a href="#about" class="px-6 py-3 bg-gray-100 text-gray-800 text-lg font-medium hover:bg-gray-200 transition border border-gray-300">
        About
      </a>
      <a href="#benefits" class="px-6 py-3 bg-gray-100 text-gray-800 text-lg font-medium hover:bg-gray-200 transition border border-gray-300">
        Benefits
      </a>
      <a href="#promo" class="px-6 py-3 bg-gray-100 text-gray-800 text-lg font-medium hover:bg-gray-200 transition border border-gray-300">
        Video
      </a>
      <a href="#news" class="px-6 py-3 bg-gray-100 text-gray-800 text-lg font-medium hover:bg-gray-200 transition border border-gray-300">
        News
      </a>
      <a href="#feedback" class="px-6 py-3 bg-gray-100 text-gray-800 text-lg font-medium hover:bg-gray-200 transition border border-gray-300">
        Feedback
      </a>
      <a href="#policies" class="px-6 py-3 bg-gray-100 text-gray-800 text-lg font-medium hover:bg-gray-200 transition border border-gray-300">
        Policies
      </a>
    </div>
  </div>
</section>

<!-- ABOUT -->
<section id="about" class="about-bg py-20 text-center relative">
  <div class="container mx-auto px-6 max-w-5xl relative z-10 animate-fadeInUp space-y-10">
    <div class="bg-white p-10 md:p-14 border-l-4 shadow-md hover:shadow-lg transition-shadow" style="border-color: #0688B4;">
      <h2 class="text-3xl font-bold text-gray-900 mb-6">About Job Poster</h2>
      <p class="text-gray-700 text-lg leading-relaxed">
        Job Poster is a modern platform connecting talented individuals with top companies.
        Our mission is to simplify job discovery, empower job seekers with insights,
        and help employers find their perfect match quickly and efficiently.
      </p>
    </div>

    <div class="flex flex-wrap justify-center gap-8">
      <div class="bg-white p-8 w-72 border border-gray-200 shadow-sm hover:shadow-md transition-all hover:-translate-y-1">
        <div class="p-4 rounded-full mb-4 w-fit mx-auto" style="background-color: rgba(6, 136, 180, 0.1);">
          <?= Icons::users('w-8 h-8') ?>
        </div>
        <h4 class="font-semibold text-gray-900 mb-2">For Job Seekers</h4>
        <p class="text-gray-600 text-sm">Discover jobs, apply easily, and manage your career goals in one place.</p>
      </div>

      <div class="bg-white p-8 w-72 border border-gray-200 shadow-sm hover:shadow-md transition-all hover:-translate-y-1">
        <div class="p-4 rounded-full mb-4 w-fit mx-auto" style="background-color: rgba(6, 136, 180, 0.1);">
          <?= Icons::briefcase('w-8 h-8') ?>
        </div>
        <h4 class="font-semibold text-gray-900 mb-2">For Employers</h4>
        <p class="text-gray-600 text-sm">Post job openings, find skilled candidates, and build your dream team.</p>
      </div>

      <div class="bg-white p-8 w-72 border border-gray-200 shadow-sm hover:shadow-md transition-all hover:-translate-y-1">
        <div class="p-4 rounded-full mb-4 w-fit mx-auto" style="background-color: rgba(6, 136, 180, 0.1);">
          <?= Icons::bar_chart('w-8 h-8') ?>
        </div>
        <h4 class="font-semibold text-gray-900 mb-2">Smart Matching</h4>
        <p class="text-gray-600 text-sm">Our system uses analytics to recommend the best matches for you.</p>
      </div>
    </div>
  </div>
</section>

<!-- BENEFITS -->
<section id="benefits" class="benefits-bg relative py-20">
  <div class="container mx-auto px-6 text-center animate-fadeInUp relative z-10">
    <div class="inline-block mx-auto mb-10 px-10 py-6 bg-white border-l-4 shadow-md" style="border-color: #0688B4;">
      <h2 class="text-3xl font-bold text-gray-900">Why Choose Job Poster?</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
      <div class="bg-white p-10 border border-gray-200 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all">
        <div class="p-4 rounded-full w-fit mx-auto mb-4" style="background-color: rgba(6, 136, 180, 0.1);">
          <?= Icons::zap('w-10 h-10') ?>
        </div>
        <h4 class="font-semibold text-lg text-gray-900 mb-2">Fast & Simple</h4>
        <p class="text-gray-700 text-sm leading-relaxed">Quickly apply and manage applications with a clean interface.</p>
      </div>

      <div class="bg-white p-10 border border-gray-200 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all">
        <div class="p-4 rounded-full w-fit mx-auto mb-4" style="background-color: rgba(6, 136, 180, 0.1);">
          <?= Icons::shield_check('w-10 h-10') ?>
        </div>
        <h4 class="font-semibold text-lg text-gray-900 mb-2">Secure Platform</h4>
        <p class="text-gray-700 text-sm leading-relaxed">Your personal data and activity are encrypted and stored safely.</p>
      </div>

      <div class="bg-white p-10 border border-gray-200 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all">
        <div class="p-4 rounded-full w-fit mx-auto mb-4" style="background-color: rgba(6, 136, 180, 0.1);">
          <?= Icons::award('w-10 h-10') ?>
        </div>
        <h4 class="font-semibold text-lg text-gray-900 mb-2">Verified Employers</h4>
        <p class="text-gray-700 text-sm leading-relaxed">Collaborate with trusted companies verified by our team.</p>
      </div>
    </div>
  </div>
</section>

<!-- PROMO VIDEO -->
<section id="promo" class="relative py-24 bg-gray-50">
  <div class="container mx-auto px-6 text-center animate-fadeInUp">
    <div class="inline-block mx-auto mb-10 px-10 py-6 bg-white border-l-4 shadow-md" style="border-color: #0688B4;">
      <h2 class="text-3xl font-bold text-gray-900">Watch Our Introduction Video</h2>
      <p class="text-gray-600 mt-3">Learn how Job Poster helps you find jobs and grow your career in minutes.</p>
    </div>

    <!-- Video container -->
    <div class="relative max-w-5xl mx-auto overflow-hidden border-2 border-gray-200 bg-white shadow-lg hover:shadow-xl transition-shadow">
      <div class="aspect-w-16 aspect-h-9">
        <iframe class="w-full h-[520px]" src="https://www.youtube.com/embed/your_video_id_here"
                title="Job Poster Intro Video" frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen>
        </iframe>
      </div>
    </div>
  </div>
</section>

<!-- NEWS -->
<section id="news" class="news-bg relative py-20">
  <div class="container mx-auto px-6 animate-fadeInUp relative z-10">
    <div class="inline-block mx-auto mb-12 px-10 py-6 bg-white border-l-4 shadow-md text-center" style="border-color: #0688B4;">
      <h2 class="text-3xl font-bold text-gray-900">Latest Job News & Insights</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <?php 
      $articles = [
        ['img'=>'1.png','title'=>'Top 5 In-demand Tech Skills in 2025','desc'=>'Stay ahead with the most sought-after skills in software development and AI.'],
        ['img'=>'2.png','title'=>'How to Build a Remote Career Successfully','desc'=>'Learn the habits and tools to thrive in the modern remote workspace.'],
        ['img'=>'3.png','title'=>'Navigating Job Market Trends in 2025','desc'=>'Insights into hiring shifts, automation, and emerging industries.']
      ];
      foreach ($articles as $a): ?>
      <article class="bg-white overflow-hidden border border-gray-200 shadow-sm hover:shadow-md transition-all hover:-translate-y-1">
        <img src="/Job_poster/public/images/jobs/<?= htmlspecialchars($a['img']) ?>" 
             class="w-full h-48 object-cover" alt="">
        <div class="p-6 text-left border-l-2" style="border-color: #0688B4;">
          <h4 class="font-semibold text-lg text-gray-900 mb-2"><?= htmlspecialchars($a['title']) ?></h4>
          <p class="text-gray-700 text-sm mb-4"><?= htmlspecialchars($a['desc']) ?></p>
          <a href="#" class="font-semibold hover:underline" style="color: #0688B4;">Read more →</a>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- FEEDBACK -->
<section id="feedback" class="feedback-bg relative py-20">
  <div class="container mx-auto px-6 text-center animate-fadeInUp relative z-10">
    <div class="inline-block mx-auto mb-12 px-10 py-6 bg-white border-l-4 shadow-md" style="border-color: #0688B4;">
      <h2 class="text-3xl font-bold text-gray-900">What Our Users Say</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <?php 
      $feedbacks = [
        ['text'=>'“I found my dream job in less than a week! The process was smooth and easy.”','name'=>'Anna Nguyen','role'=>'Software Engineer'],
        ['text'=>'“A clean design, useful filters, and verified companies. Highly recommended!”','name'=>'Minh Tran','role'=>'UI/UX Designer'],
        ['text'=>'“Posting jobs and managing applicants has never been this efficient.”','name'=>'HR at CloudTech','role'=>'Recruitment Manager']
      ];
      foreach ($feedbacks as $f): ?>
      <div class="bg-white p-10 border border-gray-200 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all">
        <p class="text-gray-700 italic mb-4 leading-relaxed"><?= htmlspecialchars($f['text']) ?></p>
        <div class="border-t border-gray-200 pt-4 mt-4">
          <h4 class="font-semibold text-gray-900 mb-1"><?= htmlspecialchars($f['name']) ?></h4>
          <p class="text-gray-500 text-sm"><?= htmlspecialchars($f['role']) ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- POLICIES -->
<section id="policies" class="relative py-20 bg-gray-50">
  <div class="container mx-auto px-6 animate-fadeInUp">
    <div class="inline-block mx-auto mb-12 px-10 py-6 bg-white border-l-4 shadow-md text-center" style="border-color: #0688B4;">
      <h2 class="text-3xl font-bold text-gray-900">Our Policies</h2>
      <p class="text-gray-600 mt-3">Guidelines for employers and job seekers</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 max-w-6xl mx-auto">
      <!-- For Employers -->
      <div class="bg-white border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
        <div class="p-6 border-b border-gray-200 border-l-4" style="border-left-color: #0688B4;">
          <h3 class="text-2xl font-bold text-gray-900 mb-2">For Employers</h3>
          <p class="text-gray-600 text-sm">Guidelines for posting jobs and hiring</p>
        </div>
        <div class="p-6 space-y-4">
          <div class="border-l-2 border-gray-300 pl-4">
            <h4 class="font-semibold text-gray-900 mb-1">Job Posting Requirements</h4>
            <p class="text-gray-700 text-sm">All job postings must include accurate job descriptions, clear requirements, and realistic salary ranges. Misleading information is strictly prohibited.</p>
          </div>
          <div class="border-l-2 border-gray-300 pl-4">
            <h4 class="font-semibold text-gray-900 mb-1">Non-Discrimination Policy</h4>
            <p class="text-gray-700 text-sm">Employers must not discriminate based on age, gender, race, religion, or disability. All candidates should be evaluated fairly based on qualifications.</p>
          </div>
          <div class="border-l-2 border-gray-300 pl-4">
            <h4 class="font-semibold text-gray-900 mb-1">Candidate Privacy</h4>
            <p class="text-gray-700 text-sm">Respect applicant privacy. Personal information must be used solely for recruitment purposes and handled according to data protection regulations.</p>
          </div>
          <div class="border-l-2 border-gray-300 pl-4">
            <h4 class="font-semibold text-gray-900 mb-1">Timely Communication</h4>
            <p class="text-gray-700 text-sm">Respond to applications within a reasonable timeframe and keep candidates informed about their application status.</p>
          </div>
        </div>
      </div>

      <!-- For Job Seekers -->
      <div class="bg-white border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
        <div class="p-6 border-b border-gray-200 border-l-4" style="border-left-color: #0688B4;">
          <h3 class="text-2xl font-bold text-gray-900 mb-2">For Job Seekers</h3>
          <p class="text-gray-600 text-sm">Guidelines for applying and using our platform</p>
        </div>
        <div class="p-6 space-y-4">
          <div class="border-l-2 border-gray-300 pl-4">
            <h4 class="font-semibold text-gray-900 mb-1">Honest Applications</h4>
            <p class="text-gray-700 text-sm">Provide accurate and truthful information in your profile and applications. Misrepresentation of skills or experience may result in account suspension.</p>
          </div>
          <div class="border-l-2 border-gray-300 pl-4">
            <h4 class="font-semibold text-gray-900 mb-1">Professional Conduct</h4>
            <p class="text-gray-700 text-sm">Maintain professional communication with employers. Respond promptly to interview invitations and notify employers if you're no longer interested.</p>
          </div>
          <div class="border-l-2 border-gray-300 pl-4">
            <h4 class="font-semibold text-gray-900 mb-1">Account Security</h4>
            <p class="text-gray-700 text-sm">Keep your login credentials secure. Do not share your account with others. Report any suspicious activity immediately.</p>
          </div>
          <div class="border-l-2 border-gray-300 pl-4">
            <h4 class="font-semibold text-gray-900 mb-1">Respect & Feedback</h4>
            <p class="text-gray-700 text-sm">Treat all employers with respect. Provide constructive feedback about your experience to help us improve our platform.</p>
          </div>
        </div>
      </div>
    </div>

    <!-- General Terms -->
    <div class="mt-10 max-w-4xl mx-auto bg-white border border-gray-200 p-8 shadow-sm">
      <h3 class="text-xl font-bold text-gray-900 mb-4 border-b border-gray-200 pb-3">General Terms</h3>
      <div class="space-y-3 text-gray-700 text-sm leading-relaxed">
        <p><strong>Privacy:</strong> We protect your personal data according to international privacy standards. Your information will never be sold to third parties.</p>
        <p><strong>Content Ownership:</strong> Users retain ownership of their content but grant Job Poster the right to display it on our platform.</p>
        <p><strong>Prohibited Activities:</strong> Spam, fraudulent postings, harassment, or any illegal activities will result in immediate account termination.</p>
        <p><strong>Platform Updates:</strong> We reserve the right to modify these policies. Users will be notified of significant changes via email.</p>
      </div>
    </div>
  </div>
</section>

<!-- Script ẩn header khi scroll -->
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

