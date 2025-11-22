<?php
$pageTitle = 'Contact Us | WorkNest';
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

  .contact-info {
    display: flex;
    align-items: center;
    margin-bottom: 1.5rem;
    padding: 1rem;
    background: #f9fafb;
    border-left: 4px solid #0a4d5c;
  }

  .contact-icon {
    font-size: 2rem;
    margin-right: 1.5rem;
    min-width: 50px;
    text-align: center;
  }

  .contact-form input,
  .contact-form textarea {
    width: 100%;
    padding: 0.75rem;
    border: 3px solid #0a4d5c;
    margin-bottom: 1rem;
    font-family: inherit;
  }

  .contact-form input:focus,
  .contact-form textarea:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(10, 77, 92, 0.1);
  }

  .btn-submit {
    background: linear-gradient(135deg, #b4ff39 0%, #2db8ac 100%);
    color: #0a4d5c;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 1rem 2rem;
    border: 3px solid #0a4d5c;
    box-shadow: 5px 5px 0px #0a4d5c;
    cursor: pointer;
    transition: all 0.2s ease;
  }

  .btn-submit:hover {
    transform: translate(-2px, -2px);
    box-shadow: 7px 7px 0px #0a4d5c;
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
    <h1 class="text-5xl md:text-6xl font-black mb-4 uppercase tracking-tight">Contact Us</h1>
    <p class="text-xl md:text-2xl font-bold max-w-2xl mx-auto">
      We're Here to Help
    </p>
  </div>
</section>

<!-- Main Content -->
<section class="bg-gray-50 py-12">
  <div class="content-section">

    <!-- Contact Information -->
    <div class="retro-card">
      <h2 class="text-3xl font-black text-gray-900 mb-6 uppercase tracking-tight">Get In Touch</h2>

      <div class="contact-info">
        <div class="contact-icon">📍</div>
        <div>
          <h3 class="font-black text-lg text-gray-900 mb-1 uppercase">Address</h3>
          <p class="text-gray-700">Ho Chi Minh City, Vietnam</p>
        </div>
      </div>

      <div class="contact-info">
        <div class="contact-icon">✉️</div>
        <div>
          <h3 class="font-black text-lg text-gray-900 mb-1 uppercase">Email</h3>
          <p class="text-gray-700">
            <a href="mailto:contact@worknest.com" class="text-cyan-600 font-bold hover:underline">
              contact@worknest.com
            </a>
          </p>
        </div>
      </div>

      <div class="contact-info">
        <div class="contact-icon">📞</div>
        <div>
          <h3 class="font-black text-lg text-gray-900 mb-1 uppercase">Phone</h3>
          <p class="text-gray-700">
            <a href="tel:+84123456789" class="text-cyan-600 font-bold hover:underline">
              +84 123 456 789
            </a>
          </p>
        </div>
      </div>

      <div class="contact-info">
        <div class="contact-icon">🕒</div>
        <div>
          <h3 class="font-black text-lg text-gray-900 mb-1 uppercase">Business Hours</h3>
          <p class="text-gray-700">Monday - Friday: 9:00 AM - 6:00 PM (GMT+7)</p>
          <p class="text-gray-700">Saturday: 9:00 AM - 1:00 PM</p>
          <p class="text-gray-700">Sunday: Closed</p>
        </div>
      </div>
    </div>

    <!-- Contact Form -->
    <div class="retro-card">
      <h2 class="text-3xl font-black text-gray-900 mb-6 uppercase tracking-tight">Send Us a Message</h2>
      <form class="contact-form" action="#" method="POST">
        <div>
          <label class="block font-bold text-gray-900 mb-2 uppercase text-sm">Your Name</label>
          <input type="text" name="name" required placeholder="Enter your name">
        </div>
        <div>
          <label class="block font-bold text-gray-900 mb-2 uppercase text-sm">Your Email</label>
          <input type="email" name="email" required placeholder="your.email@example.com">
        </div>
        <div>
          <label class="block font-bold text-gray-900 mb-2 uppercase text-sm">Subject</label>
          <input type="text" name="subject" required placeholder="What is this regarding?">
        </div>
        <div>
          <label class="block font-bold text-gray-900 mb-2 uppercase text-sm">Message</label>
          <textarea name="message" rows="6" required placeholder="Tell us how we can help you..."></textarea>
        </div>
        <button type="submit" class="btn-submit">Send Message</button>
      </form>
    </div>

    <!-- FAQ Section -->
    <div class="retro-card">
      <h2 class="text-3xl font-black text-gray-900 mb-6 uppercase tracking-tight">Frequently Asked Questions</h2>

      <div class="faq-item">
        <div class="faq-question">How quickly will I receive a response?</div>
        <div class="faq-answer">
          We aim to respond to all inquiries within 24-48 hours during business days. For urgent matters,
          please call us directly at +84 123 456 789.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question">Can I schedule a meeting or demo?</div>
        <div class="faq-answer">
          Absolutely! Please send us an email at contact@worknest.com with your preferred dates and times,
          and we'll arrange a meeting or product demonstration for you.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question">Do you offer support for employers?</div>
        <div class="faq-answer">
          Yes! We have dedicated support for employers. Contact us to learn about our premium features,
          bulk posting options, and dedicated account management services.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question">How can I report a technical issue?</div>
        <div class="faq-answer">
          If you encounter any technical issues, please email us at contact@worknest.com with a detailed
          description of the problem. Include screenshots if possible, and we'll address it promptly.
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question">Can I provide feedback or suggestions?</div>
        <div class="faq-answer">
          We love hearing from our users! Your feedback helps us improve. Send your suggestions to
          contact@worknest.com or use the feedback form on our platform.
        </div>
      </div>

      <div class="faq-item" style="border-bottom: none;">
        <div class="faq-question">Do you have a physical office I can visit?</div>
        <div class="faq-answer">
          Our main office is located in Ho Chi Minh City, Vietnam. While we primarily operate online,
          you can schedule an appointment to visit us. Please contact us in advance to arrange a visit.
        </div>
      </div>
    </div>

  </div>
</section>

<?php include __DIR__ . '/../layouts/public_footer.php'; ?>