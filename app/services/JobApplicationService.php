<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class JobApplicationService {
    
    public function sendApplication($jobId, $jobTitle, $companyName, $employerEmail, $applicantName, $applicantEmail, $phone, $coverLetter, $cvFile) {
        try {
            $mail = new PHPMailer(true);
            
            // Set UTF-8 encoding for proper character support
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';
            
            // Check email sending method from env
            $emailMethod = $_ENV['EMAIL_METHOD'] ?? 'php_mail'; // Options: 'php_mail', 'smtp', 'sendgrid'
            
            if ($emailMethod === 'sendgrid') {
                // SendGrid method
                $smtpEmail = $_ENV['SMTP_EMAIL'] ?? 'apikey';
                $smtpPassword = $_ENV['SMTP_PASSWORD'] ?? null;
                $smtpHost = $_ENV['SMTP_HOST'] ?? 'smtp.sendgrid.net';
                $smtpPort = $_ENV['SMTP_PORT'] ?? '587';
                
                if (!empty($smtpPassword)) {
                    $mail->isSMTP();
                    $mail->Host       = $smtpHost;
                    $mail->SMTPAuth   = true;
                    $mail->Username   = $smtpEmail;
                    $mail->Password   = $smtpPassword;
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port       = (int)$smtpPort;
                    $mail->Timeout    = 10;
                    
                    // SendGrid allows sending from any email
                    $fromName = mb_encode_mimeheader($applicantName, 'UTF-8', 'Q');
                    $mail->setFrom($applicantEmail, $fromName);
                } else {
                    throw new Exception("SendGrid API key not configured");
                }
            } elseif ($emailMethod === 'smtp') {
                // Direct SMTP (Gmail, etc.)
                $smtpEmail = $_ENV['SMTP_EMAIL'] ?? null;
                $smtpPassword = $_ENV['SMTP_PASSWORD'] ?? null;
                $smtpHost = $_ENV['SMTP_HOST'] ?? 'smtp.gmail.com';
                $smtpPort = $_ENV['SMTP_PORT'] ?? '587';
                
                if (!empty($smtpEmail) && !empty($smtpPassword)) {
                    $mail->isSMTP();
                    $mail->Host       = $smtpHost;
                    $mail->SMTPAuth   = true;
                    $mail->Username   = $smtpEmail;
                    $mail->Password   = $smtpPassword;
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port       = (int)$smtpPort;
                    $mail->Timeout    = 10;
                    
                    // For direct SMTP, must send from authenticated email
                    // But set Reply-To to applicant's email
                    // Use mb_encode_mimeheader for proper UTF-8 encoding in email headers
                    $fromName = mb_encode_mimeheader($applicantName . ' (via Job Poster)', 'UTF-8', 'Q');
                    $mail->setFrom($smtpEmail, $fromName);
                } else {
                    throw new Exception("SMTP credentials not configured");
                }
            } else {
                // PHP mail() function - no SMTP needed
                $mail->isMail();
                // Try to set from applicant email (may not work on all servers)
                $fromName = mb_encode_mimeheader($applicantName, 'UTF-8', 'Q');
                $mail->setFrom($applicantEmail, $fromName);
            }
            
            // Send TO employer
            $toCompanyName = mb_encode_mimeheader($companyName, 'UTF-8', 'Q');
            $mail->addAddress($employerEmail, $toCompanyName);
            
            // BCC to applicant so they know it was sent
            $bccName = mb_encode_mimeheader($applicantName, 'UTF-8', 'Q');
            $mail->addBCC($applicantEmail, $bccName);
            
            // Reply-To is always applicant's email
            $replyName = mb_encode_mimeheader($applicantName, 'UTF-8', 'Q');
            $mail->addReplyTo($applicantEmail, $replyName);
            
            // Attach CV
            if (isset($cvFile['tmp_name']) && file_exists($cvFile['tmp_name'])) {
                $mail->addAttachment($cvFile['tmp_name'], $cvFile['name']);
            }
            
            // Content
            $mail->isHTML(true);
            
            // Subject format: [Source: Job Poster] Name - Application for Job Title - Company
            // Use mb_encode_mimeheader for proper UTF-8 encoding in subject
            $subjectText = "⏩ [Source: Job Poster] {$applicantName} - Application for {$jobTitle} - {$companyName}";
            $mail->Subject = mb_encode_mimeheader($subjectText, 'UTF-8', 'Q');
            
            // Email body
            $mail->Body = $this->buildEmailBody($jobTitle, $companyName, $applicantName, $applicantEmail, $phone, $coverLetter);
            $mail->AltBody = $this->buildPlainTextBody($jobTitle, $companyName, $applicantName, $applicantEmail, $phone, $coverLetter);
            
            $mail->send();
            return true;
            
        } catch (Exception $e) {
            $errorInfo = isset($mail) ? $mail->ErrorInfo : $e->getMessage();
            error_log("Job Application Email Error: {$errorInfo}");
            error_log("Exception: " . $e->getMessage());
            return false;
        }
    }
    
    private function buildEmailBody($jobTitle, $companyName, $applicantName, $applicantEmail, $phone, $coverLetter) {
        $phoneText = !empty($phone) ? "<p><strong>Phone:</strong> {$phone}</p>" : "";
        
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #0688B4; color: white; padding: 20px; text-align: center; }
                .content { background-color: #f9f9f9; padding: 20px; }
                .info-box { background-color: white; padding: 15px; margin: 15px 0; border-left: 4px solid #0688B4; }
                .cover-letter { background-color: white; padding: 15px; margin: 15px 0; border: 1px solid #ddd; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>New Job Application</h2>
                </div>
                <div class='content'>
                    <div class='info-box'>
                        <h3>Job Position</h3>
                        <p><strong>{$jobTitle}</strong></p>
                        <p><strong>Company:</strong> {$companyName}</p>
                    </div>
                    
                    <div class='info-box'>
                        <h3>Applicant Information</h3>
                        <p><strong>Name:</strong> {$applicantName}</p>
                        <p><strong>Email:</strong> {$applicantEmail}</p>
                        {$phoneText}
                    </div>
                    
                    <div class='cover-letter'>
                        <h3>Cover Letter</h3>
                        <p>" . nl2br(htmlspecialchars($coverLetter)) . "</p>
                    </div>
                    
                    <p style='margin-top: 20px; padding-top: 20px; border-top: 1px solid #ddd; color: #666; font-size: 12px;'>
                        This application was submitted through Job Poster platform. The CV is attached to this email.
                    </p>
                </div>
            </div>
        </body>
        </html>
        ";
    }
    
    private function buildPlainTextBody($jobTitle, $companyName, $applicantName, $applicantEmail, $phone, $coverLetter) {
        $phoneText = !empty($phone) ? "Phone: {$phone}\n" : "";
        
        return "
New Job Application

Job Position: {$jobTitle}
Company: {$companyName}

Applicant Information:
Name: {$applicantName}
Email: {$applicantEmail}
{$phoneText}

Cover Letter:
{$coverLetter}

---
This application was submitted through Job Poster platform. The CV is attached to this email.
        ";
    }
}

