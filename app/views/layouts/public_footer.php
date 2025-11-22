<!-- Footer -->
<footer class="bg-gray-900 text-gray-300 mt-16">
  <div class="max-w-7xl mx-auto px-6 py-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

    <!-- Logo & Intro -->
    <div>
      <h2 class="text-white text-2xl font-semibold mb-3">WorkNest</h2>
      <p class="text-sm leading-relaxed">
        A smart and efficient job management system built for modern recruiters and job seekers.
      </p>
    </div>

    <!-- Quick Links -->
    <div>
      <h3 class="text-white font-semibold mb-3">Quick Links</h3>
      <ul class="space-y-2 text-sm">
        <li><a href="/Worknest/public/" class="hover:text-blue-400 transition">Home</a></li>
        <li><a href="/Worknest/public/jobs" class="hover:text-blue-400 transition">Jobs</a></li>
        <li><a href="/Worknest/public/about" class="hover:text-blue-400 transition">About</a></li>
        <li><a href="/Worknest/public/contact" class="hover:text-blue-400 transition">Contact</a></li>
      </ul>
    </div>

    <!-- Support -->
    <div>
      <h3 class="text-white font-semibold mb-3">Support</h3>
      <ul class="space-y-2 text-sm">
        <li><a href="/Worknest/public/help-center" class="hover:text-blue-400 transition">Help Center</a></li>
        <li><a href="/Worknest/public/terms-of-service" class="hover:text-blue-400 transition">Terms of Service</a>
        </li>
        <li><a href="/Worknest/public/privacy-policy" class="hover:text-blue-400 transition">Privacy Policy</a></li>
      </ul>
    </div>

    <!-- Contact -->
    <div>
      <h3 class="text-white font-semibold mb-3">Contact Us</h3>
      <p class="text-sm mb-2">📍 Ho Chi Minh City, Vietnam</p>
      <p class="text-sm mb-2">✉️ contact@jobposter.com</p>
      <p class="text-sm">📞 +84 123 456 789</p>
      <div class="flex space-x-4 mt-3">
        <a href="#" class="hover:text-blue-400 transition"><i class="fab fa-facebook-f"></i></a>
        <a href="#" class="hover:text-blue-400 transition"><i class="fab fa-linkedin-in"></i></a>
        <a href="#" class="hover:text-blue-400 transition"><i class="fab fa-github"></i></a>
      </div>
    </div>

  </div>

  <!-- Bottom Bar -->
  <div class="border-t border-gray-700 mt-8 py-4 text-center text-sm text-gray-400">
    <p>© 2025 <span class="text-white font-medium">WorkNest</span> — Job Management System</p>

  </div>
</footer>

<script src="/Worknest/public/javascript/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/choices.js@10.2.0/public/assets/scripts/choices.min.js"></script>
<script src="https://accounts.google.com/gsi/client" async defer></script>
<script src="/Worknest/public/javascript/notyf.min.js"></script>
<script src="/Worknest/public/javascript/main.js"></script>
<?php if (isset($additionalJS)): ?>
  <?php foreach ($additionalJS as $js): ?>
    <script src="<?= $js ?>"></script>
  <?php endforeach; ?>
<?php endif; ?>
</body>

</html>