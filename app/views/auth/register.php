<?php 
  $additionalCSS = ["/Job_poster/public/css/auth/register.css"];
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

        <form method="POST" action="/Job_poster/public/auth/register/local">
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

          <!-- Google Sign-In -->
          <div id="g_id_onload"
              data-client_id="<?= ($_ENV['GOOGLE_CLIENT_ID']) ?>"
              data-locale="en"
              data-callback="handleCredentialResponse"
              data-auto_prompt="false">
          </div>
          <div class="flex justify-center my-3">
            <div class="g_id_signin shadow rounded-full"
                data-type="standard"
                data-shape="pill"
                data-theme="filled_black"
                data-text="sign_in_with"
                data-size="large"
                data-logo_alignment="left"
                data-width="300"
                data-locale="en">
            </div>
          </div>

          <!-- Facebook Login -->
          <div class="flex justify-center my-3">
            <a href="/Job_poster/public/auth/login/facebook"
               class="flex items-center justify-center w-full max-w-xs bg-blue-700 hover:bg-blue-800 text-white font-medium py-2 rounded-full transition">
              <svg class="mr-2" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                <path d="M22.675 0H1.325C.593 0 0 .593 0 1.326v21.348C0 23.407.593 24 1.325 24h11.495v-9.294H9.692v-3.622h3.128V8.413c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.464.099 2.794.143v3.24l-1.918.001c-1.504 0-1.796.715-1.796 1.763v2.312h3.587l-.467 3.622h-3.12V24h6.116c.73 0 1.324-.593 1.324-1.326V1.326C24 .593 23.407 0 22.675 0z"/>
              </svg>
              Sign in with Facebook
            </a>
          </div>

          <p class="mt-6 text-center text-gray-700">
            Already have an account? <a href="/Job_poster/public/auth/login" class="text-blue-600 hover:underline">Login here</a>
          </p>
        </form>
      </div>
    </div>
  </div>
</div>

<?php 
  $additionalJS = ["/Job_poster/public/javascript/handleCredentials.js"];
  include __DIR__ . '/../layouts/public_footer.php'; 
?>
