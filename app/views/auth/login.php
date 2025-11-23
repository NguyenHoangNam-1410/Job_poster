<?php
$pageTitle = 'Login | WorkNest';
$additionalCSS = [
  "/Worknest/public/css/auth/login.css"
];
include __DIR__ . '/../layouts/public_header.php'; ?>
<div class="min-h-screen flex items-center justify-center px-4">
  <div class="flex flex-col md:flex-row md:gap-12 w-full max-w-6xl items-center">

    <!-- Login Form -->
    <div class="w-full md:w-1/2 max-w-lg bg-white shadow-xl rounded-xl p-10 animate-fadeInUp">
      <h2 class="text-3xl font-bold text-center mb-6 text-gray-800">Login</h2>

      <?php if (isset($_SESSION['login_error'])): ?>
        <div class="alert-login">
          <?= $_SESSION['login_error']; ?>
        </div>
        <?php unset($_SESSION['login_error']); ?>
      <?php endif; ?>

      <form method="POST" action="/Worknest/public/auth/login/local" class="space-y-5">
        <div>
          <label for="email" class="block text-gray-700 font-semibold mb-2">Email</label>
          <input type="text" id="email" name="email" required
            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
        </div>

        <div>
          <label for="password" class="block text-gray-700 font-semibold mb-2">Password</label>
          <div class="relative">
            <input type="password" id="password" name="password" required
              class="w-full border border-gray-300 rounded-lg px-4 py-2 pr-12 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
            <button type="button" id="togglePassword"
              class="absolute top-1/2 right-3 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none"
              aria-label="Show password">
              <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
            </button>
          </div>
        </div>

        <button type="submit"
          class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition">
          Login
        </button>

        <a href="/Worknest/public/auth/login/forgot-password"
          class="block text-center mt-2 text-blue-600 hover:underline">
          Forgot Password?
        </a>

        <div class="text-center text-gray-500 mt-3 mb-3">— or —</div>

        <!-- Social Login Buttons -->
        <div class="social-buttons-wrapper">
          <!-- Facebook Login -->
          <a href="/Worknest/public/auth/login/facebook" class="social-login-button social-button-facebook">
            <svg class="social-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
              <path d="M22.675 0H1.325C.593 0 0 .593 0 1.326v21.348C0 23.407.593 24 1.325 24h11.495v-9.294H9.692v-3.622h3.128V8.413c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.464.099 2.794.143v3.24l-1.918.001c-1.504 0-1.796.715-1.796 1.763v2.312h3.587l-.467 3.622h-3.12V24h6.116c.73 0 1.324-.593 1.324-1.326V1.326C24 .593 23.407 0 22.675 0z" />
            </svg>
            <span>SIGN IN WITH FACEBOOK</span>
          </a>

          <!-- Google Sign-In -->
          <button type="button" id="custom-google-login" class="social-login-button social-button-google">
            <svg class="social-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
              <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
              <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
              <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
              <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
            </svg>
            <span>SIGN IN WITH GOOGLE</span>
          </button>
        </div>

        <!-- Hidden Google Sign-In for triggering -->
        <div id="g_id_onload" data-client_id="<?= ($_ENV['GOOGLE_CLIENT_ID']) ?>" data-locale="en"
          data-callback="handleCredentialResponse" data-auto_prompt="false" data-use_fedcm_for_prompt="false">
        </div>
        <div id="hidden-google-btn" style="position: absolute; left: -9999px; width: 300px; height: 48px; overflow: visible; opacity: 0; pointer-events: none;">
          <div id="g_id_signin_hidden" data-type="standard" data-shape="rectangular"
            data-theme="filled_black" data-text="sign_in_with" data-size="large" data-logo_alignment="left"
            data-width="300" data-locale="en" data-context="signin">
          </div>
        </div>
      </form>

      <p class="mt-6 text-center text-gray-600">
        Don't have an account? <a href="/Worknest/public/auth/register" class="text-blue-600 hover:underline">Register
          here</a>
      </p>
    </div>

    <!-- Welcome Section Column -->
    <div class="hidden md:flex md:flex-col md:w-1/2 text-center justify-center animate-fadeInDown">
      <h1 class="welcome-title">Welcome back to <span> WorkNest! </span></h1>
      <p class="welcome-subtitle">The job portal for job seekers and employers</p>
    </div>

  </div>
