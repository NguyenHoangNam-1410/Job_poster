<?php
require_once __DIR__ . '/../dao/JobDAO.php';
require_once __DIR__ . '/../services/JobApplicationService.php';

class PublicJobController
{
  public function show(int $id)
  {
    $dao = new JobDAO();
    $m = $dao->getById($id);
    if (!$m) {
      http_response_code(404);
      $job = null;
      require __DIR__ . '/../views/public/jobs/show.php';
      return;
    }

    $cats = [];
    if (method_exists($m, 'getCategories')) {
      foreach ($m->getCategories() as $c)
        $cats[] = $c['name'] ?? ($c['category_name'] ?? '');
    }

    $job = [
      'id' => $id,
      'title' => $m->getTitle(),
      'company_name' => $m->getCompanyName() ?: $m->getEmployerName(),
      'location' => $m->getLocation(),
      'description' => $m->getDescription(),
      'requirements' => $m->getRequirements(),
      'salary' => $m->getSalary(),
      'deadline' => $m->getDeadline(),
      'status' => $m->getStatus(),
      'created_at' => $m->getCreatedAt(),
      'categories' => $cats,
      'logo' => $m->getEmployerLogo() ?: '',
      'website' => ''
    ];

    require __DIR__ . '/../views/public/jobs/show.php';
  }

  public function index()
  {
    require __DIR__ . '/../views/public/jobs/jobslisting.php';
  }

  public function apply()
  {
    header('Content-Type: application/json');

    try {
      $jobId = (int) ($_POST['job_id'] ?? 0);

      // Get user info from POST, or from session if logged in
      $fullName = trim($_POST['full_name'] ?? '');
      $email = trim($_POST['email'] ?? '');

      // If logged in, prefer session data but allow override
      if (isset($_SESSION['user'])) {
        if (empty($fullName)) {
          $fullName = $_SESSION['user']['name'] ?? '';
        }
        if (empty($email)) {
          $email = $_SESSION['user']['email'] ?? '';
        }
      }

      $phone = trim($_POST['phone'] ?? '');
      $coverLetter = trim($_POST['cover_letter'] ?? '');

      // Validation
      if ($jobId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid job ID']);
        return;
      }

      if (empty($fullName) || empty($email) || empty($coverLetter)) {
        echo json_encode(['success' => false, 'message' => 'Please fill in all required fields']);
        return;
      }

      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email address']);
        return;
      }

      // Validate CV file
      if (!isset($_FILES['cv']) || $_FILES['cv']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'message' => 'Please upload your CV']);
        return;
      }

      $cvFile = $_FILES['cv'];
      if ($cvFile['type'] !== 'application/pdf') {
        echo json_encode(['success' => false, 'message' => 'CV must be in PDF format']);
        return;
      }

      if ($cvFile['size'] > 100 * 1024 * 1024) {
        echo json_encode(['success' => false, 'message' => 'CV file size must be less than 100MB']);
        return;
      }

      // Get employer email
      $dao = new JobDAO();
      $employerEmail = $dao->getEmployerEmailByJobId($jobId);

      if (empty($employerEmail)) {
        echo json_encode(['success' => false, 'message' => 'Employer email not found']);
        return;
      }

      // Get job details
      $job = $dao->getById($jobId);
      if (!$job) {
        echo json_encode(['success' => false, 'message' => 'Job not found']);
        return;
      }

      // Send email using service
      $service = new JobApplicationService();
      $result = $service->sendApplication($jobId, $job->getTitle(), $job->getCompanyName(), $employerEmail, $fullName, $email, $phone, $coverLetter, $cvFile);

      if ($result) {
        echo json_encode(['success' => true, 'message' => 'Application sent successfully']);
      } else {
        // Check email method
        $emailMethod = $_ENV['EMAIL_METHOD'] ?? 'php_mail';

        if ($emailMethod === 'php_mail') {
          error_log("Apply error: Failed to send email using PHP mail(). Check server mail configuration.");
          echo json_encode(['success' => false, 'message' => 'Failed to send application. Please check server mail configuration or contact administrator.']);
        } elseif ($emailMethod === 'sendgrid' || $emailMethod === 'smtp') {
          // Check if SMTP is configured
          $smtpEmail = $_ENV['SMTP_EMAIL'] ?? null;
          $smtpPassword = $_ENV['SMTP_PASSWORD'] ?? null;

          if (empty($smtpEmail) || empty($smtpPassword)) {
            error_log("Apply error: SMTP not configured. Please configure SMTP_EMAIL, SMTP_PASSWORD, and SMTP_HOST in .env file");
            echo json_encode(['success' => false, 'message' => 'Email service is not configured. Please configure SMTP settings in .env file.']);
          } else {
            error_log("Apply error: Failed to send email. Check SMTP settings and server logs.");
            echo json_encode(['success' => false, 'message' => 'Failed to send application. Please check your email settings or try again later.']);
          }
        } else {
          error_log("Apply error: Unknown email method: {$emailMethod}");
          echo json_encode(['success' => false, 'message' => 'Email service configuration error. Please contact administrator.']);
        }
      }

    } catch (Exception $e) {
      error_log("Apply error: " . $e->getMessage());
      error_log("Stack trace: " . $e->getTraceAsString());
      http_response_code(500);
      echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
    }
  }
}
