<?php 
$pageTitle = 'Homepage | Job Poster';
$pageTitle = 'Homepage | Job Poster';
include __DIR__ . '/../layouts/public_header.php';
?>

<style>
/* ========================================
   PRELOADER STYLES WITH VIDEO
======================================== */
#preloader {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100vh;
  background: linear-gradient(180deg, #0a4d5c 0%, #1a8a9d 50%, #2db8ac 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 9999;
  overflow: hidden;
}

#preloader-video {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  min-width: 100%;
  min-height: 100%;
  width: auto;
  height: auto;
  object-fit: cover;
  opacity: 0.3;
}

.preloader-content {
  position: relative;
  z-index: 2;
  text-align: center;
  padding: 2rem;
}

.preloader-logo {
  font-size: 4rem;
  font-weight: 900;
  color: white;
  text-transform: uppercase;
  letter-spacing: -0.02em;
  text-shadow: 4px 4px 0px rgba(45, 184, 172, 0.4);
  margin-bottom: 2rem;
  line-height: 1;
}

.preloader-logo span {
  display: inline-block;
  background: linear-gradient(135deg, #b4ff39 0%, #2db8ac 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.preloader-tagline {
  font-size: 1.2rem;
  font-weight: 700;
  color: white;
  text-transform: uppercase;
  letter-spacing: 0.15em;
  margin-bottom: 3rem;
  opacity: 0.9;
}

.preloader-bar-container {
  width: 300px;
  height: 8px;
  background: rgba(255, 255, 255, 0.2);
  border: 3px solid white;
  margin: 0 auto;
  position: relative;
  overflow: hidden;
}

.preloader-bar {
  height: 100%;
  background: linear-gradient(90deg, #b4ff39 0%, #2db8ac 100%);
  width: 0%;
  transition: width 0.3s ease;
}

.preloader-percent {
  color: white;
  font-weight: 900;
  font-size: 1.5rem;
  margin-top: 1rem;
  letter-spacing: 0.1em;
}

.preloader-shapes {
  position: absolute;
  inset: 0;
  pointer-events: none;
  z-index: 1;
}

.preloader-shape {
  position: absolute;
  opacity: 0.1;
}

.preloader-shape-1 {
  top: 10%;
  left: 10%;
  width: 100px;
  height: 100px;
  border: 4px solid white;
}

.preloader-shape-2 {
  bottom: 15%;
  right: 15%;
  width: 80px;
  height: 80px;
  background: white;
  transform: rotate(45deg);
}

.preloader-shape-3 {
  top: 30%;
  right: 20%;
  width: 60px;
  height: 60px;
  background: white;
  border-radius: 50%;
}

/* Y2K Retro Background - No overlays */
.hero-bg { 
  background: linear-gradient(180deg, #0a4d5c 0%, #1a8a9d 50%, #2db8ac 100%);
  position: relative;
  overflow: hidden;
}

.hero-bg::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-image: 
    radial-gradient(circle at 20% 30%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
    radial-gradient(circle at 80% 70%, rgba(45, 184, 172, 0.2) 0%, transparent 50%);
  pointer-events: none;
}

.about-bg { 
  background: #f5f5f5;
}

.benefits-bg { 
  background: linear-gradient(to bottom, #ffffff 0%, #e8f7f6 100%);
}

.news-bg { 
  background: #ffffff;
}

.feedback-bg { 
  background: linear-gradient(135deg, #f0fffe 0%, #e0f4f3 100%);
}

.policies-bg {
  background: #fafafa;
}

/* Modern animations */
@keyframes float {
  0%, 100% { transform: translateY(0px) translateX(0px); }
  33% { transform: translateY(-20px) translateX(10px); }
  66% { transform: translateY(10px) translateX(-10px); }
}

@keyframes fadeInUp { 
  from { opacity: 0; transform: translateY(40px); } 
  to { opacity: 1; transform: translateY(0); } 
}

@keyframes fadeInLeft {
  from { opacity: 0; transform: translateX(-40px); }
  to { opacity: 1; transform: translateX(0); }
}

@keyframes fadeInRight {
  from { opacity: 0; transform: translateX(40px); }
  to { opacity: 1; transform: translateX(0); }
}

@keyframes scaleIn {
  from { opacity: 0; transform: scale(0.9); }
  to { opacity: 1; transform: scale(1); }
}

@keyframes slideInLeft {
  from { opacity: 0; transform: translateX(-40px); }
  to { opacity: 1; transform: translateX(0); }
}

@keyframes slideInRight {
  from { opacity: 0; transform: translateX(40px); }
  to { opacity: 1; transform: translateX(0); }
}

@keyframes pulse {
  0%, 100% { transform: scale(1); }
  50% { transform: scale(1.05); }
}

@keyframes gradientShift {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}

@keyframes scrollHorizontal {
  0% { transform: translateX(0); }
  100% { transform: translateX(-50%); }
}

.animate-fadeInUp { 
  animation: fadeInUp 0.8s ease-out forwards; 
}

.animate-slideInLeft {
  animation: slideInLeft 0.8s ease-out forwards;
}

.animate-slideInRight {
  animation: slideInRight 0.8s ease-out forwards;
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

/* Y2K Retro Color Palette */
:root {
  --primary-teal: #0688B4;
  --primary-cyan: #2db8ac;
  --primary-dark: #0a4d5c;
  --accent-lime: #b4ff39;
  --accent-pink: #ff6b9d;
  --accent-purple: #c77dff;
  --accent-orange: #ff9e00;
}

/* Y2K Bold text effect */
.y2k-text {
  color: #0a4d5c;
  text-shadow: 3px 3px 0px rgba(45, 184, 172, 0.3);
  font-weight: 900;
  letter-spacing: -0.02em;
}

/* Retro card styles */
.retro-card {
  background: white;
  border: 3px solid #0a4d5c;
  box-shadow: 8px 8px 0px rgba(10, 77, 92, 0.15);
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
}

.retro-card:hover {
  transform: translate(-4px, -4px) !important;
  box-shadow: 12px 12px 0px rgba(10, 77, 92, 0.2) !important;
}

/* Ensure feature cards have hover effect */
#about .retro-card {
  transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

#about .retro-card:hover {
  transform: translate(-4px, -4px) !important;
  box-shadow: 12px 12px 0px rgba(10, 77, 92, 0.2) !important;
}

/* News carousel styles */
.news-carousel-container {
  overflow: hidden;
  position: relative;
  width: 100%;
  mask-image: linear-gradient(to right, transparent 0%, black 5%, black 95%, transparent 100%);
  -webkit-mask-image: linear-gradient(to right, transparent 0%, black 5%, black 95%, transparent 100%);
}

.news-carousel-track {
  display: flex;
  gap: 2rem;
  animation: scrollHorizontal 40s linear infinite;
  width: fit-content;
  will-change: transform;
}

.news-carousel-track:hover {
  animation-play-state: paused;
}

.news-card {
  flex: 0 0 calc(33.333% - 1.33rem);
  min-width: 320px;
  max-width: 400px;
}

@media (max-width: 1024px) {
  .news-card {
    flex: 0 0 calc(50% - 1rem);
    min-width: 300px;
  }
}

@media (max-width: 768px) {
  .news-card {
    flex: 0 0 calc(100% - 2rem);
    min-width: 280px;
    max-width: 100%;
  }
}

/* Y2K Badge */
.y2k-badge {
  background: linear-gradient(135deg, #b4ff39 0%, #2db8ac 100%);
  color: #0a4d5c;
  font-weight: 900;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  padding: 8px 20px;
  border: 2px solid #0a4d5c;
  box-shadow: 3px 3px 0px #0a4d5c;
}

/* Button Y2K style */
.btn-y2k {
  position: relative;
  font-weight: 900;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  border: 3px solid #0a4d5c;
  box-shadow: 5px 5px 0px #0a4d5c;
  transition: all 0.2s ease;
}

.btn-y2k:hover {
  transform: translate(-2px, -2px);
  box-shadow: 7px 7px 0px #0a4d5c;
}

.btn-y2k:active {
  transform: translate(2px, 2px);
  box-shadow: 3px 3px 0px #0a4d5c;
}
</style>

<!-- ========================================
     PRELOADER WITH VIDEO AND GSAP
======================================== -->
<div id="preloader">
  <!-- Background Video -->
  <video id="preloader-video" autoplay muted loop playsinline>
    <source src="https://assets.mixkit.co/videos/preview/mixkit-digital-animation-of-futuristic-devices-44628-large.mp4" type="video/mp4">
    Your browser does not support the video tag.
  </video>

  <!-- Animated Shapes -->
  <div class="preloader-shapes">
    <div class="preloader-shape preloader-shape-1"></div>
    <div class="preloader-shape preloader-shape-2"></div>
    <div class="preloader-shape preloader-shape-3"></div>
  </div>

  <!-- Content -->
  <div class="preloader-content">
    <div class="preloader-logo">
      JOB <span>POSTER</span>
    </div>
    <div class="preloader-tagline">
      Find Your Dream Job
    </div>
    <div class="preloader-bar-container">
      <div class="preloader-bar" id="preloader-bar"></div>
    </div>
    <div class="preloader-percent" id="preloader-percent">0%</div>
  </div>
</div>

<!-- HERO -->
<section class="hero-bg relative min-h-screen flex flex-col items-center justify-center text-center overflow-hidden">
  <!-- Y2K Geometric shapes -->
  <div class="absolute inset-0 opacity-10 pointer-events-none">
    <div class="absolute top-20 left-10 w-40 h-40 border-4 border-white" style="animation: float 8s ease-in-out infinite;"></div>
    <div class="absolute bottom-32 right-20 w-32 h-32 bg-white/20" style="transform: rotate(45deg); animation: float 6s ease-in-out infinite 1s;"></div>
    <div class="absolute top-1/3 right-1/4 w-24 h-24" style="clip-path: polygon(50% 0%, 100% 50%, 50% 100%, 0% 50%); background: rgba(255,255,255,0.2); animation: float 7s ease-in-out infinite 2s;"></div>
  </div>

  <div class="container mx-auto px-6 relative z-10">
    <!-- Main hero content -->
    <div class="mb-12 animate-fadeInUp">
      <h1 class="text-6xl md:text-8xl font-black text-white mb-6 leading-none tracking-tight">
        FIND YOUR<br>
        <span class="inline-block" style="background: linear-gradient(135deg, #b4ff39 0%, #2db8ac 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; text-shadow: none;">DREAM JOB</span>
      </h1>
      <p class="text-white text-xl md:text-2xl font-bold mb-12 max-w-3xl mx-auto uppercase tracking-wide">
        Connect • Unlock • Build Your Career
      </p>
      
      <!-- Primary CTA -->
      <div class="flex flex-col sm:flex-row gap-6 justify-center items-center mb-12">
        <a href="/Job_poster/public/jobs"
           class="btn-y2k group text-white px-14 py-5 text-lg bg-gradient-to-r from-lime-400 to-cyan-400 hover:from-lime-300 hover:to-cyan-300">
          <span class="flex items-center gap-3">
            EXPLORE JOBS
            <svg class="w-6 h-6 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
            </svg>
          </span>
        </a>
        <a href="#about"
           class="btn-y2k px-12 py-5 text-lg bg-white text-gray-900 hover:bg-gray-100">
          LEARN MORE
        </a>
      </div>
    </div>

    <!-- Quick navigation pills -->
    <div class="inline-flex flex-wrap justify-center gap-4 px-8 py-6 bg-white/10 backdrop-blur-sm border-3 border-white/30 animate-fadeInUp" style="animation-delay: 0.2s;">
      <a href="#about" class="px-6 py-2 bg-white/90 hover:bg-white text-gray-900 font-bold text-sm uppercase tracking-wide transition-all duration-300">
        About
      </a>
      <a href="#benefits" class="px-6 py-2 bg-white/90 hover:bg-white text-gray-900 font-bold text-sm uppercase tracking-wide transition-all duration-300">
        Benefits
      </a>
      <a href="#promo" class="px-6 py-2 bg-white/90 hover:bg-white text-gray-900 font-bold text-sm uppercase tracking-wide transition-all duration-300">
        Video
      </a>
      <a href="#news" class="px-6 py-2 bg-white/90 hover:bg-white text-gray-900 font-bold text-sm uppercase tracking-wide transition-all duration-300">
        News
      </a>
      <a href="#feedback" class="px-6 py-2 bg-white/90 hover:bg-white text-gray-900 font-bold text-sm uppercase tracking-wide transition-all duration-300">
        Reviews
      </a>
      <a href="#policies" class="px-6 py-2 bg-white/90 hover:bg-white text-gray-900 font-bold text-sm uppercase tracking-wide transition-all duration-300">
        Policies
      </a>
    </div>
    
    <!-- Scroll indicator -->
    <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 animate-bounce">
      <svg class="w-10 h-10 text-white font-bold" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
        <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
      </svg>
    </div>
  </div>
</section>

<!-- ABOUT -->
<section id="about" class="about-bg py-24 text-center relative">
  <div class="container mx-auto px-6 max-w-6xl relative z-10">
    <!-- Section header -->
    <div class="mb-16 animate-fadeInUp">
      <span class="y2k-badge inline-block mb-6">About Us</span>
      <h2 class="text-5xl md:text-7xl font-black mb-6 uppercase tracking-tight y2k-text">
        Connecting Talent<br>
        With Opportunity
      </h2>
      <p class="text-gray-700 text-lg max-w-3xl mx-auto leading-relaxed font-medium">
        Job Poster is a modern platform connecting talented individuals with top companies.
        Our mission is to simplify job discovery, empower job seekers with insights,
        and help employers find their perfect match quickly and efficiently.
      </p>
    </div>

    <!-- Feature cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <!-- Card 1 -->
      <div class="retro-card p-8 group bg-white" style="animation: slideInLeft 0.8s ease-out;">
        <div class="mb-6">
          <div class="w-24 h-24 mx-auto flex items-center justify-center bg-gradient-to-br from-lime-400 to-cyan-400 border-3 border-gray-900">
            <?= Icons::users('w-12 h-12 text-gray-900') ?>
          </div>
        </div>
        <h4 class="font-black text-2xl text-gray-900 mb-3 uppercase tracking-tight">For Job Seekers</h4>
        <p class="text-gray-700 text-base leading-relaxed">Discover opportunities, apply with ease, and manage your career goals all in one powerful platform.</p>
      </div>

      <!-- Card 2 -->
      <div class="retro-card p-8 group bg-white" style="animation: slideInLeft 0.8s ease-out 0.1s; animation-fill-mode: both;">
        <div class="mb-6">
          <div class="w-24 h-24 mx-auto flex items-center justify-center bg-gradient-to-br from-pink-400 to-purple-400 border-3 border-gray-900">
            <?= Icons::briefcase('w-12 h-12 text-gray-900') ?>
          </div>
        </div>
        <h4 class="font-black text-2xl text-gray-900 mb-3 uppercase tracking-tight">For Employers</h4>
        <p class="text-gray-700 text-base leading-relaxed">Post openings, connect with skilled talent, and build your dream team faster than ever before.</p>
      </div>

      <!-- Card 3 -->
      <div class="retro-card p-8 group bg-white" style="animation: slideInLeft 0.8s ease-out 0.2s; animation-fill-mode: both;">
        <div class="mb-6">
          <div class="w-24 h-24 mx-auto flex items-center justify-center bg-gradient-to-br from-orange-400 to-pink-400 border-3 border-gray-900">
            <?= Icons::bar_chart('w-12 h-12 text-gray-900') ?>
          </div>
        </div>
        <h4 class="font-black text-2xl text-gray-900 mb-3 uppercase tracking-tight">Smart Matching</h4>
        <p class="text-gray-700 text-base leading-relaxed">Powered by intelligent analytics to match the perfect candidates with the right opportunities.</p>
      </div>
    </div>
  </div>
</section>

<!-- BENEFITS -->
<section id="benefits" class="benefits-bg relative py-24">
  <div class="container mx-auto px-6 text-center relative z-10">
    <!-- Section header -->
    <div class="mb-16 animate-fadeInUp">
      <span class="y2k-badge inline-block mb-6">Why Us</span>
      <h2 class="text-5xl md:text-7xl font-black mb-6 uppercase tracking-tight y2k-text">
        Why Choose<br>
        Job Poster?
      </h2>
    </div>

    <!-- Benefits grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
      <!-- Benefit 1 -->
      <div class="retro-card p-10 text-center group bg-gradient-to-br from-cyan-100 to-lime-100">
        <div class="inline-flex items-center justify-center w-20 h-20 mb-6 bg-white border-3 border-gray-900">
          <?= Icons::zap('w-10 h-10 text-gray-900') ?>
        </div>
        <h4 class="font-black text-2xl text-gray-900 mb-4 uppercase">Fast & Simple</h4>
        <p class="text-gray-700 leading-relaxed font-medium">Apply to jobs in seconds with our streamlined interface. No complicated forms, no hassle—just results.</p>
      </div>

      <!-- Benefit 2 -->
      <div class="retro-card p-10 text-center group bg-gradient-to-br from-pink-100 to-purple-100">
        <div class="inline-flex items-center justify-center w-20 h-20 mb-6 bg-white border-3 border-gray-900">
          <?= Icons::shield_check('w-10 h-10 text-gray-900') ?>
        </div>
        <h4 class="font-black text-2xl text-gray-900 mb-4 uppercase">Secure Platform</h4>
        <p class="text-gray-700 leading-relaxed font-medium">Bank-level encryption protects your data. Your information is safe, secure, and never shared without permission.</p>
      </div>

      <!-- Benefit 3 -->
      <div class="retro-card p-10 text-center group bg-gradient-to-br from-orange-100 to-pink-100">
        <div class="inline-flex items-center justify-center w-20 h-20 mb-6 bg-white border-3 border-gray-900">
          <?= Icons::award('w-10 h-10 text-gray-900') ?>
        </div>
        <h4 class="font-black text-2xl text-gray-900 mb-4 uppercase">Verified Employers</h4>
        <p class="text-gray-700 leading-relaxed font-medium">Connect only with legitimate companies. Every employer is verified by our dedicated team.</p>
      </div>
    </div>
  </div>
</section>

<!-- PROMO VIDEO -->
<section id="promo" class="relative py-24 bg-white">
  <div class="container mx-auto px-6 text-center">
    <!-- Section header -->
    <div class="mb-16 animate-fadeInUp">
      <span class="y2k-badge inline-block mb-6">Watch & Learn</span>
      <h2 class="text-5xl md:text-7xl font-black mb-6 uppercase tracking-tight y2k-text">
        See Job Poster<br>
        In Action
      </h2>
      <p class="text-gray-700 text-lg max-w-3xl mx-auto font-medium">Discover how our platform revolutionizes job hunting in just a few minutes.</p>
    </div>

    <!-- Video container -->
    <div class="retro-card max-w-5xl mx-auto bg-white p-6">
      <div class="aspect-w-16 aspect-h-9 overflow-hidden border-3 border-gray-900">
        <iframe class="w-full h-[520px]" src="https://www.youtube.com/embed/your_video_id_here"
                title="Job Poster Intro Video" frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen>
        </iframe>
      </div>
    </div>
  </div>
</section>

<!-- NEWS -->
<section id="news" class="news-bg relative py-24">
  <div class="container mx-auto px-6 relative z-10">
    <!-- Section header -->
    <div class="mb-16 text-center animate-fadeInUp">
      <span class="y2k-badge inline-block mb-6">Latest Updates</span>
      <h2 class="text-5xl md:text-7xl font-black mb-6 uppercase tracking-tight y2k-text">
        Job Market Insights<br>
        & Career Tips
      </h2>
    </div>

    <!-- News carousel -->
    <div class="news-carousel-container max-w-6xl mx-auto">
      <div class="news-carousel-track">
        <?php 
        // ============================================
        // NEWS ARTICLES - ĐIỀN THÔNG TIN BÀI VIẾT Ở ĐÂY
        // ============================================
        // Format: ['img'=>'tên_file.jpg', 'title'=>'Tiêu đề', 'desc'=>'Mô tả ngắn', 'url'=>'https://link-thật.com']
        // - img: Tên file ảnh trong thư mục /public/images/jobs/
        // - title: Tiêu đề bài viết
        // - desc: Mô tả ngắn (1-2 câu)
        // - url: Link thật đến bài viết (có thể là link ngoài hoặc link trong website)
        // ============================================
        $articles = [
          [
            'img'=>'1.png',
            'title'=>'Jobs Report: Hiring Flat for 2026 Grads',
            'desc'=>'Employers are increasingly using skills-based hiring processes that value college internship and co-op experience over GPAs.',
            'url'=>'https://www.insidehighered.com/news/students/careers/2025/11/17/jobs-report-hiring-flat-2026-grads'  // ← ĐIỀN LINK THẬT VÀO ĐÂY
          ],
          [
            'img'=>'2.png',
            'title'=>'How Artificial Intelligence Will Change the World',
            'desc'=>'You would have been living under a rock if you did not know how artificial intelligence is set to affect jobs in 2026-2030.',
            'url'=>'https://www.nexford.edu/insights/how-will-ai-affect-jobs'  // ← ĐIỀN LINK THẬT VÀO ĐÂY
          ],
          [
            'img'=>'3.png',
            'title'=>'Nearly 4 in 10 companies will replace workers with AI by 2026, survey shows',
            'desc'=>'High-salary employees, those without AI skills, recently hired workers and entry-level employees face the highest risks for layoffs.',
            'url'=>'https://www.hrdive.com/news/companies-will-replace-workers-with-ai-by-2026/760729/'  // ← ĐIỀN LINK THẬT VÀO ĐÂY
          ],
          [
            'img'=>'5.png',
            'title'=>'One third of companies in Germany to cut jobs in 2026',
            'desc'=>'According to a report published by the German Economic Institute (IW), one in three companies operating in Germany plan to cut jobs in 2026. The outlook is particularly poor in manufacturing.',
            'url'=>'https://www.iamexpat.de/career/employment-news/one-third-companies-germany-cut-jobs-2026'  // ← ĐIỀN LINK THẬT VÀO ĐÂY
          ],
          [
            'img'=>'placeholder.jpg',
            'title'=>'Top 8 Hiring Challenges of 2026 (And How Your Organization Can Prepare)',
            'desc'=>'Learn how to create a compelling online presence that attracts top employers and opportunities.',
            'url'=>'https://www.kellyservices.us/news-and-insights/top-hiring-challenges-2026'  // ← ĐIỀN LINK THẬT VÀO ĐÂY
          ]
          // Có thể thêm bài viết mới ở đây:
          // [
          //   'img'=>'tên_file.jpg',
          //   'title'=>'Tiêu đề bài viết mới',
          //   'desc'=>'Mô tả ngắn',
          //   'url'=>'https://link-thật.com'
          // ],
        ];
        // Duplicate articles for seamless loop
        $articles = array_merge($articles, $articles);
        foreach ($articles as $i => $a): ?>
        <article class="news-card retro-card overflow-hidden group bg-white">
          <!-- Image -->
          <div class="overflow-hidden border-b-3 border-gray-900">
            <img src="/Job_poster/public/images/jobs/<?= htmlspecialchars($a['img']) ?>" 
                 class="w-full h-56 object-cover" alt="">
          </div>
          
          <!-- Content -->
          <div class="p-6">
            <h4 class="font-black text-xl text-gray-900 mb-3 uppercase"><?= htmlspecialchars($a['title']) ?></h4>
            <p class="text-gray-700 mb-5 leading-relaxed font-medium"><?= htmlspecialchars($a['desc']) ?></p>
            <a href="<?= htmlspecialchars($a['url'] ?? '#') ?>" 
               <?= (isset($a['url']) && strpos($a['url'], 'http') === 0) ? 'target="_blank" rel="noopener noreferrer"' : '' ?>
               class="btn-y2k inline-block px-6 py-2 text-sm bg-gradient-to-r from-lime-400 to-cyan-400">
              READ MORE
            </a>
          </div>
        </article>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- FEEDBACK -->
<section id="feedback" class="feedback-bg relative py-24">
  <div class="container mx-auto px-6 text-center relative z-10">
    <!-- Section header -->
    <div class="mb-16 animate-fadeInUp">
      <span class="y2k-badge inline-block mb-6">Testimonials</span>
      <h2 class="text-5xl md:text-7xl font-black mb-6 uppercase tracking-tight y2k-text">
        Loved By Thousands<br>
        Of Users
      </h2>
    </div>

    <!-- Testimonials grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
      <?php 
      $feedbacks = [
        ['text'=>'I found my dream job in less than a week! The process was smooth and easy.','name'=>'Anna Nguyen','role'=>'Software Engineer'],
        ['text'=>'A clean design, useful filters, and verified companies. Highly recommended!','name'=>'Minh Tran','role'=>'UI/UX Designer'],
        ['text'=>'Posting jobs and managing applicants has never been this efficient.','name'=>'HR at CloudTech','role'=>'Recruitment Manager']
      ];
      $colors = ['from-lime-100 to-cyan-100', 'from-pink-100 to-purple-100', 'from-orange-100 to-yellow-100'];
      foreach ($feedbacks as $i => $f): ?>
      <div class="retro-card p-8 bg-gradient-to-br <?= $colors[$i] ?>" style="animation: slideInRight <?= 0.6 + ($i * 0.1) ?>s ease-out;">
        <!-- Stars -->
        <div class="flex gap-1 mb-5 justify-center">
          <?php for($s = 0; $s < 5; $s++): ?>
          <svg class="w-6 h-6 text-gray-900 fill-current" viewBox="0 0 20 20">
            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
          </svg>
          <?php endfor; ?>
        </div>
        
        <p class="text-gray-900 text-lg mb-6 leading-relaxed font-bold">
          "<?= htmlspecialchars($f['text']) ?>"
        </p>
        
        <!-- User info -->
        <div class="pt-6 border-t-3 border-gray-900">
          <div class="w-16 h-16 mx-auto mb-3 bg-white border-3 border-gray-900 flex items-center justify-center text-gray-900 font-black text-2xl">
            <?= strtoupper(substr($f['name'], 0, 1)) ?>
          </div>
          <h4 class="font-black text-gray-900 uppercase text-sm"><?= htmlspecialchars($f['name']) ?></h4>
          <p class="text-gray-700 text-sm font-medium"><?= htmlspecialchars($f['role']) ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- POLICIES -->
<section id="policies" class="policies-bg relative py-24">
  <div class="container mx-auto px-6">
    <!-- Section header -->
    <div class="mb-16 text-center animate-fadeInUp">
      <span class="y2k-badge inline-block mb-6">Guidelines</span>
      <h2 class="text-5xl md:text-7xl font-black mb-6 uppercase tracking-tight y2k-text">
        Our Policies
      </h2>
      <p class="text-gray-700 text-lg max-w-2xl mx-auto font-medium">Clear guidelines to ensure a fair and professional experience for everyone</p>
    </div>

    <!-- Policy cards -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 max-w-6xl mx-auto">
      <!-- For Employers -->
      <div class="retro-card bg-gradient-to-br from-lime-100 to-cyan-100" style="animation: slideInLeft 0.8s ease-out;">
        <div class="p-8 border-b-3 border-gray-900">
          <div class="inline-flex items-center justify-center w-16 h-16 mb-4 bg-white border-3 border-gray-900">
            <?= Icons::briefcase('w-8 h-8 text-gray-900') ?>
          </div>
          <h3 class="text-3xl font-black text-gray-900 mb-2 uppercase">For Employers</h3>
          <p class="text-gray-700 font-medium">Guidelines for posting jobs and hiring</p>
        </div>
        <div class="p-8 space-y-5">
          <div>
            <h4 class="font-black text-gray-900 mb-2 uppercase text-sm">• Job Posting Requirements</h4>
            <p class="text-gray-700 text-sm leading-relaxed">All job postings must include accurate job descriptions, clear requirements, and realistic salary ranges. Misleading information is strictly prohibited.</p>
          </div>
          <div>
            <h4 class="font-black text-gray-900 mb-2 uppercase text-sm">• Non-Discrimination Policy</h4>
            <p class="text-gray-700 text-sm leading-relaxed">Employers must not discriminate based on age, gender, race, religion, or disability. All candidates should be evaluated fairly based on qualifications.</p>
          </div>
          <div>
            <h4 class="font-black text-gray-900 mb-2 uppercase text-sm">• Candidate Privacy</h4>
            <p class="text-gray-700 text-sm leading-relaxed">Respect applicant privacy. Personal information must be used solely for recruitment purposes and handled according to data protection regulations.</p>
          </div>
          <div>
            <h4 class="font-black text-gray-900 mb-2 uppercase text-sm">• Timely Communication</h4>
            <p class="text-gray-700 text-sm leading-relaxed">Respond to applications within a reasonable timeframe and keep candidates informed about their application status.</p>
          </div>
        </div>
      </div>

      <!-- For Job Seekers -->
      <div class="retro-card bg-gradient-to-br from-pink-100 to-purple-100" style="animation: slideInRight 0.8s ease-out;">
        <div class="p-8 border-b-3 border-gray-900">
          <div class="inline-flex items-center justify-center w-16 h-16 mb-4 bg-white border-3 border-gray-900">
            <?= Icons::users('w-8 h-8 text-gray-900') ?>
          </div>
          <h3 class="text-3xl font-black text-gray-900 mb-2 uppercase">For Job Seekers</h3>
          <p class="text-gray-700 font-medium">Guidelines for applying and using our platform</p>
        </div>
        <div class="p-8 space-y-5">
          <div>
            <h4 class="font-black text-gray-900 mb-2 uppercase text-sm">• Honest Applications</h4>
            <p class="text-gray-700 text-sm leading-relaxed">Provide accurate and truthful information in your profile and applications. Misrepresentation of skills or experience may result in account suspension.</p>
          </div>
          <div>
            <h4 class="font-black text-gray-900 mb-2 uppercase text-sm">• Professional Conduct</h4>
            <p class="text-gray-700 text-sm leading-relaxed">Maintain professional communication with employers. Respond promptly to interview invitations and notify employers if you're no longer interested.</p>
          </div>
          <div>
            <h4 class="font-black text-gray-900 mb-2 uppercase text-sm">• Account Security</h4>
            <p class="text-gray-700 text-sm leading-relaxed">Keep your login credentials secure. Do not share your account with others. Report any suspicious activity immediately.</p>
          </div>
          <div>
            <h4 class="font-black text-gray-900 mb-2 uppercase text-sm">• Respect & Feedback</h4>
            <p class="text-gray-700 text-sm leading-relaxed">Treat all employers with respect. Provide constructive feedback about your experience to help us improve our platform.</p>
          </div>
        </div>
      </div>
    </div>

    <!-- General Terms -->
    <div class="mt-16 max-w-5xl mx-auto">
      <div class="retro-card p-10 bg-gradient-to-br from-orange-100 to-yellow-100">
        <div class="flex items-center gap-3 mb-8 pb-6 border-b-3 border-gray-900">
          <div class="w-14 h-14 flex items-center justify-center bg-white border-3 border-gray-900">
            <svg class="w-7 h-7 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
          </div>
          <h3 class="text-3xl font-black text-gray-900 uppercase">General Terms</h3>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-gray-700">
          <div>
            <p class="leading-relaxed font-medium"><strong class="text-gray-900 font-black uppercase text-sm">• Privacy:</strong> We protect your personal data according to international privacy standards. Your information will never be sold to third parties.</p>
          </div>
          <div>
            <p class="leading-relaxed font-medium"><strong class="text-gray-900 font-black uppercase text-sm">• Content Ownership:</strong> Users retain ownership of their content but grant Job Poster the right to display it on our platform.</p>
          </div>
          <div>
            <p class="leading-relaxed font-medium"><strong class="text-gray-900 font-black uppercase text-sm">• Prohibited Activities:</strong> Spam, fraudulent postings, harassment, or any illegal activities will result in immediate account termination.</p>
          </div>
          <div>
            <p class="leading-relaxed font-medium"><strong class="text-gray-900 font-black uppercase text-sm">• Platform Updates:</strong> We reserve the right to modify these policies. Users will be notified of significant changes via email.</p>
          </div>
        </div>
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
  let isUserScrolling = false;
  let scrollTimeout;
  let autoScrollInterval;
  let currentSectionIndex = 0;

  // Sections to auto-scroll through
  const sections = ['#about', '#benefits', '#promo', '#news', '#feedback', '#policies'];
  
  // Function to scroll to a section
  function scrollToSection(index) {
    const sectionId = sections[index];
    const section = document.querySelector(sectionId);
    if (section) {
      section.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  }

  // Function to start auto-scrolling
  function startAutoScroll() {
    // Clear any existing interval
    if (autoScrollInterval) {
      clearInterval(autoScrollInterval);
    }

    // Auto-scroll every 15 seconds
    autoScrollInterval = setInterval(() => {
      if (!isUserScrolling) {
        currentSectionIndex = (currentSectionIndex + 1) % sections.length;
        scrollToSection(currentSectionIndex);
      }
    }, 15000);
  }

  // Handle user scroll
  window.addEventListener("scroll", () => {
    // Hide/show header
    if (window.scrollY > lastScrollY && window.scrollY > 100) {
      header.classList.add("-translate-y-full");
    } else {
      header.classList.remove("-translate-y-full");
    }
    lastScrollY = window.scrollY;

    // Mark that user is scrolling
    isUserScrolling = true;
    clearTimeout(scrollTimeout);

    // Reset auto-scroll after 2 seconds of no scrolling
    scrollTimeout = setTimeout(() => {
      isUserScrolling = false;
      // Find current section based on scroll position
      const scrollPosition = window.scrollY + window.innerHeight / 2;
      for (let i = sections.length - 1; i >= 0; i--) {
        const section = document.querySelector(sections[i]);
        if (section && section.offsetTop <= scrollPosition) {
          currentSectionIndex = i;
          break;
        }
      }
    }, 2000);
  });

  // Start auto-scroll on page load
  startAutoScroll();

  // Pause auto-scroll on user interaction
  document.addEventListener('mousedown', () => {
    isUserScrolling = true;
  });

  document.addEventListener('keydown', () => {
    isUserScrolling = true;
  });

  // Resume auto-scroll after user interaction stops
  document.addEventListener('mouseup', () => {
    setTimeout(() => {
      isUserScrolling = false;
    }, 2000);
  });
});
</script>


<?php include __DIR__ . '/../layouts/public_footer.php'; ?>


