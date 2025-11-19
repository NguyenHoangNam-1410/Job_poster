<?php
require_once __DIR__ . '/../services/CompanyService.php';

class CompanyController {
    private $companyService;

    public function __construct() {
        $this->companyService = new CompanyService();
    }

    private function getCurrentEmployerId() {
        $employer = $this->companyService->getEmployerByUserId($_SESSION['user']['id']);
        return $employer ? $employer->getId() : null;
    }

    public function index() {
        // An employer is a company
        $company = $this->companyService->getEmployerByUserId($_SESSION['user']['id']);
        
        if (!$company) {
            // Template 
            $company = new Employer(
                null,
                null,
                null,
                null,
                null,
                null,
                null
            );
        }

        $error = $_GET['error'] ?? null;
        $success = $_GET['success'] ?? null;
        
        require_once __DIR__ . '/../views/employer/my_company/profile.php';
    }

    public function createCompanyProfile() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = [
                    'name' => trim($_POST['name'] ?? ''),
                    'contact' => trim($_POST['contact'] ?? ''),
                    'email' => trim($_POST['email'] ?? ''),
                    'phone' => trim($_POST['phone'] ?? ''),
                    'website' => trim($_POST['website'] ?? ''),
                    'description' => trim($_POST['description'] ?? ''),
                    'logo' => null
                ];
                // Handle logo upload
                if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                    $file = $_FILES['logo'];
                    if ($file['size'] > 1048576) throw new Exception("Logo image must be less than 1MB.");
                    $allowedTypes = ['image/jpeg','image/png','image/gif','image/webp'];
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $mimeType = finfo_file($finfo, $file['tmp_name']);
                    finfo_close($finfo);
                    if (!in_array($mimeType, $allowedTypes)) throw new Exception("Invalid image format.");

                    $uploadDir = __DIR__ . '/../../public/image/logo/' . $_SESSION['user']['id'] . '/';
                    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                    $filename = 'logo_' . time() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
                    $uploadPath = $uploadDir . $filename;
                    if (!move_uploaded_file($file['tmp_name'], $uploadPath)) throw new Exception("Failed to upload logo.");

                    $data['logo'] = '/Job_poster/public/image/logo/' . $_SESSION['user']['id'] . '/' . $filename;
                }
                $success = $this->companyService->createCompanyProfile($_SESSION['user']['id'], $data);

                if ($success) {
                    header('Location: /Job_poster/public/company-profile?success=' . urlencode('Company updated successfully'));
                    exit;         
                } else {
                    throw new Exception("Failed to create company profile.");
                }
            } catch (Exception $e) {
                $error = $e->getMessage();
                require_once __DIR__ . '/../views/employer/my_company/profile.php';
            }
        }
    }

    public function updateCompanyProfile(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $currentEmployer = $this->companyService->getEmployerByUserId($_SESSION['user']['id']);
            if (!$currentEmployer) {
                $currentEmployer = $this->createCompanyProfile();
            }
            try {
                $data = [
                    'name' => trim($_POST['name'] ?? ''),
                    'contact' => trim($_POST['contact'] ?? ''),
                    'email' => trim($_POST['email'] ?? ''),
                    'phone' => trim($_POST['phone'] ?? ''),
                    'website' => trim($_POST['website'] ?? ''),
                    'description' => trim($_POST['description'] ?? ''),
                    'logo' => $currentEmployer->getLogo(),
                ];
                // Handle logo upload
                if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                    $file = $_FILES['logo'];
                    if ($file['size'] > 1048576) throw new Exception("Logo image must be less than 1MB.");
                    $allowedTypes = ['image/jpeg','image/png','image/gif','image/webp'];
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $mimeType = finfo_file($finfo, $file['tmp_name']);
                    finfo_close($finfo);
                    if (!in_array($mimeType, $allowedTypes)) throw new Exception("Invalid image format.");

                    $uploadDir = __DIR__ . '/../../public/image/logo/' . $_SESSION['user']['id'] . '/';
                    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                    $filename = 'logo_' . time() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
                    $uploadPath = $uploadDir . $filename;
                    if (!move_uploaded_file($file['tmp_name'], $uploadPath)) throw new Exception("Failed to upload logo.");

                    $data['logo'] = '/Job_poster/public/image/logo/' . $_SESSION['user']['id'] . '/' . $filename;
                }
                
                $success = $this->companyService->updateCompanyProfile($currentEmployer, $data);
                if ($success) {
                    header('Location: /Job_poster/public/company-profile?success=' . urlencode('Company updated successfully'));
                    exit;
                } else {
                    throw new Exception("Failed to update company profile.");
                }
            } catch (Exception $e) {
                $error = $e->getMessage();
                $company = $this->companyService->getEmployerByUserId($_SESSION['user']['id']);
                require_once __DIR__ . '/../views/employer/my_company/profile.php';
            }
        }
    }
}