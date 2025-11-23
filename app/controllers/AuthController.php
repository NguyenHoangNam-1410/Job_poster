<?php
require_once __DIR__ . '/../services/UserService.php';

class AuthController
{
    private $userService;
    public function __construct()
    {
        $this->userService = new UserService();
    }
    public function index()
    {
        require_once __DIR__ . '/../views/public/home.php';
    }
    public function loginForm()
    {
        require_once __DIR__ . '/../views/auth/login.php';
        exit;
    }

    public function showRegisterForm()
    {
        // Lưu redirect URL nếu có trong query parameter
        if (isset($_GET['redirect'])) {
            $_SESSION['register_redirect'] = $_GET['redirect'];
        }
        require_once __DIR__ . '/../views/auth/register.php';
        exit;
    }

    public function handleLocalRegister()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $fullname = $_POST['name'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            if ($this->userService->getUserByEmail($email)) {
                if ($this->userService->getAuthProvider($email) === 'google') {
                    $_SESSION['register_error'] = 'Email already exists with Google. Please login with Google.';
                    header("Location: " . BASE_URL . "/auth/register");
                    exit;
                } elseif ($this->userService->getAuthProvider($email) === 'facebook') {
                    $_SESSION['register_error'] = 'Email already exists with Facebook. Please login with Facebook.';
                    header("Location: " . BASE_URL . "/auth/register");
                    exit;
                }
                $_SESSION['register_error'] = 'Email already exists. Please use a different email or login.';
                header("Location: " . BASE_URL . "/auth/register");
                exit;
            }
            $rules = [
                '/.{8,}/', // At least 8 characters
                '/[0-9]/', // At least one number
                '/[A-Z]/', // At least one uppercase letter
                '/[a-z]/', // At least one lowercase letter
                '/[!@#$%^&*(),.?":{}|<>]/' // At least one special character
            ];

            foreach ($rules as $rule) {
                if (!preg_match($rule, $password)) {
                    $_SESSION['register_error'] = 'Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character.';
                    header("Location: " . BASE_URL . "/auth/register");
                    exit;
                }
            }

            if ($password !== $confirmPassword) {
                $_SESSION['register_error'] = 'Your confirmation password do not match.';
                header("Location: " . BASE_URL . "/auth/register");
                exit;
            }

            $data = [
                'fullname' => $fullname,
                'email' => $email,
                'password' => $password,
                'role' => 'Employer'
            ];
            if (!$this->userService->registerUser($data)) {
                $_SESSION['register_error'] = 'Registration failed. Please try again.';
                header("Location: " . BASE_URL . "/auth/register");
                exit;
            }
            $user = $this->userService->getUserByEmail($email);
            
            // Create employer record for the new employer user
            require_once __DIR__ . '/../dao/EmployerDAO.php';
            require_once __DIR__ . '/../models/Employer.php';
            $employerDAO = new EmployerDAO();
            $newEmployer = new Employer(
                null,  // id (auto-generated)
                null,  // company_name
                null,  // website
                null,  // logo
                null,  // contact_phone
                null,  // contact_email
                null,  // contact_person
                null,  // description
                $user->getId()  // user_id
            );
            $employerDAO->create($newEmployer);
            
            $_SESSION['user'] = [
                'id' => $user->getId(),
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'role' => $user->getRole(),
                'auth_provider' => $user->getAuthProvider(),
            ];
            
            // Redirect logic:
            // - Nếu có register_redirect trong session (từ nút Job Posting) → redirect đến đó
            // - Nếu không có → redirect về homepage
            if (isset($_SESSION['register_redirect'])) {
                $redirectUrl = $_SESSION['register_redirect'];
                unset($_SESSION['register_redirect']); // Xóa redirect URL sau khi dùng
                header("Location: " . BASE_URL . $redirectUrl);
            } else {
                // Đăng ký thông thường → về homepage
                header("Location: " . BASE_URL . "/");
            }
            exit;
        }
    }

    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();
        header("Cache-Control: no-cache, no-store, must-revalidate");
        header("Location: " . BASE_URL . "/");
        exit;
    }

    public function handleGoogleLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = json_decode(file_get_contents('php://input'), true);
                $token = $data['token'] ?? null;
                if (!$token) {
                    http_response_code(400);
                    echo 'ID token is required.';
                    error_log("Google login: Missing token");
                    return;
                }
                
                $googleClientId = $_ENV['GOOGLE_CLIENT_ID'] ?? null;
                if (empty($googleClientId)) {
                    http_response_code(500);
                    echo 'Google OAuth is not configured. Please configure GOOGLE_CLIENT_ID in .env file.';
                    error_log("Google login: GOOGLE_CLIENT_ID not set in .env");
                    return;
                }
                
                $client = new Google_Client(['client_id' => $googleClientId]);
                $payload = $client->verifyIdToken($token);
                if ($payload) {
                $email = $payload['email'];
                $name = $payload['name'];
                $avatar = $payload['picture'] ?? null;
                $user = $this->userService->getUserByEmail($email);
                $currentUrl = $_SERVER['REQUEST_URI'];
                if ($user && $user->getAuthProvider() !== 'google') {
                    if ($currentUrl === "/Worknest/public/auth/login") {
                        $_SESSION['login_error'] = 'Email already exists. Please use a different email or login.';
                        header("Location: " . BASE_URL . "/auth/login");
                        exit;
                    } else {
                        $_SESSION['register_error'] = 'Email already exists. Please use a different email or login.';
                        header("Location: " . BASE_URL . "/auth/register");
                        exit;
                    }
                }
                if (!$user) {
                    $user = $this->userService->createUserGoogle($email, $name, $avatar);
                }
                $_SESSION['user'] = [
                    'id' => $user->getId(),
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
                    'role' => $user->getRole(),
                    'auth_provider' => $user->getAuthProvider(),
                    'avatar' => $user->getAvatar()
                ];
                
                // Redirect based on role (same as local login)
                $redirectUrl = BASE_URL . "/";
                switch ($user->getRole()) {
                    case 'Admin':
                        $redirectUrl = BASE_URL . "/statistics";
                        break;
                    case 'Staff':
                        $redirectUrl = BASE_URL . "/staff/home";
                        break;
                    case 'Employer':
                        $redirectUrl = BASE_URL . "/employer/home";
                        break;
                    default:
                        $redirectUrl = BASE_URL . "/";
                }
                
                // Check register_redirect nếu đăng ký từ trang register
                if (isset($_SESSION['register_redirect'])) {
                    $redirectUrl = BASE_URL . $_SESSION['register_redirect'];
                    unset($_SESSION['register_redirect']);
                }
                
                // Return redirect URL in JSON for JavaScript to handle
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'redirect' => $redirectUrl]);
                exit;

                } else {
                    http_response_code(401);
                    $errorMsg = 'Invalid ID token. Please check GOOGLE_CLIENT_ID in .env file matches your Google Cloud Console configuration.';
                    error_log("Google login: Token verification failed. Client ID: " . substr($googleClientId, 0, 20) . "...");
                    echo $errorMsg;
                }
            } catch (Exception $e) {
                http_response_code(500);
                $errorMsg = 'Google login error: ' . $e->getMessage();
                error_log("Google login exception: " . $e->getMessage());
                error_log("Stack trace: " . $e->getTraceAsString());
                echo $errorMsg;
            }
        } else {
            http_response_code(405);
            echo 'Method Not Allowed';
        }
    }

    public function handleLocalLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            
            // Basic email validation (allow test emails like worknest@123)
            if (empty($email) || strpos($email, '@') === false) {
                $_SESSION['login_error'] = 'Invalid email format.';
                header("Location: " . BASE_URL . "/auth/login");
                exit;
            }
            
            $user = $this->userService->getUserByEmail($email);
            
            // Check if user exists
            if (!$user) {
                error_log("Login failed: User not found for email: " . $email);
                $_SESSION['login_error'] = 'Invalid email or password.';
                header("Location: " . BASE_URL . "/auth/login");
                exit;
            }
            
            // Check if user is using local authentication
            // Note: NULL auth_provider is treated as 'local' (legacy users)
            $authProvider = $user->getAuthProvider();
            if ($authProvider !== null && $authProvider !== 'local') {
                error_log("Login failed: User email " . $email . " uses " . $authProvider . " authentication, not local");
                if ($authProvider === 'google') {
                    $_SESSION['login_error'] = 'This email is registered with Google. Please login with Google.';
                } elseif ($authProvider === 'facebook') {
                    $_SESSION['login_error'] = 'This email is registered with Facebook. Please login with Facebook.';
                } else {
                    $_SESSION['login_error'] = 'Invalid email or password.';
                }
                header("Location: " . BASE_URL . "/auth/login");
                exit;
            }
            
            // Verify password
            if (!$this->userService->verifyPasswordLogin($email, $password)) {
                error_log("Login failed: Invalid password for email: " . $email);
                $_SESSION['login_error'] = 'Invalid email or password.';
                header("Location: " . BASE_URL . "/auth/login");
                exit;
            }
            
            // Login successful
            $_SESSION['user'] = [
                'id' => $user->getId(),
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'role' => $user->getRole(),
                'auth_provider' => $user->getAuthProvider(),
                'avatar' => $user->getAvatar()
            ];

            // Redirect based on role
            switch ($user->getRole()) {
                case 'Admin':
                    header("Location: " . BASE_URL . "/statistics");
                    break;
                case 'Staff':
                    header("Location: " . BASE_URL . "/staff/home");
                    break;
                case 'Employer':
                    header("Location: " . BASE_URL . "/employer/home");
                    break;
                default:
                    header("Location: " . BASE_URL . "/");
            }
            exit;
        }
    }

    public function handleFacebookLogin()
    {
        $clientId = $_ENV['FACEBOOK_CLIENT_ID'];
        $redirectUri = $_ENV['FACEBOOK_REDIRECT_URI'];
        // Facebook API v19+ - email is returned automatically with public_profile
        // Do not include 'email' in scope to avoid warning
        $scope = 'public_profile';

        $url = "https://www.facebook.com/v19.0/dialog/oauth?client_id=$clientId&redirect_uri=$redirectUri&scope=$scope&response_type=code";
        header("Location: $url");
        exit;
    }

    public function facebookCallback()
    {
        if (!isset($_GET['code'])) {
            echo "Facebook login failed or canceled.";
            exit;
        }

        $code = $_GET['code'];
        $accessToken = $this->userService->getFacebookAccessToken($code);
        if (!$accessToken) {
            echo "Failed to get access token from Facebook.";
            exit;
        }

        $user = $this->userService->getFacebookUser($accessToken);
        if (!$user) {
            echo "Failed to fetch Facebook user info.";
            exit;
        }

        // Log Facebook response for debugging
        error_log("Facebook user data: " . json_encode($user));

        // Check if email is available
        $email = $user['email'] ?? null;
        $facebookId = $user['id'] ?? null;
        
        // If no email, create a temporary email from Facebook ID
        if (empty($email)) {
            if (empty($facebookId)) {
                $_SESSION['login_error'] = 'Failed to get Facebook account information. Please try again.';
                header("Location: " . BASE_URL . "/auth/login");
                exit;
            }
            // Create email from Facebook ID (format: facebook_{id}@facebook.temp)
            $email = 'facebook_' . $facebookId . '@facebook.temp';
            error_log("Facebook email not available, using generated email: " . $email);
        }
        
        $name = $user['name'] ?? 'Facebook User';
        $avatar = $user['picture']['data']['url'] ?? null;
        $user = $this->userService->getUserByEmail($email);
        $currentUrl = $_SERVER['REQUEST_URI'];
        if ($user && $user->getAuthProvider() !== 'facebook') {
            if ($currentUrl === "/Worknest/public/auth/login") {
                $_SESSION['login_error'] = 'Email already exists. Please use a different email or login.';
                header("Location: " . BASE_URL . "/auth/login");

            } else {
                $_SESSION['register_error'] = 'Email already exists. Please use a different email or login.';
                header("Location: " . BASE_URL . "/auth/register");
            }
        }

        if (!$user) {
            // Create new user
            $user = $this->userService->createUserFacebook($email, $name, $avatar);
        }

        $_SESSION['user'] = [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'role' => $user->getRole(),
            'auth_provider' => $user->getAuthProvider(),
            'avatar' => $user->getAvatar()
        ];

        // Redirect logic: check register_redirect nếu đăng ký từ trang register
        if (isset($_SESSION['register_redirect'])) {
            $redirectUrl = $_SESSION['register_redirect'];
            unset($_SESSION['register_redirect']);
            header("Location: " . BASE_URL . $redirectUrl);
        } else {
            header("Location: " . BASE_URL . "/");
        }
        exit;
    }

    public function showForgotPasswordForm()
    {
        require_once __DIR__ . '/../views/auth/forgot-password.php';
        exit;
    }

    public function sendPasswordResetOTP()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $user = $this->userService->getUserByEmail($email);
            $currentUrl = $_SERVER['REQUEST_URI'];
            if ($user) {
                if ($this->userService->getAuthProvider($email) === 'google') {
                    $error = 'This email is registered with Google. We cannot reset your password with Google.';
                    if ($currentUrl === "/Worknest/public/auth/login/forgot-password/resetExpired") {
                        $_SESSION['error-expired'] = $error;
                        header("Location: " . BASE_URL . "/auth/login/forgot-password/resetExpired");
                    } else {
                        $_SESSION['error-message'] = $error;
                        header("Location: " . BASE_URL . "/auth/login/forgot-password");
                    }
                    exit;
                }
                if ($this->userService->getAuthProvider($email) === 'facebook') {
                    $error = 'This email is registered with Facebook. We cannot reset your password with Facebook.';

                    if ($currentUrl === "/Worknest/public/auth/login/forgot-password/resetExpired") {
                        $_SESSION['error-expired'] = $error;
                        header("Location: " . BASE_URL . "/auth/login/forgot-password/resetExpired");
                    } else {
                        $_SESSION['error-message'] = $error;
                        header("Location: " . BASE_URL . "/auth/login/forgot-password");
                    }
                    exit;
                }
                $this->userService->generateAndSendOTP($email);
                header("Location: " . BASE_URL . "/auth/login/forgot-password/input-otp");
                exit;
            } else {
                $error = 'No email found for that account.';
                if ($currentUrl === "/Worknest/public/auth/login/forgot-password/resetExpired") {
                    $_SESSION['error-expired'] = $error;
                    header("Location: " . BASE_URL . "/auth/login/forgot-password/resetExpired");
                } else {
                    $_SESSION['error-message'] = $error;
                    header("Location: " . BASE_URL . "/auth/login/forgot-password");
                }
                exit;
            }
        }
    }
    public function showVerifyOTPForm()
    {
        $email = $_SESSION['otp-email'] ?? null;
        if (!$email) {
            header("Location: " . BASE_URL . "/auth/login/forgot-password/resetExpired");
            exit;
        }
        require_once __DIR__ . '/../views/auth/inputOtp.php';
        exit;
    }
    public function verifyPasswordResetOTP()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['otp-email'])) {
                header("Location: " . BASE_URL . "/auth/login/forgot-password/resetExpired");
                exit;
            }
            $otpParts = [
                $_POST['otp1'] ?? '',
                $_POST['otp2'] ?? '',
                $_POST['otp3'] ?? '',
                $_POST['otp4'] ?? '',
                $_POST['otp5'] ?? ''
            ];
            $otp = implode('', $otpParts); // Combine parts to form full OTP
            if ($this->userService->verifyOTP($otp)) {
                header("Location: " . BASE_URL . "/auth/login/forgot-password/reset-password-form");
                exit;
            } else {
                $_SESSION['error-OTP'] = 'Invalid OTP. Please try again.';
                header("Location: " . BASE_URL . "/auth/login/forgot-password/input-otp");
                exit;
            }
        }
    }

    public function showResetPasswordForm()
    {
        if (!$_SESSION['reset-email']) {
            header("Location: " . BASE_URL . "/auth/login/forgot-password/resetExpired");
            exit;
        }
        $expiry = $_SESSION['reset-expire'] ?? '';
        if (time() > $expiry) {
            unset($_SESSION['reset-email']);
            unset($_SESSION['reset-expire']);
            header("Location: " . BASE_URL . "/auth/login/forgot-password/resetExpired");
            exit;
        }
        require_once __DIR__ . '/../views/auth/resetPassword.php';
        exit;
    }

    public function resetPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_SESSION['reset-email'] ?? '';
            $expiry = $_SESSION['reset-expire'] ?? '';
            if (time() > $expiry) {
                unset($_SESSION['reset-email']);
                unset($_SESSION['reset-expire']);
                header("Location: " . BASE_URL . "/auth/login/forgot-password/resetExpired");
                exit;
            }
            if (!$email) {
                header("Location: " . BASE_URL . "/auth/login/forgot-password/resetExpired");
                exit;
            }
            $newPassword = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            if ($newPassword !== $confirmPassword) {
                $_SESSION['error-reset'] = 'Your confirmed password do not match.';
                header("Location: " . BASE_URL . "/auth/login/forgot-password/reset-password-form");
                exit;
            }
            $rules = [
                '/.{8,}/', // At least 8 characters
                '/[0-9]/', // At least one number
                '/[A-Z]/', // At least one uppercase letter
                '/[a-z]/', // At least one lowercase letter
                '/[!@#$%^&*(),.?":{}|<>]/' // At least one special character
            ];
            foreach ($rules as $rule) {
                if (!preg_match($rule, $newPassword)) {
                    $_SESSION['error-reset'] = 'Password must be at least 8 characters long and contain at least one number, one uppercase letter, one lowercase letter, and one special character.';
                    header("Location: " . BASE_URL . "/auth/login/forgot-password/reset-password-form");
                    exit;
                }
            }
            error_log("Resetting password for email: " . $email);
            if (!$this->userService->updatePasswordByEmail($email, $newPassword)) {
                $_SESSION['error-reset'] = 'Something went wrong. Please try again.';
                header("Location: " . BASE_URL . "/auth/login/forgot-password/reset-password-form");
                exit;
            }
            ;
            // Destroy session
            unset($_SESSION['reset-email']);
            unset($_SESSION['reset-expire']);
            header("Location: " . BASE_URL . "/auth/login");
            exit;
        }
    }

    public function showExpiredTokenOrOTPPage()
    {
        require_once __DIR__ . '/../views/auth/reset-expired.php';
        exit;
    }

    public function checkEmail()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            header('Content-Type: application/json');
            if ($this->userService->getUserByEmail($email)) {
                if ($this->userService->getAuthProvider($email) === 'google') {
                    echo json_encode([
                        'exists' => true,
                        'message' => 'Email already exists with Google. Please login with Google.'
                    ]);
                    exit;
                } elseif ($this->userService->getAuthProvider($email) === 'facebook') {
                    echo json_encode([
                        'exists' => true,
                        'message' => 'Email already exists with Facebook. Please login with Facebook.'
                    ]);
                    exit;
                } else {
                    echo json_encode([
                        'exists' => true,
                        'message' => 'Email already exists'
                    ]);
                    exit;
                }
            } else {
                echo json_encode([
                    'exists' => false,
                    'message' => 'Email is available'
                ]);
            }

            exit;
        }
    }
}
