<?php
$pageTitle = 'About Us | WorkNest';
include __DIR__ . '/../layouts/public_header.php';
?>

<style>
  .page-hero {
    background: linear-gradient(135deg, #0a4d5c 0%, #1a8a9d 50%, #2db8ac 100%);
    padding: 4rem 0;
  }

  .content-section {
    max-width: 900px;
    margin: 0 auto;
    padding: 3rem 1.5rem;
  }

  .retro-card {
    background: white;
    border: 3px solid #0a4d5c;
    box-shadow: 8px 8px 0px rgba(10, 77, 92, 0.15);
    padding: 2rem;
    margin-bottom: 2rem;
    transition: all 0.3s ease;
  }

  .retro-card:hover {
    transform: translate(-4px, -4px);
    box-shadow: 12px 12px 0px rgba(10, 77, 92, 0.2);
  }

  .faq-item {
    border-bottom: 2px solid #e5e7eb;
    padding: 1.5rem 0;
  }

  .faq-question {
    font-weight: 900;
    font-size: 1.1rem;
    color: #0a4d5c;
    margin-bottom: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
  }

  .faq-answer {
    color: #4b5563;
    line-height: 1.7;
  }
</style>

<!-- Hero Section -->
<section class="page-hero text-white text-center">
  <div class="container mx-auto px-6">
    <h1 class="text-5xl md:text-6xl font-black mb-4 uppercase tracking-tight">About WorkNest</h1>
    <p class="text-xl md:text-2xl font-bold max-w-2xl mx-auto">
      Connecting Talent With Opportunity
    </p>
  </div>
</section>

<!-- Main Content -->
<section class="bg-gray-50 py-12">
  <div class="content-section">

    <!-- Mission -->
    <div class="retro-card">
      <h2 class="text-3xl font-black text-gray-900 mb-4 uppercase tracking-tight">Our Mission</h2>
      <p class="text-gray-700 leading-relaxed text-lg mb-4">
        WorkNest is a modern platform designed to revolutionize the way job seekers and employers connect.
        We believe that finding the right job or the perfect candidate shouldn't be complicated.
      </p>
      <p class="text-gray-700 leading-relaxed text-lg">
        Our mission is to simplify job discovery, empower job seekers with valuable insights, and help employers
        find their ideal match quickly and efficiently through intelligent matching and a user-friendly interface.
      </p>
    </div>

    <!-- What We Do -->
    <div class="retro-card">
      <h2 class="text-3xl font-black text-gray-900 mb-4 uppercase tracking-tight">What We Do</h2>
      <div class="space-y-4">
        <div>
          <h3 class="font-black text-xl text-gray-900 mb-2 uppercase">For Job Seekers</h3>
          <p class="text-gray-700 leading-relaxed">
            We provide a streamlined platform where you can discover opportunities, apply with ease,
            and manage your career goals all in one place. Our smart matching system helps you find
            jobs that align with your skills and aspirations.
          </p>
        </div>
        <div>
          <h3 class="font-black text-xl text-gray-900 mb-2 uppercase">For Employers</h3>
          <p class="text-gray-700 leading-relaxed">
            We offer powerful tools to post job openings, connect with skilled talent, and build your
            dream team faster than ever. Our platform ensures you reach qualified candidates efficiently.
          </p>
        </div>
        <div>
          <h3 class="font-black text-xl text-gray-900 mb-2 uppercase">Smart Matching</h3>
          <p class="text-gray-700 leading-relaxed">
            Powered by intelligent analytics, our system matches the perfect candidates with the right
            opportunities, saving time for both job seekers and employers.
          </p>
        </div>
      </div>
    </div>

    <!-- Why Choose Us -->
    <div class="retro-card">
      <h2 class="text-3xl font-black text-gray-900 mb-4 uppercase tracking-tight">Why Choose WorkNest?</h2>
      <ul class="space-y-3 text-gray-700">
        <li class="flex items-start">
          <span class="font-black text-2xl text-cyan-500 mr-3">✓</span>
          <span><strong class="text-gray-900">Fast & Simple:</strong> Apply to jobs in seconds with our streamlined
            interface.</span>
        </li>
        <li class="flex items-start">
          <span class="font-black text-2xl text-cyan-500 mr-3">✓</span>
          <span><strong class="text-gray-900">Secure Platform:</strong> Bank-level encryption protects your data.</span>
        </li>
        <li class="flex items-start">
          <span class="font-black text-2xl text-cyan-500 mr-3">✓</span>
          <span><strong class="text-gray-900">Verified Employers:</strong> Connect only with legitimate
            companies.</span>
        </li>
        <li class="flex items-start">
          <span class="font-black text-2xl text-cyan-500 mr-3">✓</span>
          <span><strong class="text-gray-900">Smart Matching:</strong> AI-powered recommendations for better job
            matches.</span>
        </li>
      </ul>
    </div>

    <!-- FAQ Section -->
    <div class="retro-card">
      <h2 class="text-3xl font-black text-gray-900 mb-6 uppercase tracking-tight">Frequently Asked Questions</h2>

      <div class="faq-item">
        <div class="faq-question">What is WorkNest?</div>
        <div class="faq-answer">
          WorkNest is a comprehensive job management platform that connects job seekers with employers.
          We provide tools for job discovery, application management, and intelligent candidate matching.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question">Is WorkNest free to use?</div>
        <div class="faq-answer">
          Yes! WorkNest is completely free for job seekers. Employers may have access to premium features
          for enhanced visibility and recruitment tools.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question">How does the smart matching work?</div>
        <div class="faq-answer">
          Our intelligent matching system analyzes your skills, experience, and preferences to recommend
          jobs that best fit your profile. For employers, it helps identify candidates whose qualifications
          match your job requirements.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question">Can I apply for multiple jobs at once?</div>
        <div class="faq-answer">
          Absolutely! You can apply to as many jobs as you want. Our platform makes it easy to track all
          your applications in one place.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question">How do I get started?</div>
        <div class="faq-answer">
          Simply create a free account, complete your profile, and start browsing jobs. You can apply
          immediately or save jobs for later. It's that simple!
        </div>
      </div>

      <div class="faq-item" style="border-bottom: none;">
        <div class="faq-question">Is my personal information safe?</div>
        <div class="faq-answer">
          Yes, we take data security seriously. Your personal information is protected with bank-level
          encryption, and we never share your data with third parties without your consent. Read our
          <a href="/Job_poster/public/privacy-policy" class="text-cyan-600 font-bold hover:underline">Privacy Policy</a>
          for more details.
        </div>
      </div>
    </div>

  </div>
</section>

<?php include __DIR__ . '/../layouts/public_footer.php'; ?>