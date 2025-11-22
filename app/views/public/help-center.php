<?php
$pageTitle = 'Help Center | WorkNest';
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

  .help-category {
    margin-bottom: 2rem;
  }

  .category-title {
    font-size: 1.5rem;
    font-weight: 900;
    color: #0a4d5c;
    margin-bottom: 1rem;
    text-transform: uppercase;
    padding-bottom: 0.5rem;
    border-bottom: 3px solid #0a4d5c;
  }
</style>

<!-- Hero Section -->
<section class="page-hero text-white text-center">
  <div class="container mx-auto px-6">
    <h1 class="text-5xl md:text-6xl font-black mb-4 uppercase tracking-tight">Help Center</h1>
    <p class="text-xl md:text-2xl font-bold max-w-2xl mx-auto">
      Find Answers to Your Questions
    </p>
  </div>
</section>

<!-- Main Content -->
<section class="bg-gray-50 py-12">
  <div class="content-section">

    <!-- Getting Started -->
    <div class="retro-card help-category">
      <h2 class="category-title">Getting Started</h2>

      <div class="faq-item">
        <div class="faq-question">How do I create an account?</div>
        <div class="faq-answer">
          Click on the "Register" button in the top right corner, fill in your email address, create a password,
          and complete your profile. You'll receive a confirmation email to verify your account.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question">Do I need to pay to use WorkNest?</div>
        <div class="faq-answer">
          No! WorkNest is completely free for job seekers. You can browse jobs, create a profile, and apply
          to unlimited positions at no cost. Some premium features may be available for employers.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question">How do I complete my profile?</div>
        <div class="faq-answer">
          After registering, go to your profile page and add your work experience, education, skills, and
          upload your resume. A complete profile increases your chances of being noticed by employers.
        </div>
      </div>

      <div class="faq-item" style="border-bottom: none;">
        <div class="faq-question">Can I use WorkNest on mobile?</div>
        <div class="faq-answer">
          Yes! WorkNest is fully responsive and works great on mobile devices, tablets, and desktops.
          You can access all features from any device with an internet connection.
        </div>
      </div>
    </div>

    <!-- Job Searching -->
    <div class="retro-card help-category">
      <h2 class="category-title">Job Searching</h2>

      <div class="faq-item">
        <div class="faq-question">How do I search for jobs?</div>
        <div class="faq-answer">
          Use the search bar on the Jobs page to enter keywords, job titles, or company names. You can also
          filter by location, salary range, job type (full-time, part-time, remote), and category.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question">How do I save jobs for later?</div>
        <div class="faq-answer">
          When viewing a job listing, click the "Save" or bookmark icon. You can access all saved jobs from
          your profile dashboard under "Saved Jobs."
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question">Can I apply to multiple jobs?</div>
        <div class="faq-answer">
          Absolutely! You can apply to as many jobs as you want. Keep track of all your applications in the
          "My Applications" section of your profile.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question">How do I know if an employer viewed my application?</div>
        <div class="faq-answer">
          You'll receive email notifications when an employer views your application or updates your application
          status. You can also check the status in your "My Applications" dashboard.
        </div>
      </div>

      <div class="faq-item" style="border-bottom: none;">
        <div class="faq-question">What should I include in my application?</div>
        <div class="faq-answer">
          Make sure to include an updated resume, a personalized cover letter (when requested), and ensure
          your profile is complete. Highlight relevant skills and experience that match the job requirements.
        </div>
      </div>
    </div>

    <!-- For Employers -->
    <div class="retro-card help-category">
      <h2 class="category-title">For Employers</h2>

      <div class="faq-item">
        <div class="faq-question">How do I post a job?</div>
        <div class="faq-answer">
          Register as an employer, verify your company, and click "Post a Job" from your dashboard. Fill in
          the job details, requirements, and salary information. Your job will be reviewed and published within
          24-48 hours.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question">How much does it cost to post a job?</div>
        <div class="faq-answer">
          Basic job postings are free. Premium packages are available for enhanced visibility, featured listings,
          and access to advanced candidate search tools. Contact us for pricing details.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question">How do I review applications?</div>
        <div class="faq-answer">
          Log into your employer dashboard and navigate to "Job Applications." You'll see all applications
          for your posted jobs. You can filter, sort, and review candidate profiles and resumes.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question">Can I edit or delete a job posting?</div>
        <div class="faq-answer">
          Yes, you can edit your job postings at any time from your dashboard. You can also close or delete
          postings when positions are filled or no longer available.
        </div>
      </div>

      <div class="faq-item" style="border-bottom: none;">
        <div class="faq-question">How do I contact candidates?</div>
        <div class="faq-answer">
          Once you review an application, you can send messages directly through the platform. You can also
          schedule interviews and send interview invitations through the system.
        </div>
      </div>
    </div>

    <!-- Account & Security -->
    <div class="retro-card help-category">
      <h2 class="category-title">Account & Security</h2>

      <div class="faq-item">
        <div class="faq-question">I forgot my password. How do I reset it?</div>
        <div class="faq-answer">
          Click "Forgot Password" on the login page, enter your email address, and you'll receive a password
          reset link. Click the link and follow the instructions to create a new password.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question">How do I change my email address?</div>
        <div class="faq-answer">
          Go to your profile settings, click "Edit Profile," and update your email address. You'll need to
          verify the new email address before it's activated.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question">How do I delete my account?</div>
        <div class="faq-answer">
          Go to your account settings and select "Delete Account." Please note that this action is permanent
          and cannot be undone. All your data will be removed from our system.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question">Is my personal information secure?</div>
        <div class="faq-answer">
          Yes, we use industry-standard encryption to protect your data. We never share your personal information
          with third parties without your consent. Read our <a href="/Job_poster/public/privacy-policy"
            class="text-cyan-600 font-bold hover:underline">Privacy Policy</a> for details.
        </div>
      </div>

      <div class="faq-item" style="border-bottom: none;">
        <div class="faq-question">What should I do if I notice suspicious activity?</div>
        <div class="faq-answer">
          If you notice any suspicious activity on your account, immediately change your password and contact
          our support team at contact@jobposter.com. We take security seriously and will investigate promptly.
        </div>
      </div>
    </div>

    <!-- Technical Support -->
    <div class="retro-card help-category">
      <h2 class="category-title">Technical Support</h2>

      <div class="faq-item">
        <div class="faq-question">The website is not loading properly. What should I do?</div>
        <div class="faq-answer">
          Try clearing your browser cache and cookies, or use a different browser. If the problem persists,
          contact our technical support team with details about the issue.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question">I'm having trouble uploading my resume. What's wrong?</div>
        <div class="faq-answer">
          Make sure your resume is in PDF, DOC, or DOCX format and under 5MB in size. If you continue to have
          issues, try converting your file to PDF format.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question">I'm not receiving email notifications. Why?</div>
        <div class="faq-answer">
          Check your spam/junk folder first. Make sure our email address (noreply@jobposter.com) is whitelisted.
          You can also check your notification settings in your account preferences.
        </div>
      </div>

      <div class="faq-item" style="border-bottom: none;">
        <div class="faq-question">Still need help?</div>
        <div class="faq-answer">
          If you can't find the answer you're looking for, please <a href="/Job_poster/public/contact"
            class="text-cyan-600 font-bold hover:underline">contact our support team</a>.
          We're here to help and typically respond within 24-48 hours.
        </div>
      </div>
    </div>

  </div>
</section>

<?php include __DIR__ . '/../layouts/public_footer.php'; ?>