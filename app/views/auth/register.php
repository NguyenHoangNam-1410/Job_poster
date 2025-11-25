<?php
$pageTitle = 'Register | WorkNest';
$additionalCSS = ["/Worknest/public/css/auth/register.css"];
include __DIR__ . '/../layouts/public_header.php';
?>

<div class="min-h-screen flex items-center justify-center px-4">
  <div class="flex flex-col md:flex-row md:gap-12 w-full max-w-6xl items-center">

    <!-- Left: Welcome Section -->
    <div class="hidden md:flex md:flex-col md:w-1/2 welcome-section animate-fadeInDown">
      <h1 class="welcome-title">Join <span>WorkNest!</span></h1>
      <p class="welcome-subtitle">Find your dream job or your next great hire today</p>
    </div>

    <!-- Right: Signup Card -->
    <div class="w-full md:w-1/2">
      <div class="card p-8 mx-auto animate-fadeInUp" style="max-width: 600px;">
        <h2 class="text-center mb-6 signup-title">Create Your Account</h2>

        <?php if (isset($_SESSION['register_error'])): ?>
          <div class="alert-login mb-4">
            <?= $_SESSION['register_error']; ?>
          </div>
          <?php unset($_SESSION['register_error']); ?>
        <?php endif; ?>

        <form method="POST" action="/Worknest/public/auth/register/local">
          <div class="mb-3">
            <label for="name" class="form-label">Full Name<span class="text-red-500">*</span></label>
            <input type="text" class="form-control" id="name" name="name" required>
          </div>

          <div class="mb-3">
            <label for="email" class="form-label">Email<span class="text-red-500">*</span></label>
            <input type="email" class="form-control" id="email" name="email" required>
            <div class="error-email mt-2"><?= isset($error_email) ? $error_email : '' ?></div>
          </div>

          <div class="mb-3">
            <label for="password" class="form-label">Password<span class="text-red-500">*</span></label>
            <input type="password" class="form-control" id="password" name="password" required>
            <ul class="password-requirements">
              <li>At least 8 characters</li>
              <li>Include a number</li>
              <li>Include uppercase and lowercase letters</li>
              <li>Include a special character</li>
            </ul>
          </div>

          <div class="mb-4">
            <label for="confirm_password" class="form-label">Confirm Password<span class="text-red-500">*</span></label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            <div class="text-red-600 text-sm mt-1 hidden is-invalid-not-match">Passwords do not match.</div>
          </div>

          <button type="submit" class="btn btn-primary signup-button w-full mb-4">Sign Up</button>

          <div class="text-center text-gray-500 my-3">— or —</div>

          <!-- Social Login Buttons -->
          <div class="social-buttons-wrapper">
            <!-- Facebook Login -->
            <a href="/Worknest/public/auth/login/facebook" class="social-login-button social-button-facebook">
              <svg class="social-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                <path d="M22.675 0H1.325C.593 0 0 .593 0 1.326v21.348C0 23.407.593 24 1.325 24h11.495v-9.294H9.692v-3.622h3.128V8.413c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.464.099 2.794.143v3.24l-1.918.001c-1.504 0-1.796.715-1.796 1.763v2.312h3.587l-.467 3.622h-3.12V24h6.116c.73 0 1.324-.593 1.324-1.326V1.326C24 .593 23.407 0 22.675 0z" />
              </svg>
              <span>SIGN IN WITH FACEBOOK</span>
            </a>

            <!-- Google Sign-In - Use Google's default button with styling -->
            <div class="google-button-wrapper">
              <div id="g_id_signin_real" class="g_id_signin" data-type="standard" data-shape="rectangular"
                data-theme="outline" data-text="signin_with" data-size="large" data-logo_alignment="left"
                data-locale="en">
              </div>
            </div>
          </div>

          <!-- Google Sign-In Script -->
          <div id="g_id_onload" data-client_id="<?= ($_ENV['GOOGLE_CLIENT_ID']) ?>" data-locale="en"
            data-callback="handleCredentialResponse" data-auto_prompt="false" data-use_fedcm_for_prompt="false" data-cancel_on_tap_outside="true">
          </div>

          <p class="mt-6 text-center text-gray-700">
            Already have an account? <a href="/Worknest/public/auth/login" class="text-blue-600 hover:underline">Login
              here</a>
          </p>
        </form>
      </div>
    </div>
  </div>
</div>

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

  // Disable Google One Tap completely
  window.addEventListener('load', function() {
    setTimeout(function() {
      if (window.google && window.google.accounts && window.google.accounts.id) {
        window.google.accounts.id.disableAutoSelect();
        window.google.accounts.id.cancel();
      }
    }, 1000);
  });
</script>

<?php
$additionalJS = ["/Worknest/public/javascript/handleCredentials.js"];
include __DIR__ . '/../layouts/public_footer.php';
?>