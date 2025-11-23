<?php
$pageTitle = 'Reset Password | WorkNest';
$additionalCSS = [
  "/Worknest/public/css/auth/login.css",
  "/Worknest/public/css/auth/forgotPassword.css"
];
include __DIR__ . '/../layouts/public_header.php'; ?>
<div class="min-h-screen flex items-center justify-center px-4">
  <div class="flex flex-col md:flex-row md:gap-12 w-full max-w-6xl items-center">

    <!-- Reset Password Form -->
    <div class="w-full md:w-1/2 max-w-lg bg-white shadow-xl rounded-xl p-10 animate-fadeInUp">
      <h2 class="text-3xl font-bold text-center mb-6 text-gray-800">🔑 Reset Password</h2>

      <?php if (isset($_SESSION['error-reset'])): ?>
        <div class="alert-login">
          <?= $_SESSION['error-reset']; ?>
        </div>
        <?php unset($_SESSION['error-reset']); ?>
      <?php endif; ?>

      <form method="POST" action="/Worknest/public/auth/login/forgot-password/reset-password" class="space-y-5">
        <div>
          <label for="password" class="block text-gray-700 font-semibold mb-2">
            Password <span class="text-red-500">*</span>
          </label>
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
          <ul class="password-requirements">
            <li>At least 8 characters</li>
            <li>Include a number</li>
            <li>Include uppercase and lowercase letters</li>
            <li>Include a special character</li>
          </ul>
        </div>

        <div>
          <label for="confirm_password" class="block text-gray-700 font-semibold mb-2">
            Confirm Password <span class="text-red-500">*</span>
          </label>
          <div class="relative">
            <input type="password" id="confirm_password" name="confirm_password" required
              class="w-full border border-gray-300 rounded-lg px-4 py-2 pr-12 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
            <button type="button" id="toggleConfirmPassword"
              class="absolute top-1/2 right-3 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none"
              aria-label="Show password">
              <svg id="eyeIconConfirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
            </button>
          </div>
          <div class="text-red-600 text-sm mt-1 hidden is-invalid-not-match">Passwords do not match.</div>
        </div>

        <button type="submit"
          class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition">
          Reset Password
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
      <h1 class="welcome-title">Reset your <span> Password </span></h1>
      <p class="welcome-subtitle">Now reset your password to get back to Worknest!</p>
    </div>

  </div>
</div>

<script>
  // Password validation
  const passwordInput = document.getElementById('password');
  const confirmPasswordInput = document.getElementById('confirm_password');
  const requirements = document.querySelectorAll('.password-requirements li');
  const mismatchError = document.querySelector('.is-invalid-not-match');

  function validatePassword(password) {
    const checks = {
      length: password.length >= 8,
      number: /\d/.test(password),
      uppercase: /[A-Z]/.test(password),
      lowercase: /[a-z]/.test(password),
      special: /[!@#$%^&*(),.?":{}|<>]/.test(password)
    };

    requirements.forEach((req, index) => {
      const isValid = Object.values(checks)[index];
      if (isValid) {
        req.classList.add('valid');
      } else {
        req.classList.remove('valid');
      }
    });

    return Object.values(checks).every(check => check === true);
  }

  function checkPasswordMatch() {
    if (confirmPasswordInput.value && passwordInput.value !== confirmPasswordInput.value) {
      mismatchError.classList.remove('hidden');
      confirmPasswordInput.classList.add('border-red-500');
    } else {
      mismatchError.classList.add('hidden');
      confirmPasswordInput.classList.remove('border-red-500');
    }
  }

  passwordInput.addEventListener('input', (e) => {
    validatePassword(e.target.value);
    if (confirmPasswordInput.value) {
      checkPasswordMatch();
    }
  });

  confirmPasswordInput.addEventListener('input', checkPasswordMatch);

  // Show password toggle
  const togglePassword = document.getElementById('togglePassword');
  const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
  const eyeIcon = document.getElementById('eyeIcon');
  const eyeIconConfirm = document.getElementById('eyeIconConfirm');

  togglePassword.addEventListener('mousedown', (e) => {
    e.preventDefault();
    passwordInput.type = 'text';
    eyeIcon.innerHTML = `
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
    `;
  });

  togglePassword.addEventListener('mouseup', () => {
    passwordInput.type = 'password';
    eyeIcon.innerHTML = `
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
    `;
  });

  togglePassword.addEventListener('mouseleave', () => {
    passwordInput.type = 'password';
    eyeIcon.innerHTML = `
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
    `;
  });

  toggleConfirmPassword.addEventListener('mousedown', (e) => {
    e.preventDefault();
    confirmPasswordInput.type = 'text';
    eyeIconConfirm.innerHTML = `
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
    `;
  });

  toggleConfirmPassword.addEventListener('mouseup', () => {
    confirmPasswordInput.type = 'password';
    eyeIconConfirm.innerHTML = `
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
    `;
  });

  toggleConfirmPassword.addEventListener('mouseleave', () => {
    confirmPasswordInput.type = 'password';
    eyeIconConfirm.innerHTML = `
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
    `;
  });
</script>

<?php
$additionalJS = [
  "/Worknest/public/javascript/handleCredentials.js"
];
include __DIR__ . '/../layouts/public_footer.php'; ?>