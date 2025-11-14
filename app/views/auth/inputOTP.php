<?php 
  $additionalCSS = [
    "/Job_poster/public/css/
auth/inputOTP.css",
    "/Job_poster/public/css/
auth/forgotPassword.css"
  ];
  include __DIR__ . '/../layouts/public_header.php'; 
?>

<div class="min-h-screen w-full flex flex-col items-center justify-center py-12">
  <div class="w-full max-w-md space-y-4">

    <!-- Page Title -->
    <div class="text-center animate-fadeInDown">
      <h1 class="welcome-title">Verify your OTP</h1>
      <p class="welcome-subtitle">Enter the 5-digit code we sent to your email.</p>
    </div>

    <!-- OTP Verification Card -->
    <div class="card animate-fadeInUp">
      <h2 class="signup-title">🔑 One-Time Password</h2>
      <?php if (isset($_SESSION['error-OTP'])): ?>
        <div class="alert-login mb-4"><?= $_SESSION['error-OTP']?? '' ?></div>
        <?php unset($_SESSION['error-OTP']); ?>
      <?php endif; ?>
      <form method="POST" action="/auth/login/forgot-password/verify-otp" class="space-y-5 text-center">

        <label for="otp" class="form-label block">Enter One-Time Password</label>

        <div id="otp-inputs" class="flex justify-center gap-3">
          <?php for ($i = 1; $i <= 5; $i++): ?>
            <input 
              type="text" 
              id="otp<?= $i ?>" 
              name="otp<?= $i ?>" 
              maxlength="1" 
              inputmode="numeric" 
              required
              class="form-control w-12 h-12 text-center border rounded-md focus:ring-2 focus:ring-pink-400 focus:outline-none transition-transform"
            >
          <?php endfor; ?>
        </div>

        <button type="submit" class="btn-primary text-white w-full py-2 mt-4">Verify OTP</button>
        <a href="/auth/login" class="btn-secondary w-full block text-center py-2">Back to Login</a>
      </form>

      <p class="mt-6 text-center text-gray-600">
        Didn’t receive the OTP? <a href="/auth/login/forgot-password" class="text-blue-600 hover:underline">Resend</a><br>
        Don’t have an account? <a href="/auth/register" class="text-blue-600 hover:underline">Register here</a>
      </p>
    </div>

  </div>
</div>

<?php 
  $additionalJS = ["/javascript/handleCredentials.js"];
  include __DIR__ . '/../layouts/public_footer.php'; 
?>
