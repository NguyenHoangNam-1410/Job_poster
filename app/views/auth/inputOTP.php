<?php
$pageTitle = 'Verify OTP | WorkNest';
$additionalCSS = [
  "/Worknest/public/css/auth/login.css",
  "/Worknest/public/css/auth/inputOTP.css"
];
include __DIR__ . '/../layouts/public_header.php'; ?>
<div class="min-h-screen flex items-center justify-center px-4">
  <div class="flex flex-col md:flex-row md:gap-12 w-full max-w-6xl items-center">

    <!-- OTP Verification Form -->
    <div class="w-full md:w-1/2 max-w-lg bg-white shadow-xl rounded-xl p-10 animate-fadeInUp">
      <h2 class="text-3xl font-bold text-center mb-6 text-gray-800">🔑 One-Time Password</h2>

      <?php if (isset($_SESSION['error-OTP'])): ?>
        <div class="alert-login">
          <?= $_SESSION['error-OTP'] ?? '' ?>
        </div>
        <?php unset($_SESSION['error-OTP']); ?>
      <?php endif; ?>

      <form method="POST" action="/Worknest/public/auth/login/forgot-password/verify-otp" class="space-y-5">
        <div>
          <label for="otp" class="block text-gray-700 font-semibold mb-2">Enter One-Time Password</label>
          <div id="otp-inputs" class="flex justify-center gap-3">
            <?php for ($i = 1; $i <= 5; $i++): ?>
              <input type="text" id="otp<?= $i ?>" name="otp<?= $i ?>" maxlength="1" inputmode="numeric" required
                class="otp-input w-12 h-12 text-center">
            <?php endfor; ?>
          </div>
        </div>

        <button type="submit"
          class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition">
          Verify OTP
        </button>
        <a href="/Worknest/public/auth/login"
          class="block text-center mt-2 text-blue-600 hover:underline">
          Back to Login
        </a>
      </form>

      <p class="mt-6 text-center text-gray-600">
        Didn't receive the OTP? <a href="/Worknest/public/auth/login/forgot-password"
          class="text-blue-600 hover:underline">Resend</a><br>
        Don't have an account? <a href="/Worknest/public/auth/register" class="text-blue-600 hover:underline">Register
          here</a>
      </p>
    </div>

    <!-- Welcome Section Column -->
    <div class="hidden md:flex md:flex-col md:w-1/2 text-center justify-center animate-fadeInDown">
      <h1 class="welcome-title">Verify your <span> OTP </span></h1>
      <p class="welcome-subtitle">Enter the 5-digit code we sent to your email.</p>
    </div>

  </div>
</div>

<script>
  // Auto-focus and move between OTP inputs
  const otpInputs = document.querySelectorAll('.otp-input');
  
  otpInputs.forEach((input, index) => {
    // Only allow numbers
    input.addEventListener('input', (e) => {
      e.target.value = e.target.value.replace(/[^0-9]/g, '');
      
      // Auto-move to next input
      if (e.target.value && index < otpInputs.length - 1) {
        otpInputs[index + 1].focus();
      }
    });
    
    // Handle backspace
    input.addEventListener('keydown', (e) => {
      if (e.key === 'Backspace' && !e.target.value && index > 0) {
        otpInputs[index - 1].focus();
      }
    });
    
    // Handle paste
    input.addEventListener('paste', (e) => {
      e.preventDefault();
      const pastedData = e.clipboardData.getData('text').replace(/[^0-9]/g, '').slice(0, 5);
      pastedData.split('').forEach((char, i) => {
        if (otpInputs[index + i]) {
          otpInputs[index + i].value = char;
        }
      });
      // Focus last filled input
      const lastIndex = Math.min(index + pastedData.length - 1, otpInputs.length - 1);
      otpInputs[lastIndex].focus();
    });
  });
  
  // Focus first input on load
  if (otpInputs.length > 0) {
    otpInputs[0].focus();
  }
</script>

<?php
$additionalJS = ["/javascript/handleCredentials.js"];
include __DIR__ . '/../layouts/public_footer.php';
?>