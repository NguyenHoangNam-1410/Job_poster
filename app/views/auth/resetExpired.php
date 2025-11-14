<?php
  $additionalCSS = [
    "/Job_poster/public/css/auth/forgotPassword.css"
  ];
include __DIR__ . '/../layouts/public_header.php'; ?>
<div class="container mx-auto py-12 px-4">
  <div class="flex flex-col items-center justify-center space-y-8">

    <div class="w-full md:w-7/12 text-center">
      <h1 class="text-3xl font-bold mb-2 welcome-title">Your Reset Session Expired</h1>
      <p class="text-gray-600 welcome-subtitle">Your password reset session has expired. Please request a new OTP to continue.</p>
    </div>
    <div class="w-full md:w-7/12 max-w-lg bg-white shadow-lg rounded-lg p-8 animate-fadeInUp">
      <h2 class="text-2xl font-semibold text-center mb-6">🔑 Reset Session Expired</h2>

      <?php if (isset($_SESSION['error-expired'])): ?>
        <div class="alert-login mb-4">
          <?= $_SESSION['error-expired']; ?>
        </div>
        <?php unset($_SESSION['error-expired']); ?>
      <?php endif; ?>

      <form method="POST" action="/Job_poster/public/auth/login/forgot-password/send-otp" class="space-y-4">
        <div>
          <label for="email" class="block text-gray-700 font-medium mb-1">Email address</label>
          <input type="email" id="email" name="email" required placeholder="Enter your registered email"
                 class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-md mb-2">
          Request New OTP
        </button>
        <a href="/auth/login" class="w-full block text-center bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 rounded-md">
          Back to Login
        </a>
      </form>

      <p class="mt-6 text-center text-gray-600">
        Don’t have an account? <a href="/register" class="text-blue-600 hover:underline">Register here</a>
      </p>
    </div>
  </div>
</div>
<?php
  $additionalJS = [
    "/Job_poster/public/javascript/handleCredentials.js"
  ];
include __DIR__ . '/../layouts/public_footer.php'; ?>