<?php 
  $additionalCSS = [
    "/Job_poster/public/css/auth/forgotPassword.css"
  ];
include __DIR__ . '/../layouts/public_header.php'; ?>
<div class="container mx-auto py-12 px-4">
  <div class="flex flex-col items-center justify-center space-y-8">

    <div class="w-full md:w-1/2 text-center">
      <h1 class="text-3xl font-bold mb-2 welcome-title">Reset Password</h1>
      <p class="text-gray-600 welcome-subtitle">Now reset your password to get back to learning!</p>
    </div>
    <div class="w-full md:w-1/2 max-w-lg bg-white shadow-lg rounded-lg p-8 animate-fadeInUp">
      <h2 class="text-2xl font-semibold text-center mb-6">🔑 Reset Password</h2>

      <?php if (isset($_SESSION['error-reset'])): ?>
        <div class="alert-login mb-4">
          <?= $_SESSION['error-reset']; ?>
        </div>
        <?php unset($_SESSION['error-reset']); ?>
      <?php endif; ?>
      <form method="POST" action="/Job_poster/public/auth/login/forgot-password/reset-password" class="space-y-4">
        
        <div>
          <label for="password" class="block text-gray-700 font-medium mb-1">
            Password <span class="text-red-500">*</span>
          </label>
          <input type="password" id="password" name="password" required
                 class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <ul class="password-requirements">
              <li>At least 8 characters</li>
              <li>Include a number</li>
              <li>Include uppercase and lowercase letters</li>
              <li>Include a special character</li>
            </ul>
        </div>

        <div>
          <label for="confirm_password" class="block text-gray-700 font-medium mb-1">
            Confirm Password <span class="text-red-500">*</span>
          </label>
          <input type="password" id="confirm_password" name="confirm_password" required
                 class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
          <div class="text-red-600 text-sm mt-1 hidden is-invalid-not-match">Passwords do not match.</div>
        </div>

        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-md mb-2">
          Reset Password
        </button>
        <a href="/Job_poster/public/auth/login" class="w-full block text-center bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 rounded-md">
          Back to Login
        </a>
      </form>

      <p class="mt-6 text-center text-gray-600">
        Don’t have an account? <a href="/Job_poster/public/auth/register" class="text-blue-600 hover:underline">Register here</a>
      </p>
    </div>
  </div>
</div>
<?php
  $additionalJS = [
    "/Job_poster/public/javascript/handleCredentials.js"
  ];
include __DIR__ . '/../layouts/public_footer.php'; ?>