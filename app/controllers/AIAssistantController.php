<?php
require_once __DIR__ . '/../services/AIAssistantService.php';
require_once __DIR__ . '/../dao/JobDAO.php';

class AIAssistantController
{
    private $aiService;

    public function __construct()
    {
        $this->aiService = new AIAssistantService();
    }

    /**
     * Handle chat requests
     */
    public function chat()
    {
        header('Content-Type: application/json');
        
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $message = trim($input['message'] ?? '');
            $context = $input['context'] ?? [];

            if (empty($message)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Please enter your question.'
                ]);
                return;
            }

            $response = $this->aiService->handleChat($message, $context);
            echo json_encode($response);

        } catch (Exception $e) {
            error_log('AI Assistant Controller Error: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            http_response_code(500);
            
            // Check if error message contains useful info
            $errorMsg = $e->getMessage();
            if (strpos($errorMsg, 'API key') !== false) {
                $userMessage = 'API key is not configured. Please check your .env file.';
            } elseif (strpos($errorMsg, 'CURL') !== false || strpos($errorMsg, 'network') !== false) {
                $userMessage = 'Connection error. Please check your internet connection and try again.';
            } else {
                $userMessage = 'An error occurred. Please try again later or check the error log.';
            }
            
            echo json_encode([
                'success' => false,
                'message' => $userMessage
            ]);
        }
    }

    /**
     * Search jobs based on user query
     */
    public function searchJobs()
    {
        header('Content-Type: application/json');
        
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $query = trim($input['query'] ?? '');
            $limit = (int)($input['limit'] ?? 5);

            if (empty($query)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Please enter search keywords.'
                ]);
                return;
            }

            $response = $this->aiService->searchJobs($query, $limit);
            echo json_encode($response);

        } catch (Exception $e) {
            error_log('AI Job Search Controller Error: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            http_response_code(500);
            
            $errorMsg = $e->getMessage();
            if (strpos($errorMsg, 'API key') !== false) {
                $userMessage = 'API key is not configured. Please check your .env file and see OPENROUTER_AI_SETUP.md';
            } elseif (strpos($errorMsg, 'CURL') !== false) {
                $userMessage = 'Connection error to API. Please check your internet connection.';
            } else {
                $userMessage = 'An error occurred while searching. Details have been logged.';
            }
            
            echo json_encode([
                'success' => false,
                'message' => $userMessage
            ]);
        }
    }

    /**
     * Summarize job description
     */
    public function summarizeJob()
    {
        header('Content-Type: application/json');
        
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $jobId = (int)($input['job_id'] ?? 0);

            if ($jobId <= 0) {
                echo json_encode([
                    'success' => false,
                    'summary' => 'Invalid job ID.'
                ]);
                return;
            }

            $jobDAO = new JobDAO();
            $job = $jobDAO->getById($jobId);

            if (!$job) {
                echo json_encode([
                    'success' => false,
                    'summary' => 'Job not found.'
                ]);
                return;
            }

            $jobData = [
                'title' => $job->getTitle(),
                'company' => $job->getCompanyName() ?: $job->getEmployerName(),
                'location' => $job->getLocation(),
                'description' => $job->getDescription(),
                'requirements' => $job->getRequirements(),
                'salary' => $job->getSalary(),
                'deadline' => $job->getDeadline()
            ];

            $response = $this->aiService->summarizeJobDescription($jobData);
            echo json_encode($response);

        } catch (Exception $e) {
            error_log('AI Job Summary Controller Error: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'summary' => 'An error occurred while summarizing the job.'
            ]);
        }
    }

    /**
     * Answer questions about a job
     */
    public function answerJobQuestion()
    {
        header('Content-Type: application/json');
        
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $jobId = (int)($input['job_id'] ?? 0);
            $question = trim($input['question'] ?? '');

            if ($jobId <= 0) {
                echo json_encode([
                    'success' => false,
                    'answer' => 'Invalid job ID.'
                ]);
                return;
            }

            if (empty($question)) {
                echo json_encode([
                    'success' => false,
                    'answer' => 'Please enter your question.'
                ]);
                return;
            }

            $jobDAO = new JobDAO();
            $job = $jobDAO->getById($jobId);

            if (!$job) {
                echo json_encode([
                    'success' => false,
                    'answer' => 'Job not found.'
                ]);
                return;
            }

            $jobData = [
                'title' => $job->getTitle(),
                'company' => $job->getCompanyName() ?: $job->getEmployerName(),
                'location' => $job->getLocation(),
                'description' => $job->getDescription(),
                'requirements' => $job->getRequirements(),
                'salary' => $job->getSalary(),
                'deadline' => $job->getDeadline()
            ];

            $response = $this->aiService->answerJobQuestion($jobData, $question);
            echo json_encode($response);

        } catch (Exception $e) {
            error_log('AI Job Q&A Controller Error: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'answer' => 'An error occurred while answering the question.'
            ]);
        }
    }
}

