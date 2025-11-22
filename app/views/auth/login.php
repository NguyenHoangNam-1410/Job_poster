<?php
$pageTitle = 'Login | WorkNest';
$additionalCSS = [
  "/Job_poster/public/css/auth/login.css"
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

      <form method="POST" action="/Job_poster/public/auth/login/local" class="space-y-5">
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

        <a href="/Job_poster/public/auth/login/forgot-password"
          class="block text-center mt-2 text-blue-600 hover:underline">
          Forgot Password?
        </a>

        <div class="text-center text-gray-500 mt-3 mb-3">— or —</div>

        <!-- Google Sign-In -->
        <div id="g_id_onload" data-client_id="<?= ($_ENV['GOOGLE_CLIENT_ID']) ?>"
          data-callback="handleCredentialResponse" data-locale="en" data-auto_prompt="false"></div>

        <div class="flex justify-center my-3">
          <div class="g_id_signin shadow-md" data-type="standard" data-shape="rectangular"
            data-theme="filled_black" data-text="sign_in_with" data-size="large" data-logo_alignment="left"
            data-width="300" data-locale="en"></div>
        </div>

        <!-- Facebook Login -->
        <div class="flex justify-center my-3">
          <a href="/Job_poster/public/auth/login/facebook"
            class="flex items-center justify-center w-full max-w-xs bg-blue-700 hover:bg-blue-800 text-white font-medium py-2 transition social-login-btn">
            <svg class="mr-2" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
              viewBox="0 0 24 24">
              <path
                d="M22.675 0H1.325C.593 0 0 .593 0 1.326v21.348C0 23.407.593 24 1.325 24h11.495v-9.294H9.692v-3.622h3.128V8.413c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.464.099 2.794.143v3.24l-1.918.001c-1.504 0-1.796.715-1.796 1.763v2.312h3.587l-.467 3.622h-3.12V24h6.116c.73 0 1.324-.593 1.324-1.326V1.326C24 .593 23.407 0 22.675 0z" />
            </svg>
            <span class="social-login-text">SIGN IN WITH FACEBOOK</span>
          </a>
        </div>
      </form>

      <p class="mt-6 text-center text-gray-600">
        Don't have an account? <a href="/Job_poster/public/auth/register" class="text-blue-600 hover:underline">Register
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

<?php
$additionalJS = [
  "/Job_poster/public/javascript/handleCredentials.js"
];
include __DIR__ . '/../layouts/public_footer.php'; ?>