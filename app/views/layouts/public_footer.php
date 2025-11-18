    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 mt-16">
      <div class="max-w-7xl mx-auto px-6 py-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        
        <!-- Logo & Intro -->
        <div>
          <h2 class="text-white text-2xl font-semibold mb-3">Job Poster</h2>
          <p class="text-sm leading-relaxed">
            A smart and efficient job management system built for modern recruiters and job seekers.
          </p>
        </div>

        <!-- Quick Links -->
        <div>
          <h3 class="text-white font-semibold mb-3">Quick Links</h3>
          <ul class="space-y-2 text-sm">
            <li><a href="/Job_poster/public/" class="hover:text-blue-400 transition">Home</a></li>
            <li><a href="/Job_poster/public/jobs" class="hover:text-blue-400 transition">Jobs</a></li>
            <li><a href="/Job_poster/public/about" class="hover:text-blue-400 transition">About</a></li>
            <li><a href="/Job_poster/public/contact" class="hover:text-blue-400 transition">Contact</a></li>
          </ul>
        </div>

        <!-- Support -->
        <div>
          <h3 class="text-white font-semibold mb-3">Support</h3>
          <ul class="space-y-2 text-sm">
            <li><a href="/Job_poster/public/help-center" class="hover:text-blue-400 transition">Help Center</a></li>
            <li><a href="/Job_poster/public/terms-of-service" class="hover:text-blue-400 transition">Terms of Service</a></li>
            <li><a href="/Job_poster/public/privacy-policy" class="hover:text-blue-400 transition">Privacy Policy</a></li>
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
        <p>© 2025 <span class="text-white font-medium">Job Poster</span> — Job Management System</p>
       
      </div>
    </footer>

    <!-- GSAP Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    
    <!-- GSAP Preloader Animation -->
    <script>
    (function() {
      const preloader = document.getElementById('preloader');
      if (!preloader) return; // Exit if no preloader on page
      
      // Register ScrollTrigger
      gsap.registerPlugin(ScrollTrigger);
      
      const preloaderBar = document.getElementById('preloader-bar');
      const preloaderPercent = document.getElementById('preloader-percent');
      const preloaderContent = document.querySelector('.preloader-content');
      const preloaderShapes = document.querySelectorAll('.preloader-shape');

      // Ensure body doesn't scroll during preloader
      document.body.style.overflow = 'hidden';

      // GSAP Timeline for preloader entrance
      const preloaderTimeline = gsap.timeline();

      // Animate logo entrance
      preloaderTimeline.from('.preloader-logo', {
        opacity: 0,
        scale: 0.5,
        y: -50,
        duration: 0.8,
        ease: 'back.out(1.7)'
      });

      // Animate tagline
      preloaderTimeline.from('.preloader-tagline', {
        opacity: 0,
        y: 20,
        duration: 0.6,
        ease: 'power2.out'
      }, '-=0.4');

      // Animate progress bar container
      preloaderTimeline.from('.preloader-bar-container', {
        opacity: 0,
        scale: 0.8,
        duration: 0.5,
        ease: 'power2.out'
      }, '-=0.3');

      // Animate shapes
      preloaderShapes.forEach((shape, index) => {
        gsap.to(shape, {
          rotation: 360,
          duration: 3 + index,
          repeat: -1,
          ease: 'none'
        });
        
        gsap.to(shape, {
          y: -20,
          duration: 2 + index * 0.5,
          repeat: -1,
          yoyo: true,
          ease: 'power1.inOut'
        });
      });

      // Simulate loading progress
      let progress = 0;
      const loadingInterval = setInterval(() => {
        progress += Math.random() * 15;
        if (progress >= 100) {
          progress = 100;
          clearInterval(loadingInterval);
          
          // Start exit animation after reaching 100%
          setTimeout(() => {
            exitPreloader();
          }, 500);
        }
        
        // Update progress bar and percentage
        gsap.to(preloaderBar, {
          width: progress + '%',
          duration: 0.3,
          ease: 'power2.out'
        });
        
        gsap.to(preloaderPercent, {
          innerHTML: Math.floor(progress) + '%',
          duration: 0.3,
          snap: { innerHTML: 1 }
        });
      }, 200);

      // Exit animation function
      function exitPreloader() {
        const exitTimeline = gsap.timeline({
          onComplete: () => {
            preloader.style.display = 'none';
            document.body.style.overflow = 'auto';
          }
        });

        // Fade out content
        exitTimeline.to(preloaderContent, {
          opacity: 0,
          y: -30,
          duration: 0.5,
          ease: 'power2.in'
        });

        // Fade out shapes
        exitTimeline.to(preloaderShapes, {
          opacity: 0,
          scale: 0,
          duration: 0.4,
          stagger: 0.1,
          ease: 'back.in(1.7)'
        }, '-=0.3');

        // Scale and fade out entire preloader
        exitTimeline.to(preloader, {
          opacity: 0,
          scale: 1.1,
          duration: 0.8,
          ease: 'power2.inOut'
        }, '-=0.2');

        // Animate hero section entrance
        const heroSection = document.querySelector('.hero-bg');
        if (heroSection) {
          exitTimeline.from(heroSection, {
            opacity: 0,
            duration: 0.6,
            ease: 'power2.out'
          }, '-=0.4');
        }
      }

      // Skip preloader on click
      preloader.addEventListener('click', () => {
        if (progress < 100) {
          progress = 100;
          clearInterval(loadingInterval);
          gsap.to(preloaderBar, { width: '100%', duration: 0.3 });
          gsap.to(preloaderPercent, { innerHTML: '100%', duration: 0.3 });
          setTimeout(() => exitPreloader(), 300);
        }
      });
    })();
    </script>
    
    <script src="/Job_poster/public/javascript/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/choices.js@10.2.0/public/assets/scripts/choices.min.js"></script>
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <?php if (isset($additionalJS)): ?>
      <?php foreach ($additionalJS as $js): ?>
        <script src="<?= $js ?>"></script>
      <?php endforeach; ?>
    <?php endif; ?>
  </body>
</html>
