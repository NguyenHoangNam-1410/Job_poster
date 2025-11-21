<?php
$additionalCSS = ["/Job_poster/public/css/
auth/forgotPassword.css"
];
include __DIR__ . '/../layouts/public_header.php';
?>

<div class="min-h-screen w-full flex flex-col items-center justify-center py-12">
  <div class="w-full max-w-md space-y-4">
    <div class="text-center animate-fadeInDown">
      <h1 class="welcome-title">Forgot your password?</h1>
      <p class="welcome-subtitle">Don’t worry! We’ll send you an OTP to reset your password!</p>
    </div>

    <div class="card animate-fadeInUp">
      <h2 class="signup-title">🔑 Send OTP</h2>

      <?php if (isset($_SESSION['error-message'])): ?>
        <div class="alert-login mb-4"><?= $_SESSION['error-message'] ?? '' ?></div>
        <?php unset($_SESSION['error-message']); ?>
      <?php endif; ?>

      <form method="POST" action="/Job_poster/public/auth/login/forgot-password/send-otp" class="space-y-5">
        <div>
          <label for="email" class="form-label">Email address<span class="required">*</span></label>
          <input type="email" id="email" name="email" required placeholder="Enter your registered email"
            class="form-control w-full">
        </div>

        <button type="submit" class="btn-primary text-white w-full py-2">Send OTP</button>
        <a href="/auth/login" class="btn-secondary w-full block text-center py-2">Back to Login</a>
      </form>

      <p class="mt-6 text-center text-gray-600">
        Don’t have an account? <a href="/auth/register" class="text-blue-600 hover:underline">Register here</a>
      </p>
    </div>

  </div>
</div>

<?php
$additionalJS = ["/javascript/handleCredentials.js"];
include __DIR__ . '/../layouts/public_footer.php';
?>