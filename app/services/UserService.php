<?php
require_once __DIR__ . '/../dao/UserDAO.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class UserService {
    private $userDAO;

    public function __construct() {
        $this->userDAO = new UserDAO();
    }

    public function getAllUsers($search, $roleFilter, $per_page, $offset) {
        return $this->userDAO->getAll($search, $roleFilter, $per_page, $offset);
    }

    public function getTotalCount($search, $roleFilter) {
        return $this->userDAO->getTotalCount($search, $roleFilter);
    }

    public function getUserById($id) {
        return $this->userDAO->getById($id);
    }

    public function getUserByEmail($email) {
        return $this->userDAO->getByEmail($email);
    }

    public function getAuthProvider($email) {
        return $this->userDAO->getAuthProvider($email);
    }

    public function createUserGoogle($email, $name, $avatar = null) {
        return $this->userDAO->createUserGoogle($email, $name, $avatar);
    }

    public function createUserFacebook($email, $name, $avatar = null) {
        return $this->userDAO->createUserFacebook($email, $name, $avatar);
    }

    // get facebook access token
    public function getFacebookAccessToken($code) {
        $url = "https://graph.facebook.com/oauth/access_token?" . http_build_query([
            "client_id" => $_ENV['FACEBOOK_CLIENT_ID'],
            "redirect_uri" => $_ENV['FACEBOOK_REDIRECT_URI'],
            "client_secret" => $_ENV['FACEBOOK_CLIENT_SECRET'],
            "code" => $code
        ]);

        $response = json_decode(file_get_contents($url), true);
        return $response['access_token'];
    }

    // get facebook user
    public function getFacebookUser($accessToken) {
        $url = "https://graph.facebook.com/me?fields=id,name,email,picture&access_token=" . $accessToken;
        return json_decode(file_get_contents($url), true);
    }

    public function verifyPasswordLogin($email, $password) {
        $hashedPassword = $this->userDAO->getHashedPassword($email);
        if($hashedPassword){
            // Check if it's a bcrypt hash (starts with $2y$)
            if (strpos($hashedPassword, '$2y$') === 0 || strpos($hashedPassword, '$2a$') === 0) {
                // It's hashed, use password_verify
                return password_verify($password, $hashedPassword);
            } else {
                // For testing: plain text comparison (REMOVE IN PRODUCTION!)
                return $hashedPassword === $password;
            }
        }
        return false;
    }

    public function verifyOTP($otp) {
        if(isset($_SESSION['otp']) && isset($_SESSION['otp-email']) 
            && isset($_SESSION['otp-expire'])
            && time() < $_SESSION['otp-expire']
            && strval($_SESSION['otp']) === strval($otp)) {

            $_SESSION['reset-email'] = $_SESSION['otp-email'];
            $_SESSION['reset-expire'] =  time() + 900; // 15 minutes from now
            unset($_SESSION['otp']);
            unset($_SESSION['otp-email']);
            unset($_SESSION['otp-expire']);

            return true;
        }
        return false;
    }

    public function updatePasswordByEmail($email, $password) {
        return $this->userDAO->updatePasswordByEmail($email, $password);
    }

    // Register user (employer and local only)
    public function registerUser($data) {
        // Check email
        if ($this->userDAO->emailExists($data['email'])) {
            throw new Exception("Email already exists.");
        }

        $user = new User(
            null,
            $data['fullname'],
            $data['email'],
            'Employer',
            $data['password'],
            null,
            1,
            'local'
        );

        return $this->userDAO->create($user);
    }

    public function getLocalUserByEmail($email) { 
        return $this->userDAO->getLocalUserByEmail($email);
    }

    public function generateAndSendOTP($email) {
        if(isset($_SESSION['otp-email'])) { // Check if OTP already generated, unset it
            unset($_SESSION['otp']);
            unset($_SESSION['otp-email']);
            unset($_SESSION['otp-expire']);
        }
        #generate 5 digit OTP
        $otp = random_int(10000, 99999);

        //Store OTP and expiry in session for 15 minutes
        $_SESSION['otp'] = $otp;
        $_SESSION['otp-email'] = $email;
        $_SESSION['otp-expire'] = time() + 900; // 15 minutes from now

        // Load email template and replace placeholders
        $template = file_get_contents(__DIR__ . '/../../public/resources/OTP.html');
        $message = str_replace('{{otp}}', $otp, $template);
        $message = str_replace('{{name}}', $this->userDAO->getByEmail($email)->getName(), $message);

        
        //send email using PHPMailer
        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->isSMTP(); // Send using SMTP
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['SMTP_EMAIL'];
            $mail->Password   = $_ENV['SMTP_PASSWORD'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $_ENV['SMTP_PORT'];

            //Recipients
            $mail->setFrom('no-reply@worknest.com', 'WorkNest Support');
            $mail->addAddress($email);

            //Content
            $mail->isHTML(true);
            $mail->Subject = '[WorkNest] Your Password Reset OTP';
            $mail->Body    = $message;

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
            return false;
        }
    }
    public function createUser($data, $currentUserId = null) {
        // Validate that only Admin or Staff roles can be created
        if (!in_array($data['role'], ['Admin', 'Staff'])) {
            throw new Exception("Invalid role. Only Admin and Staff can be created.");
        }

        // Check if email already exists
        if ($this->userDAO->emailExists($data['email'])) {
            throw new Exception("Email already exists.");
        }

        // Validate password is provided
        if (empty($data['password'])) {
            throw new Exception("Password is required.");
        }

        $user = new User(
            null, 
            $data['username'], 
            $data['email'], 
            $data['role'], 
            $data['password'],
            null, // avatar
            1 // is_active
        );
        return $this->userDAO->create($user);
    }

    public function updateUser($id, $data, $currentUserId = null) {
        $user = $this->userDAO->getById($id);
        if (!$user) {
            throw new Exception("User not found.");
        }

        // Prevent admin from editing other admins (unless editing themselves)
        if ($user->getRole() === 'Admin' && $id != $currentUserId) {
            throw new Exception("You cannot edit other administrators.");
        }

        // Validate role
        if (!in_array($data['role'], ['Admin', 'Staff'])) {
            throw new Exception("Invalid role. Only Admin and Staff are allowed.");
        }

        // Check if email already exists (excluding current user)
        if ($this->userDAO->emailExists($data['email'], $id)) {
            throw new Exception("Email already exists.");
        }

        $user->setUsername($data['username']);
        $user->setEmail($data['email']);
        $user->setRole($data['role']);

        return $this->userDAO->update($user);
    }

    public function deleteUser($id, $currentUserId = null) {
        $user = $this->userDAO->getById($id);
        if (!$user) {
            throw new Exception("User not found.");
        }

        // Prevent admin from deleting themselves
        if ($id == $currentUserId) {
            throw new Exception("You cannot delete yourself.");
        }

        // Allow admins to delete other admins or staff
        return $this->userDAO->delete($id);
    }

    public function canEdit($userId, $currentUserId) {
        $user = $this->userDAO->getById($userId);
        if (!$user) {
            return false;
        }

        // Can edit if it's the same user OR if the target is not an admin
        return ($userId == $currentUserId) || ($user->getRole() !== 'Admin');
    }

    public function updateProfile($userId, $data) {
        // Validate name
        if (empty(trim($data['name']))) {
            throw new Exception("Name is required.");
        }

        // Validate email
        if (empty(trim($data['email'])) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Valid email is required.");
        }

        // Check if email already exists (excluding current user)
        if ($this->userDAO->emailExists($data['email'], $userId)) {
            throw new Exception("Email already exists.");
        }

        // Handle avatar if provided
        $avatar = $data['avatar'] ?? null;
        
        return $this->userDAO->updateProfile($userId, $data['name'], $data['email'], $avatar);
    }

    public function updatePassword($userId, $oldPassword, $newPassword, $confirmPassword) {
        // Verify old password
        if (!$this->userDAO->verifyPassword($userId, $oldPassword)) {
            throw new Exception("Current password is incorrect.");
        }

        // Validate new password
        if (strlen($newPassword) < 6) {
            throw new Exception("New password must be at least 6 characters long.");
        }

        // Check if passwords match
        if ($newPassword !== $confirmPassword) {
            throw new Exception("New passwords do not match.");
        }

        return $this->userDAO->updatePassword($userId, $newPassword);
    }

    public function verifyPassword($userId, $password) {
        return $this->userDAO->verifyPassword($userId, $password);
    }
}
