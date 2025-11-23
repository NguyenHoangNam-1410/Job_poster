<?php
$pageTitle = 'Forgot Password | WorkNest';
$additionalCSS = [
  "/Worknest/public/css/auth/login.css"
];
include __DIR__ . '/../layouts/public_header.php'; ?>
<div class="min-h-screen flex items-center justify-center px-4">
  <div class="flex flex-col md:flex-row md:gap-12 w-full max-w-6xl items-center">

    <!-- Forgot Password Form -->
    <div class="w-full md:w-1/2 max-w-lg bg-white shadow-xl rounded-xl p-10 animate-fadeInUp">
      <h2 class="text-3xl font-bold text-center mb-6 text-gray-800">Forgot Password?</h2>
      <p class="text-center text-gray-600 mb-6">Don't worry! We'll send you an OTP to reset your password!</p>

      <?php if (isset($_SESSION['error-message'])): ?>
        <div class="alert-login mb-4">
          <?= $_SESSION['error-message']; ?>
        </div>
        <?php unset($_SESSION['error-message']); ?>
      <?php endif; ?>

      <form method="POST" action="/Worknest/public/auth/login/forgot-password/send-otp" class="space-y-5">
        <div>
          <label for="email" class="block text-gray-700 font-semibold mb-2">Email</label>
          <input type="email" id="email" name="email" required placeholder="Enter your registered email"
            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
        </div>

        <button type="submit"
          class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition">
          Send OTP
        </button>

        <a href="/Worknest/public/auth/login"
          class="block text-center mt-2 text-blue-600 hover:underline">
          Back to Login
        </a>
      </form>

      <p class="mt-6 text-center text-gray-600">
        Don't have an account? <a href="/Worknest/public/auth/register" class="text-blue-600 hover:underline">Register
          here</a>
      </p>
    </div>

    <!-- Welcome Section Column -->
    <div class="hidden md:flex md:flex-col md:w-1/2 text-center justify-center animate-fadeInDown">
      <h1 class="welcome-title">Reset Your <span>Password</span></h1>
      <p class="welcome-subtitle">We'll help you get back into your account</p>
    </div>

  </div>
</div>

<?php
$additionalJS = [
  "/Worknest/public/javascript/handleCredentials.js"
];
include __DIR__ . '/../layouts/public_footer.php'; ?>