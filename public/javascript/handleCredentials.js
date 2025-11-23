function handleCredentialResponse(response) {
  fetch("/Worknest/public/auth/login/google", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ token: response.credential }),
    credentials: "include", // include cookies cross-origin
  }).then((res) => {
    if (res.ok) window.location.href = "/";
    else alert("Google login failed");
  });
}
let confirmPassword = $("#confirm_password");
let requirements = $(".password-requirements li");
let password = $("#password");
let email = $("#email");
let isNotMatch = $(".is-invalid-not-match");
let error_email = $(".error-email");

$(document).ready(() => {
  isNotMatch.hide();
  error_email.hide();
});
//check if the password contains necessary criteria
password.on("input", () => {
  const rules = [
    /.{8,}/, // at least 8 characters
    /[0-9]/, // at least 1 number
    /[A-Z]/, // at least 1 uppercase letter
    /[a-z]/, // at least 1 lowercase letter
    /[!@#$%^&*(),.?":{}|<>]/, // at least 1 special character
  ];

  // Map the 5 rules to 4 HTML list items
  // Rules 2 and 3 (uppercase and lowercase) map to item 2 (combined)
  requirements.each((index, item) => {
    let isValid = false;

    if (index === 0) {
      // First item: 8+ characters
      isValid = rules[0].test(password.val());
    } else if (index === 1) {
      // Second item: at least 1 number
      isValid = rules[1].test(password.val());
    } else if (index === 2) {
      // Third item: uppercase AND lowercase
      isValid = rules[2].test(password.val()) && rules[3].test(password.val());
    } else if (index === 3) {
      // Fourth item: special character
      isValid = rules[4].test(password.val());
    }

    if (isValid) {
      $(item).addClass("valid");
      $(item).removeClass("invalid");
    } else {
      $(item).addClass("invalid");
      $(item).removeClass("valid");
    }
  });
});
confirmPassword.on("input", () => {
  if (confirmPassword.val() === password.val()) {
    isNotMatch.hide();
  } else {
    isNotMatch.show();
  }
});

let inputs = $("#otp-inputs input");

inputs.each((index, input) => {
  $(input).on("input", () => {
    if (input.value.length === input.maxLength) {
      // Move to next input
      if (index < inputs.length - 1) {
        inputs[index + 1].focus();
      }
    }
  });
  $(input).on("keydown", (e) => {
    // Handle backspace to move back
    if (e.key === "Backspace" && input.value.length === 0) {
      if (index > 0) {
        inputs[index - 1].focus();
      }
    }
  });
});

email.on("input", function () {
  const value = $(this).val();
  const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // basic email format check
  if (!emailPattern.test(value) && value.length > 0) {
    error_email.text("Invalid email format").show();
  } else {
    error_email.hide();
    $.post(
      "/check-email",
      { email: value },
      function (res) {
        if (res.exists) {
          error_email.text(res.message).show();
        } else {
          error_email.hide();
        }
      },
      "json"
    );
  }
});
/* Cookies have:
PHPSESSID: to know if user is logged in
Preferences: languages
Auth token: to know who is logged in
Temporary data
.....
*/