</div>

<script>
  // Show password only while mouse is held down
  const passwordInput = document.getElementById('password');
  const toggleBtn = document.getElementById('togglePassword');
  const eyeIcon = document.getElementById('eyeIcon');

  toggleBtn.addEventListener('mousedown', (e) => {
    e.preventDefault();
    passwordInput.type = 'text';
    eyeIcon.innerHTML = `
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
  `;
  });

  toggleBtn.addEventListener('mouseup', () => {
    passwordInput.type = 'password';
    eyeIcon.innerHTML = `
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
  `;
  });

  toggleBtn.addEventListener('mouseleave', () => {
    passwordInput.type = 'password';
    eyeIcon.innerHTML = `
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
  `;
  });

  // Handle touch devices
  toggleBtn.addEventListener('touchstart', (e) => {
    e.preventDefault();
    passwordInput.type = 'text';
    eyeIcon.innerHTML = `
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
  `;
  });

  toggleBtn.addEventListener('touchend', () => {
    passwordInput.type = 'password';
    eyeIcon.innerHTML = `
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
  `;
  });
</script>

<script>
  // Disable Google One Tap prompt completely
  window.onload = function() {
    if (window.google && window.google.accounts && window.google.accounts.id) {
      window.google.accounts.id.cancel();
      window.google.accounts.id.disableAutoSelect();
    }
  };
  
  // Also disable after GSI script loads
  window.addEventListener('load', function() {
    setTimeout(function() {
      if (window.google && window.google.accounts && window.google.accounts.id) {
        window.google.accounts.id.cancel();
        window.google.accounts.id.disableAutoSelect();
      }
    }, 1000);
  });

  // Handle custom Google button click
  function triggerGoogleLogin() {
    // Wait for Google API to be ready
    function tryGoogleLogin() {
      if (window.google && window.google.accounts && window.google.accounts.id) {
        const hiddenButton = document.getElementById('g_id_signin_hidden');
        
        // Ensure button is rendered
        if (hiddenButton) {
          // Wait for iframe to be created
          let attempts = 0;
          const checkForIframe = setInterval(function() {
            attempts++;
            const iframe = hiddenButton.querySelector('iframe');
            
            if (iframe) {
              clearInterval(checkForIframe);
              // Try multiple methods to trigger click
              try {
                // Method 1: Direct iframe click
                iframe.click();
                
                // Method 2: Dispatch click event
                const clickEvent = new MouseEvent('click', {
                  bubbles: true,
                  cancelable: true,
                  view: window
                });
                iframe.dispatchEvent(clickEvent);
                
                // Method 3: Post message to iframe
                if (iframe.contentWindow) {
                  iframe.contentWindow.postMessage({type: 'click'}, '*');
                }
              } catch (e) {
                console.log('Error triggering iframe click:', e);
                // Fallback to prompt
                window.google.accounts.id.prompt();
              }
            } else if (attempts > 20) {
              // Timeout after 2 seconds
              clearInterval(checkForIframe);
              // Fallback to prompt
              window.google.accounts.id.prompt();
            }
          }, 100);
        } else {
          // Fallback: use prompt method
          window.google.accounts.id.prompt();
        }
      } else {
        // Retry after a short delay
        setTimeout(tryGoogleLogin, 100);
      }
    }
    
    tryGoogleLogin();
  }

  document.addEventListener('DOMContentLoaded', function() {
    const customGoogleBtn = document.getElementById('custom-google-login');
    if (customGoogleBtn) {
      customGoogleBtn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        triggerGoogleLogin();
      });
    }
  });
</script>

<?php
$additionalJS = [
  "/Worknest/public/javascript/handleCredentials.js"
];
include __DIR__ . '/../layouts/public_footer.php'; ?>