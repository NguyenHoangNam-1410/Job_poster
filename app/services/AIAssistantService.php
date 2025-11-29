<?php
require_once __DIR__ . '/../dao/JobDAO.php';

class AIAssistantService
{
    private $apiKey;
    private $apiUrl = 'https://openrouter.ai/api/v1/chat/completions';
    private $model = 'mistralai/mistral-7b-instruct:free'; // Free model via OpenRouter
    private $jobDAO;

    public function __construct()
    {
        // Support both GEMINI_API_KEY and OPENROUTER_API_KEY for backward compatibility
        $this->apiKey = $_ENV['OPENROUTER_API_KEY'] ?? $_ENV['GEMINI_API_KEY'] ?? '';
        $this->jobDAO = new JobDAO();
        
        // Allow using OpenRouter without API key for free tier models
        if (empty($this->apiKey)) {
            error_log('OPENROUTER_API_KEY not set - will use free tier (limited)');
        }
    }

    /**
     * Call AI API via OpenRouter (supports Mistral and other models)
     */
    private function callGeminiAPI($prompt, $maxTokens = 2048)
    {
        $url = $this->apiUrl;
        
        // OpenRouter format (OpenAI-compatible)
        $data = [
            'model' => $this->model,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
            'max_tokens' => $maxTokens,
            'temperature' => 0.7
        ];
        
        $headers = [
            'Content-Type: application/json',
            'HTTP-Referer: ' . ($_SERVER['HTTP_REFERER'] ?? 'https://worknest.local'),
            'X-Title:Worknest AI Assistant'
        ];
        
        // Add API key if available (optional for free tier)
        if (!empty($this->apiKey)) {
            $headers[] = 'Authorization: Bearer ' . $this->apiKey;
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); // 30 second timeout

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        
        if (curl_errno($ch)) {
            curl_close($ch);
            $errorMsg = 'CURL Error: ' . $curlError . ' (Code: ' . curl_errno($ch) . ')';
            error_log('OpenRouter API CURL Error: ' . $errorMsg);
            throw new Exception($errorMsg);
        }
        
        curl_close($ch);

        if ($httpCode !== 200) {
            $errorMsg = 'OpenRouter API returned HTTP ' . $httpCode;
            error_log('OpenRouter API HTTP Error: ' . $errorMsg);
            error_log('Response: ' . substr($response, 0, 500));
            
            // Try to parse error message from response
            $errorData = json_decode($response, true);
            if (isset($errorData['error']['message'])) {
                $errorMsg .= ': ' . $errorData['error']['message'];
            }
            
            throw new Exception($errorMsg);
        }

        $result = json_decode($response, true);
        
        if (!$result) {
            error_log('OpenRouter API: Failed to parse JSON response');
            error_log('Response: ' . substr($response, 0, 500));
            throw new Exception('Unable to parse response from API. Please try again.');
        }
        
        // Check for error in response
        if (isset($result['error'])) {
            $errorMsg = $result['error']['message'] ?? 'Unknown error from API';
            error_log('OpenRouter API Error in response: ' . $errorMsg);
            throw new Exception('API Error: ' . $errorMsg);
        }
        
        // OpenRouter returns OpenAI-compatible format
        if (!isset($result['choices']) || empty($result['choices'])) {
            error_log('OpenRouter API: No choices in response');
            error_log('Response structure: ' . json_encode($result));
            throw new Exception('Không nhận được phản hồi hợp lệ từ API.');
        }
        
        // Extract text from response
        if (!isset($result['choices'][0]['message']['content'])) {
            error_log('OpenRouter API: Invalid response structure');
            error_log('Response: ' . json_encode($result));
            throw new Exception('Cấu trúc phản hồi không hợp lệ từ API.');
        }

        return $result['choices'][0]['message']['content'];
    }

