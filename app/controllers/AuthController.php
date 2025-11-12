<?php
require_once __DIR__ . '/../services/UserService.php';

class AuthController {
        private $userService;
    public function __construct() {
        $this->userService = new UserService();
    }
    public function index(){
        require_once __DIR__ . '/../views/auth/login.php';
        exit;
    }

    public function showRegisterForm() {
        require_once __DIR__ . '/../views/auth/register.php';
        exit;
    }

    public function handleLocalRegister() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $fullname = $_POST['name'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            if($this->userService->getUserByEmail($email)) {
                if($this->userService->getAuthProvider($email) === 'google') {
                    $_SESSION['register_error'] = 'Email already exists with Google. Please login with Google.';
                    header("Location: /auth/register");
                    exit;
                } elseif($this->userService->getAuthProvider($email) === 'facebook') {
                    $_SESSION['register_error'] = 'Email already exists with Facebook. Please login with Facebook.';
                    header("Location: /auth/register");
                    exit;
                }
                $_SESSION['register_error'] = 'Email already exists. Please use a different email or login.';
                header("Location: /auth/register");
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
                    header("Location: /auth/register");
                    exit;
                }
            }

            if($password !== $confirmPassword) {
                $_SESSION['register_error'] = 'Your confirmation password do not match.';
                header("Location: /auth/register");
                exit;
            }

            $data = [
                'fullname' => $fullname,
                'email' => $email,
                'password' => $password,
                'role' => 'Employer'
            ];
            if(!$this->userService->registerUser($data)) {
                $_SESSION['register_error'] = 'Registration failed. Please try again.';
                header("Location: /auth/register");
                exit;
            }
            $user = $this->userService->getUserByEmail($email);
            $_SESSION['user'] = [
                'id' => $user->getId(),
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'role' => $user->getRole(),
                'auth_provider' => $user->getAuthProvider(),
            ];
            header("Location: /");
            exit;
        }
    }

    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        header("Cache-Control: no-cache, no-store, must-revalidate");
        header("Location: /public/home");
        exit;
}

    public function handleGoogleLogin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $token = $data['token'] ?? null;
            if (!$token) {
                http_response_code(400);
                echo 'ID token is required.';
                return;
            }
            $client = new Google_Client(['client_id' => $_ENV['GOOGLE_CLIENT_ID']]);
            $payload = $client->verifyIdToken($token);
            if ($payload) {
                $email = $payload['email'];
                $name = $payload['name'];
                $avatar = $payload['picture'] ?? null;
                $user = $this->userService->getUserByEmail($email);
                $currentUrl = $_SERVER['REQUEST_URI'];
                if($user && $user->getAuthProvider() !== 'google') {
                    if($currentUrl === "/auth/login") {
                        $_SESSION['login_error'] = 'Email already exists. Please use a different email or login.';
                        header("Location: /auth/login");
                        exit;
                    } else {
                        $_SESSION['register_error'] = 'Email already exists. Please use a different email or login.';
                        header("Location: /auth/register");
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
                header("Location: /");
                exit;
                
            } else {
                http_response_code(401);
                echo 'Invalid ID token.';
            }
        } else {
            http_response_code(405);
            echo 'Method Not Allowed';
        }
    }

    public function handleLocalLogin() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $user = $this->userService->getUserByEmail($email);
            error_log($password);
            if($user && $this->userService->verifyPasswordLogin($email, $password)) {
                $_SESSION['user'] = [
                    'id' => $user->getId(),
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
                    'role' => $user->getRole(),
                    'auth_provider' => $user->getAuthProvider(),
                    'avatar' => $user->getAvatar()
                ];
                switch ($user->getRole()) {
                    case 'Admin':
                        header("Location: /admin/home");
                        break;
                    case 'Staff':
                        header("Location: /staff/home");
                        break;
                    case 'Employer':
                        header("Location: /employer/home");
                        break;
                    default:
                        header("Location: /public/home");
                }
                exit;
            } else {
                $_SESSION['login_error'] = 'Invalid email or password.';
                // Redirect back to login page
                header("Location: /auth/login");
                exit;
            }
        }
    }

    public function handleFacebookLogin() {
        $clientId = $_ENV['FACEBOOK_CLIENT_ID'];
        $redirectUri = $_ENV['FACEBOOK_REDIRECT_URI'];
        $scope = 'email, public_profile';
        
        $url = "https://www.facebook.com/v19.0/dialog/oauth?client_id=$clientId&redirect_uri=$redirectUri&scope=$scope&response_type=code";
        header("Location: $url");
        exit;
    }

    public function facebookCallback() {
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

        $email = $user['email'];
        $name = $user['name'];
        $avatar = $user['picture']['data']['url'] ?? null;
        $user = $this->userService->getUserByEmail($email);
        $currentUrl = $_SERVER['REQUEST_URI'];
        if($user && $user->getAuthProvider() !== 'facebook') {
            if($currentUrl === "/auth/login") {
                $_SESSION['login_error'] = 'Email already exists. Please use a different email or login.';
                header("Location: /auth/login");
                
            } else {
                $_SESSION['register_error'] = 'Email already exists. Please use a different email or login.';
                header("Location: /auth/register");
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

        header("Location: /");
        exit;
    }

    public function showForgotPasswordForm() {
        require_once __DIR__ . '/../views/auth/forgot-password.php';
        exit;
    }
   
    public function sendPasswordResetOTP() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $user = $this->userService->getUserByEmail($email);
            $currentUrl = $_SERVER['REQUEST_URI'];
            if($user) {
                if($this->userService->getAuthProvider($email) === 'google') {
                    $error = 'This email is registered with Google. We cannot reset your password with Google.';
                    if($currentUrl === "/auth/login/forgot-password/resetExpired") {
                        $_SESSION['error-expired'] = $error;
                        header("Location: /auth/login/forgot-password/resetExpired");
                    } else{
                        $_SESSION['error-message'] = $error;
                        header("Location: /auth/login/forgot-password");
                    }
                    exit;
                }
                if($this->userService->getAuthProvider($email) === 'facebook') {
                    $error = 'This email is registered with Facebook. We cannot reset your password with Facebook.';
                    
                    if($currentUrl === "/auth/login/forgot-password/resetExpired") {
                        $_SESSION['error-expired'] = $error;
                        header("Location: /auth/login/forgot-password/resetExpired");
                    } else{
                        $_SESSION['error-message'] = $error;
                        header("Location: /auth/login/forgot-password");
                    }
                    exit;
                }
                $this->userService->generateAndSendOTP($email);
                header("Location: /auth/login/forgot-password/input-otp");
                exit;
            } else {
                $error = 'No email found for that account.';
                if($currentUrl === "/auth/login/forgot-password/resetExpired") {
                    $_SESSION['error-expired'] = $error;
                    header("Location: /auth/login/forgot-password/resetExpired");
                } else{
                    $_SESSION['error-message'] = $error;
                    header("Location: /auth/login/forgot-password");
                }
                exit;
            }
        }
    }
    public function showVerifyOTPForm() {
        $email = $_SESSION['otp-email'] ?? null;
        if (!$email) {
            header("Location: /auth/login/forgot-password/resetExpired");
            exit;
        }
        require_once __DIR__ . '/../views/auth/inputOtp.php';
        exit;
    }
    public function verifyPasswordResetOTP() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['otp-email'])) {
                header("Location: /auth/login/forgot-password/resetExpired");
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
                header("Location: /auth/login/forgot-password/reset-password-form");
                exit;
            } else {
                $_SESSION['error-OTP'] = 'Invalid OTP. Please try again.';
                header("Location: /auth/login/forgot-password/input-otp");
                exit;
            }
            header("Location: /auth/login/forgot-password/reset-password-form");
            exit;
        }
    }
    
    public function showResetPasswordForm() {
        if(!$_SESSION['reset-email']) {
            header("Location: /auth/login/forgot-password/resetExpired");
            exit;
        }
        $expiry = $_SESSION['reset-expire'] ?? '';
        if(time() > $expiry) {
            unset($_SESSION['reset-email']);
            unset($_SESSION['reset-expire']);
            header("Location: /auth/login/forgot-password/resetExpired");
            exit;
        }
        require_once __DIR__ . '/../views/auth/resetPassword.php';
        exit;
    }

    public function resetPassword() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_SESSION['reset-email'] ?? '';
            $expiry = $_SESSION['reset-expire'] ?? '';
            if(time() > $expiry) {
                unset($_SESSION['reset-email']);
                unset($_SESSION['reset-expire']);
                header("Location: /auth/login/forgot-password/resetExpired");
                exit;
            }
            if(!$email) {
                header("Location: /auth/login/forgot-password/resetExpired");
                exit;
            }
            $newPassword = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            if($newPassword !== $confirmPassword) {
                $_SESSION['error-reset'] = 'Your confirmed password do not match.';
                header("Location: /auth/login/forgot-password/reset-password-form");
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
                    header("Location: /auth/login/forgot-password/reset-password-form");
                    exit;
                }
            }
            error_log("Resetting password for email: " . $email);
            if(!$this->userService->updatePasswordByEmail($email, $newPassword)) {
                $_SESSION['error-reset'] = 'Something went wrong. Please try again.';
                header("Location: /auth/login/forgot-password/reset-password-form");
                exit;
            };
            // Destroy session
            unset($_SESSION['reset-email']);
            unset($_SESSION['reset-expire']);
            header("Location: /auth/login");
            exit;
        }
    }

    public function showExpiredTokenOrOTPPage() {
        require_once __DIR__ . '/../views/auth/reset-expired.php';
        exit;
    }

    public function checkEmail(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            header('Content-Type: application/json');
            if ($this->userService->getUserByEmail($email)) {
                if($this->userService->getAuthProvider($email) === 'google') {
                    echo json_encode([
                        'exists' => true,
                        'message' => 'Email already exists with Google. Please login with Google.'
                    ]);
                    exit;
                } elseif($this->userService->getAuthProvider($email) === 'facebook') {
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