    /**
     * Extract location from user query using pattern matching
     */
    private function extractLocationFromQuery($query)
    {
        // Common Vietnamese cities and provinces (lowercase keys for case-insensitive matching)
        $locations = [
            'ho chi minh' => 'Ho Chi Minh',
            'hcm' => 'Ho Chi Minh',
            'sai gon' => 'Ho Chi Minh',
            'ha noi' => 'Ha Noi',
            'hanoi' => 'Ha Noi',
            'da nang' => 'Da Nang',
            'hai phong' => 'Hai Phong',
            'can tho' => 'Can Tho',
            'long an' => 'Long An',
            'dong nai' => 'Dong Nai',
            'binh duong' => 'Binh Duong',
            'ba ria' => 'Ba Ria',
            'tay ninh' => 'Tay Ninh',
            'kien giang' => 'Kien Giang',
            'an giang' => 'An Giang',
            'quang nam' => 'Quang Nam',
            'quang ngai' => 'Quang Ngai',
            'binh dinh' => 'Binh Dinh',
            'phu yen' => 'Phu Yen',
            'khanh hoa' => 'Khanh Hoa',
            'ninh thuan' => 'Ninh Thuan',
            'lam dong' => 'Lam Dong',
            'nghe an' => 'Nghe An',
            'ha tinh' => 'Ha Tinh',
            'thai binh' => 'Thai Binh',
            'thai nguyen' => 'Thai Nguyen',
            'bac kan' => 'Bac Kan',
            'cao bang' => 'Cao Bang',
            'dien bien' => 'Dien Bien',
            'lai chau' => 'Lai Chau',
            'son la' => 'Son La',
            'yen bai' => 'Yen Bai',
            'hoa binh' => 'Hoa Binh',
            'vinh phuc' => 'Vinh Phuc',
            'bac giang' => 'Bac Giang',
            'bac ninh' => 'Bac Ninh',
            'hung yen' => 'Hung Yen',
            'ha nam' => 'Ha Nam',
            'nam dinh' => 'Nam Dinh'
        ];
        
        // Convert query to lowercase for matching
        $queryLower = strtolower($query);
        
        // Try to find location in query (case-insensitive)
        foreach ($locations as $pattern => $locationName) {
            if (preg_match('/\b' . preg_quote($pattern, '/') . '\b/', $queryLower)) {
                return $locationName;
            }
        }
        
        return '';
    }
    public function searchJobs($userQuery, $limit = 5)
    {
        try {
            // Check if query is about salary
            $isSalaryQuery = preg_match('/(highest|highest.*salary|best.*salary|max.*salary|most.*salary|top.*salary|salary.*high|salary.*most|salary.*best|lương cao|lương cao nhất|cao nhất|lương tối đa)/i', $userQuery);
            $isLowestSalaryQuery = preg_match('/(lowest|lowest.*salary|min.*salary|minimum.*salary|lương thấp|lương tối thiểu)/i', $userQuery);
            
            // Fallback: Use direct search if API fails
            $searchQuery = $userQuery;
            $locationFilter = '';
            
            // Clean user query: remove common Vietnamese words
            $cleanedQuery = preg_replace('/\b(tìm|kiếm|job|việc|tuyển|có|về|với|cho|ở|tại|highest|lowest|salary|lương)\b/iu', '', $userQuery);
            $cleanedQuery = trim(preg_replace('/\s+/', ' ', $cleanedQuery));
            
            // Use cleaned query as base search
            $searchQuery = !empty($cleanedQuery) ? $cleanedQuery : '';
            $locationFilter = '';
            
            // Try to extract keywords using AI (optional - if fails, use cleaned query)
            // Skip AI extraction for salary queries - we'll fetch all jobs and sort by salary
            if (!$isSalaryQuery && !$isLowestSalaryQuery) {
                try {
                    $extractPrompt = "You are a job search analyzer. Extract search criteria from the user query. You can understand both English and Vietnamese.

Question: {$userQuery}

Return JSON only (no markdown, no code blocks):
{
  \"search_keywords\": \"job title/field keywords or empty string if searching by location only\",
  \"location\": \"location/city name or null\"
}

Important rules:
- Extract location names (cities, provinces): 'at Long An' → location: 'Long An', 'Ha Noi' → location: 'Ha Noi'
- Extract job keywords: 'data science job' → search_keywords: 'data science'
- If query is ONLY about location or salary, leave search_keywords empty
- Return location WITHOUT 'at', 'in', 'near' prefixes

Examples:
'tìm job data science' → {\"search_keywords\": \"data science\", \"location\": null}
'I'm at Long An, what job?' → {\"search_keywords\": \"\", \"location\": \"Long An\"}
'developer job in Ha Noi' → {\"search_keywords\": \"developer\", \"location\": \"Ha Noi\"}
'highest salary jobs' → {\"search_keywords\": \"\", \"location\": null}";

                    $extraction = $this->callGeminiAPI($extractPrompt, 256);
                    
                    // Clean JSON response
                    $extraction = preg_replace('/```json\s*/', '', $extraction);
                    $extraction = preg_replace('/```\s*/', '', $extraction);
                    $extraction = preg_replace('/^```\s*/', '', $extraction);
                    $extraction = preg_replace('/```\s*$/', '', $extraction);
                    $extraction = trim($extraction);
                    
                    // Try to extract JSON
                    if (preg_match('/\{.*\}/s', $extraction, $matches)) {
                        $extraction = $matches[0];
                    }
                    
                    $criteria = json_decode($extraction, true);
                    if ($criteria && isset($criteria['search_keywords'])) {
                        $searchQuery = trim($criteria['search_keywords']);
                        $locationFilter = $criteria['location'] ? trim($criteria['location']) : '';
                        error_log("AI extraction result - keywords: '{$searchQuery}', location: '{$locationFilter}'");
                    }
                } catch (Exception $e) {
                    // If AI extraction fails, use cleaned query directly
                    error_log('AI extraction failed: ' . $e->getMessage());
                    // Try basic location extraction from user query
                    $locationFilter = $this->extractLocationFromQuery($userQuery);
                }
            } else {
                // For salary queries, still try to extract location if present
                $locationFilter = $this->extractLocationFromQuery($userQuery);
            }

            // Log search query for debugging
            error_log("AI Job Search - Original query: {$userQuery}, Search query: '{$searchQuery}', Location: '{$locationFilter}', Is salary query: " . ($isSalaryQuery ? 'yes' : 'no'));
            
            // If salary query, fetch ALL jobs (empty search) to sort by salary
            if ($isSalaryQuery || $isLowestSalaryQuery) {
                $searchQuery = ''; // Don't search for specific keywords, get all jobs
                
                // Search jobs using JobDAO - get ALL recruiting jobs
                $result = $this->jobDAO->searchPublic(
                    '', // empty search - get all jobs
                    [], // categories
                    $locationFilter ? [$locationFilter] : [], // locations
                    ['recruiting'], // only show recruiting jobs
                    1, // page
                    $limit * 3 // get more jobs for sorting
                );
                
                error_log("AI Job Search - Found " . count($result['rows'] ?? []) . " total jobs" . ($locationFilter ? " in {$locationFilter}" : ""));

                $jobs = $result['rows'] ?? [];
                
                if ($isLowestSalaryQuery) {
                    // Sort by salary ascending (lowest first)
                    usort($jobs, function($a, $b) {
                        $salaryA = (float)($a['salary'] ?? 0);
                        $salaryB = (float)($b['salary'] ?? 0);
                        return $salaryA <=> $salaryB;
                    });
                    $searchQuery = 'lowest salary';
                } else {
                    // Sort by salary descending (highest first)
                    usort($jobs, function($a, $b) {
                        $salaryA = (float)($a['salary'] ?? 0);
                        $salaryB = (float)($b['salary'] ?? 0);
                        return $salaryB <=> $salaryA;
                    });
                    $searchQuery = 'highest salary';
                }
                
                // Filter out jobs with no salary
                $jobs = array_filter($jobs, function($job) {
                    return !empty($job['salary']) && (float)$job['salary'] > 0;
                });
                $jobs = array_values($jobs); // re-index array
                
                error_log("AI Job Search - After salary sort: " . count($jobs) . " jobs with salary data");
            } else {
                // Normal keyword search
                // Search jobs using JobDAO
                $result = $this->jobDAO->searchPublic(
                    $searchQuery,
                    [], // categories
                    $locationFilter ? [$locationFilter] : [], // locations
                    ['recruiting'], // only show recruiting jobs
                    1, // page
                    $limit * 2 // get more jobs for filtering
                );
                
                error_log("AI Job Search - Found " . count($result['rows'] ?? []) . " jobs" . ($locationFilter ? " in {$locationFilter}" : "") . ($searchQuery ? " matching '{$searchQuery}'" : ""));

                $jobs = $result['rows'] ?? [];
            }
            
            // If no jobs found, try searching with individual words
            if (empty($jobs) && str_word_count($searchQuery) > 1 && !$isSalaryQuery && !$isLowestSalaryQuery) {
                $words = explode(' ', $searchQuery);
                // Try searching with first significant word
                foreach ($words as $word) {
                    $word = trim($word);
                    if (strlen($word) > 2) { // Skip short words
                        $wordResult = $this->jobDAO->searchPublic(
                            $word,
                            [],
                            $locationFilter ? [$locationFilter] : [],
                            ['recruiting'],
                            1,
                            $limit * 2
                        );
                        if (!empty($wordResult['rows'])) {
                            $jobs = $wordResult['rows'];
                            $searchQuery = $word; // Update search query
                            break;
                        }
                    }
                }
            }
            
            // If still no jobs, show all recent jobs with helpful message
            if (empty($jobs)) {
                // Try to get any recent jobs to show
                $allJobsResult = $this->jobDAO->searchPublic(
                    '',
                    [],
                    [],
                    ['recruiting'],
                    1,
                    5
                );
                $allJobs = $allJobsResult['rows'] ?? [];
                
                // Different message for different query types
                if ($isSalaryQuery || $isLowestSalaryQuery) {
                    $message = '😔 Sorry, there are no jobs with ' . htmlspecialchars($searchQuery) . ' available right now.\n\n';
                    $message .= 'This could mean:\n';
                    $message .= '• No jobs have salary information entered\n';
                    $message .= '• All available jobs have lower salary ranges\n\n';
                } elseif (!empty($locationFilter)) {
                    $message = '🔍 Sorry, no jobs found in ' . htmlspecialchars($locationFilter);
                    if (!empty($searchQuery)) {
                        $message .= ' matching "' . htmlspecialchars($searchQuery) . '"';
                    }
                    $message .= ".\n\n";
                    $message .= 'This could mean:\n';
                    $message .= '• No companies are hiring in this location right now\n';
                    $message .= '• The keywords don\'t match available positions\n\n';
                } else {
                    $message = '🔍 I searched but couldn\'t find any jobs matching "' . htmlspecialchars($userQuery) . '".\n\n';
                    $message .= 'There might not be any jobs in this field currently, or the keywords don\'t match available positions.';
                }
                
                if (!empty($allJobs)) {
                    $message .= '\n\n💡 Suggestions:\n';
                    if ($isSalaryQuery || $isLowestSalaryQuery) {
                        $message .= '• Browse all available jobs - some may have salary info in their description\n';
                    } elseif (!empty($locationFilter)) {
                        $message .= '• Try searching without location filter\n';
                        $message .= '• Try searching with different keywords\n';
                    } else {
                        $message .= '• Try searching with different keywords (e.g., "IT", "developer", "marketing", "sales")\n';
                    }
                    $message .= '• Here are some of the latest jobs for your reference\n';
                    $message .= '• Or use filters on the Jobs page to explore more';
                    
                    // Show recent jobs as suggestions
                    $recommendedJobs = [];
                    foreach (array_slice($allJobs, 0, 3) as $job) {
                        $recommendedJobs[] = [
                            'id' => $job['id'],
                            'title' => $job['title'],
                            'company' => $job['company'] ?? '',
                            'location' => $job['location'] ?? '',
                            'salary' => $job['salary'] ?? '',
                            'url' => '/Worknest/public/jobs/show/' . $job['id']
                        ];
                    }
                    
                    return [
                        'success' => true,
                        'message' => $message,
                        'jobs' => $recommendedJobs
                    ];
                }
                
                return [
                    'success' => true,
                    'message' => $message . '\n\n⚠️ There are currently no jobs available in the system. Please check back later!',
                    'jobs' => []
                ];
            }

            // Take top jobs (up to limit)
            $recommendedJobs = [];
            $maxJobs = min(count($jobs), $limit);
            
            for ($i = 0; $i < $maxJobs; $i++) {
                $job = $jobs[$i];
                $recommendedJobs[] = [
                    'id' => $job['id'],
                    'title' => $job['title'],
                    'company' => $job['company'] ?? '',
                    'location' => $job['location'] ?? '',
                    'salary' => $job['salary'] ?? '',
                    'url' => '/Worknest/public/jobs/show/' . $job['id']
                ];
            }

            // Generate friendly response with better formatting
            $responseMessage = "## 🔍 Search Results\n\n";
            $responseMessage .= "Found " . count($recommendedJobs) . " job(s) matching \"" . htmlspecialchars($searchQuery) . "\":\n\n";
            
            foreach ($recommendedJobs as $idx => $job) {
                $responseMessage .= "### " . ($idx + 1) . ". " . htmlspecialchars($job['title']) . "\n\n";
                $responseMessage .= "- Company: " . htmlspecialchars($job['company']) . "\n";
                $responseMessage .= "- Location: " . htmlspecialchars($job['location']) . "\n";
                if (!empty($job['salary']) && (float)$job['salary'] > 0) {
                    $responseMessage .= "- Salary: " . number_format((float)$job['salary'], 0, ',', '.') . " VND\n";
                }
                $responseMessage .= "\n";
            }
            
            $responseMessage .= "Click on any job above to view details!";

            return [
                'success' => true,
                'message' => $responseMessage,
                'jobs' => $recommendedJobs
            ];

        } catch (Exception $e) {
            error_log('AI Job Search Error: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            
            // Try fallback: direct search without AI
            try {
                $result = $this->jobDAO->searchPublic(
                    $userQuery,
                    [],
                    [],
                    ['recruiting'],
                    1,
                    $limit
                );
                
                $jobs = $result['rows'] ?? [];
                
                // Check if this is a salary query and sort accordingly
                if ($isSalaryQuery || $isLowestSalaryQuery) {
                    if ($isLowestSalaryQuery) {
                        usort($jobs, function($a, $b) {
                            $salaryA = (float)($a['salary'] ?? 0);
                            $salaryB = (float)($b['salary'] ?? 0);
                            return $salaryA <=> $salaryB;
                        });
                    } else {
                        usort($jobs, function($a, $b) {
                            $salaryA = (float)($a['salary'] ?? 0);
                            $salaryB = (float)($b['salary'] ?? 0);
                            return $salaryB <=> $salaryA;
                        });
                    }
                    $jobs = array_filter($jobs, function($job) {
                        return !empty($job['salary']) && (float)$job['salary'] > 0;
                    });
                    $jobs = array_values($jobs);
                }
                
                if (!empty($jobs)) {
                    $recommendedJobs = [];
                    foreach (array_slice($jobs, 0, $limit) as $job) {
                        $recommendedJobs[] = [
                            'id' => $job['id'],
                            'title' => $job['title'],
                            'company' => $job['company'] ?? '',
                            'location' => $job['location'] ?? '',
                            'salary' => $job['salary'] ?? '',
                            'url' => '/Worknest/public/jobs/show/' . $job['id']
                        ];
                    }
                    
                    $resultMsg = "Found " . count($recommendedJobs) . " job(s):\n\n" . 
                               implode("\n", array_map(fn($j) => "• {$j['title']} at {$j['company']}" . (!empty($j['salary']) && (float)$j['salary'] > 0 ? " - " . number_format((float)$j['salary'], 0, ',', '.') . " VND" : ""), $recommendedJobs));
                    
                    return [
                        'success' => true,
                        'message' => $resultMsg,
                        'jobs' => $recommendedJobs
                    ];
                }
            } catch (Exception $fallbackError) {
                error_log('Fallback search also failed: ' . $fallbackError->getMessage());
            }
            
            return [
                'success' => false,
                'message' => 'Sorry, an error occurred while searching for jobs. Please check:\n\n1. Is the API key configured correctly?\n2. Is your internet connection stable?\n3. Try again in a few minutes.',
                'jobs' => []
            ];
        }
    }

    /**
     * Summarize job description
     */
    public function summarizeJobDescription($jobData)
    {
        try {
            $prompt = "Summarize this job posting in a clear, structured format. Use proper markdown formatting with headers, bullet points, and spacing. Respond in English.

**Job Information:**
- Title: {$jobData['title']}
- Company: {$jobData['company']}
- Location: {$jobData['location']}
- Description: " . substr($jobData['description'] ?? '', 0, 1500) . "
- Requirements: " . substr($jobData['requirements'] ?? '', 0, 1500) . "
- Salary: " . ($jobData['salary'] ? number_format($jobData['salary'], 0, ',', '.') . ' VND' : 'Negotiable') . "
- Deadline: " . ($jobData['deadline'] ? date('F j, Y', strtotime($jobData['deadline'])) : 'Not specified') . "

Please format your response like this (use markdown headers and bullet points, but NO HTML tags and NO bold markers):

## 📋 Job Overview
[Brief 1-2 sentence overview of what this job is about]

## 💰 Compensation
- Salary: [amount or Negotiable]
- Benefits: [if mentioned]

## ✨ Key Requirements
- [Requirement 1]
- [Requirement 2]
- [Requirement 3]
[Add more bullets as needed]

## 📍 Additional Details
- Location: [location]
- Deadline: [deadline]

Make it visually appealing and easy to scan. Use proper spacing between sections. 
IMPORTANT: Use only plain text and markdown formatting. Do NOT use HTML tags like <s>, </s>, or any other HTML. Do NOT use ** markers for bold text.";

            $summary = $this->callGeminiAPI($prompt, 1024);
            
            return [
                'success' => true,
                'summary' => $summary
            ];

        } catch (Exception $e) {
            error_log('AI Job Summary Error: ' . $e->getMessage());
            return [
                'success' => false,
                'summary' => 'Unable to summarize this job. Please try again.'
            ];
        }
    }

    /**
     * Answer questions about a specific job
     */
    public function answerJobQuestion($jobData, $question)
    {
        try {
            $prompt = "You are a job search assistant. Answer the question based on the following job information. Respond in English with clear formatting (use headers, bullet points, etc.), but you can understand questions in Vietnamese.

**Job Information:**
- Title: {$jobData['title']}
- Company: {$jobData['company']}
- Location: {$jobData['location']}
- Description: {$jobData['description']}
- Requirements: {$jobData['requirements']}
- Salary: " . ($jobData['salary'] ? number_format($jobData['salary'], 0, ',', '.') . ' VND' : 'Negotiable') . "
- Deadline: {$jobData['deadline']}

**User's question:** {$question}

Answer in a well-structured format using markdown:
- Use headers (##) for main sections
- Use bullet points (-) for lists
- Break into paragraphs for readability
- Do NOT use HTML tags like <s>, </s>, or any other HTML
- Do NOT use ** markers for bold text

If the question is about missing skills, format it as a clear list with headers.";

            $answer = $this->callGeminiAPI($prompt, 1024);
            
            return [
                'success' => true,
                'answer' => $answer
            ];

        } catch (Exception $e) {
            error_log('AI Job Q&A Error: ' . $e->getMessage());
            return [
                'success' => false,
                'answer' => 'Sorry, I couldn\'t answer this question. Please try again or ask something else.'
            ];
        }
    }

    /**
     * Detect if message is a job search intent vs general conversation
     */
    private function isJobSearchIntent($message)
    {
        // Strong job search indicators - these clearly indicate job search
        $strongIndicators = [
            '/(tìm|kiếm|tuyển|việc).*job/i',           // "tìm job", "kiếm việc"
            '/job.*(search|list|find|looking|available|open)/i',  // "job search", "job available"
            '/(looking for|find|search for|apply for|apply to).*(job|role|position)/i',  // "looking for a job"
            '/\b(developer|engineer|manager|designer|analyst|programmer|architect|designer|coordinator|specialist|consultant|assistant|intern)\b.*\b(job|position|role|work)\b/i'  // specific roles
        ];
        
        foreach ($strongIndicators as $pattern) {
            if (preg_match($pattern, $message)) {
                return true;
            }
        }
        
        // Weak indicators that could be job search OR general conversation
        $weakIndicators = [
            '/salary/',
            '/work/',
            '/job/',
            '/hire/',
            '/apply/',
            '/position/'
        ];
        
        // Count how many weak indicators are present
        $weakCount = 0;
        foreach ($weakIndicators as $pattern) {
            if (preg_match($pattern, $message, $matches)) {
                $weakCount++;
            }
        }
        
        // If multiple weak indicators AND message sounds like a search query (not a question about the topic)
        if ($weakCount >= 2) {
            // Check if it's phrased as a question about the topic (general conversation)
            // vs a directive to search (job search)
            if (preg_match('/^(why|how|what|when|where|who|can|should|will|is|are|does|do)\b/i', $message)) {
                // Phrased as a question - likely general conversation
                return false;
            }
            // Otherwise it's likely a job search
            return true;
        }
        
        return false;
    }

    /**
     * General chat handler
     */
    public function handleChat($message, $context = [])
    {
        try {
            // Check if message contains job search intent
            if ($this->isJobSearchIntent($message)) {
                return $this->searchJobs($message);
            }

            // If context has job data, treat as job-related question
            if (!empty($context['job_id'])) {
                $job = $this->jobDAO->getById($context['job_id']);
                if ($job) {
                    $jobData = [
                        'title' => $job->getTitle(),
                        'company' => $job->getCompanyName() ?: $job->getEmployerName(),
                        'location' => $job->getLocation(),
                        'description' => $job->getDescription(),
                        'requirements' => $job->getRequirements(),
                        'salary' => $job->getSalary(),
                        'deadline' => $job->getDeadline()
                    ];
                    
                    // Check if asking for summary
                    if (preg_match('/(tóm tắt|summary|tổng hợp|highlight)/i', $message)) {
                        return $this->summarizeJobDescription($jobData);
                    }
                    
                    // Otherwise, answer question about the job
                    return $this->answerJobQuestion($jobData, $message);
                }
            }

            // General response
            $prompt = "You are an AI assistant for Worknest - a job search platform. Respond in English with clear formatting (use bullet points, headers if needed), but you can understand Vietnamese questions.

User says: \"{$message}\"

Please respond in a friendly and helpful manner in English. Use markdown formatting:
- Use bullet points (-) for lists
- Break into paragraphs for readability
- Use headers (##) if organizing information into sections
- Do NOT use HTML tags like <s>, </s>, or any other HTML
- Do NOT use ** markers for bold text

If the user wants to search for jobs, suggest they describe their skills, experience, desired location, etc.";

            $response = $this->callGeminiAPI($prompt, 512);
            
            return [
                'success' => true,
                'message' => $response,
                'type' => 'general'
            ];

        } catch (Exception $e) {
            error_log('AI Chat Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Sorry, an error occurred. Please try again later.'
            ];
        }
    }
}

