<?php
/**
 * Test script for Gemini API connection
 * Access at: http://localhost:8888/Worknest/public/test-gemini-api.php
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test Gemini API Connection</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 3px solid #667eea;
            padding-bottom: 10px;
        }
        .test-item {
            margin: 20px 0;
            padding: 15px;
            border-left: 4px solid #ddd;
            background: #f9f9f9;
        }
        .test-item.success {
            border-left-color: #4caf50;
            background: #e8f5e9;
        }
        .test-item.error {
            border-left-color: #f44336;
            background: #ffebee;
        }
        .test-item.warning {
            border-left-color: #ff9800;
            background: #fff3e0;
        }
        .label {
            font-weight: bold;
            color: #555;
        }
        .value {
            color: #333;
            margin-top: 5px;
            word-break: break-all;
        }
        code {
            background: #f0f0f0;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: monospace;
        }
        pre {
            background: #f5f5f5;
            padding: 10px;
            border-radius: 4px;
            overflow-x: auto;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
            <h1>🔍 AI API Connection Test (OpenRouter + Mistral)</h1>
        
        <?php
        // Test 1: Check if .env file exists
        $envFile = __DIR__ . '/../.env';
        echo '<div class="test-item ' . (file_exists($envFile) ? 'success' : 'error') . '">';
        echo '<div class="label">1. File .env tồn tại:</div>';
        echo '<div class="value">' . (file_exists($envFile) ? '✅ Có' : '❌ Không - Vui lòng tạo file .env') . '</div>';
        echo '</div>';

        // Test 2: Check API key (OpenRouter or Gemini for backward compatibility)
        $apiKey = $_ENV['OPENROUTER_API_KEY'] ?? $_ENV['GEMINI_API_KEY'] ?? '';
        $hasApiKey = !empty($apiKey);
        $apiType = isset($_ENV['OPENROUTER_API_KEY']) ? 'OpenRouter' : (isset($_ENV['GEMINI_API_KEY']) ? 'Gemini (deprecated)' : 'None');
        
        echo '<div class="test-item ' . ($hasApiKey || true ? 'success' : 'warning') . '">';
        echo '<div class="label">2. API Key được cấu hình (tùy chọn):</div>';
        if ($hasApiKey) {
            $maskedKey = substr($apiKey, 0, 10) . '...' . substr($apiKey, -5);
            echo '<div class="value">✅ Có - Type: ' . htmlspecialchars($apiType) . ' - Key: <code>' . htmlspecialchars($maskedKey) . '</code></div>';
        } else {
            echo '<div class="value">⚠️ Không có - Đang dùng free tier của OpenRouter (không cần API key)</div>';
            echo '<div class="value" style="margin-top: 5px; font-size: 12px;">💡 Tip: Thêm <code>OPENROUTER_API_KEY=your_key_here</code> vào .env để có thêm credits (không bắt buộc)</div>';
        }
        echo '</div>';

        // Test 3: Check CURL extension
        $hasCurl = function_exists('curl_init');
        echo '<div class="test-item ' . ($hasCurl ? 'success' : 'error') . '">';
        echo '<div class="label">3. PHP CURL extension:</div>';
        echo '<div class="value">' . ($hasCurl ? '✅ Đã cài đặt' : '❌ Chưa cài đặt - Cần enable CURL extension trong php.ini') . '</div>';
        echo '</div>';

        // Test 4: Test API connection (OpenRouter)
        if ($hasCurl) {
            echo '<div class="test-item">';
            echo '<div class="label">4. Test kết nối đến OpenRouter API (Mistral free):</div>';
            
            $url = 'https://openrouter.ai/api/v1/chat/completions';
            
            $data = [
                'model' => 'mistralai/mistral-7b-instruct:free',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => 'Xin chào! Trả lời ngắn gọn: "OK" nếu bạn có thể nghe thấy tôi.'
                    ]
                ],
                'max_tokens' => 50,
                'temperature' => 0.7
            ];
            
            $headers = [
                'Content-Type: application/json',
                'HTTP-Referer: ' . ($_SERVER['HTTP_REFERER'] ?? 'https://worknest.local'),
                'X-Title: Worknest AI Assistant'
            ];
            
            if (!empty($apiKey)) {
                $headers[] = 'Authorization: Bearer ' . $apiKey;
            }

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            $curlErrno = curl_errno($ch);
            
            curl_close($ch);

            if ($curlErrno) {
                echo '<div class="value error">❌ Lỗi CURL: ' . htmlspecialchars($curlError) . ' (Code: ' . $curlErrno . ')</div>';
                echo '<div style="margin-top: 10px;"><strong>Giải pháp:</strong><ul>';
                echo '<li>Kiểm tra kết nối internet</li>';
                echo '<li>Kiểm tra firewall có chặn HTTPS không</li>';
                echo '<li>Thử lại sau vài phút</li>';
                echo '</ul></div>';
            } elseif ($httpCode !== 200) {
                echo '<div class="value error">❌ HTTP Error: ' . $httpCode . '</div>';
                
                $errorData = json_decode($response, true);
                echo '<pre>' . htmlspecialchars(json_encode($errorData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) . '</pre>';
                
                if ($httpCode === 400) {
                    echo '<div style="margin-top: 10px;"><strong>Có thể request format sai hoặc model không khả dụng.</strong></div>';
                } elseif ($httpCode === 401 || $httpCode === 403) {
                    echo '<div style="margin-top: 10px;"><strong>API key không hợp lệ (nếu có). Free tier không cần key.</strong></div>';
                } elseif ($httpCode === 429) {
                    echo '<div style="margin-top: 10px;"><strong>Đã vượt quá rate limit. Đợi một chút rồi thử lại.</strong></div>';
                }
            } else {
                $result = json_decode($response, true);
                if (isset($result['choices'][0]['message']['content'])) {
                    $aiResponse = $result['choices'][0]['message']['content'];
                    echo '<div class="value success">✅ Kết nối thành công!</div>';
                    echo '<div class="value">Phản hồi từ AI (Mistral): <strong>' . htmlspecialchars($aiResponse) . '</strong></div>';
                } else {
                    echo '<div class="value error">❌ Không nhận được phản hồi hợp lệ từ API</div>';
                    echo '<pre>' . htmlspecialchars(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) . '</pre>';
                }
            }
            
            echo '</div>';
        } else {
            echo '<div class="test-item warning">';
            echo '<div class="label">4. Test kết nối:</div>';
            echo '<div class="value">⚠️ Bỏ qua - Cần CURL extension để test</div>';
            echo '</div>';
        }

        // Test 5: Check database connection (for job search)
        try {
            require_once __DIR__ . '/../config/db.php';
            $database = new Database();
            $dbConnected = $database->conn !== null;
            echo '<div class="test-item ' . ($dbConnected ? 'success' : 'error') . '">';
            echo '<div class="label">5. Kết nối Database:</div>';
            echo '<div class="value">' . ($dbConnected ? '✅ Kết nối thành công' : '❌ Lỗi kết nối database') . '</div>';
            echo '</div>';
        } catch (Exception $e) {
            echo '<div class="test-item error">';
            echo '<div class="label">5. Kết nối Database:</div>';
            echo '<div class="value">❌ Lỗi: ' . htmlspecialchars($e->getMessage()) . '</div>';
            echo '</div>';
        }
        ?>

        <div style="margin-top: 30px; padding: 20px; background: #e3f2fd; border-radius: 4px;">
            <h3>📝 Hướng dẫn khắc phục:</h3>
            <ol>
                <li><strong>Free Tier (Không cần API key):</strong> Code đã được cấu hình để dùng OpenRouter free tier với Mistral model</li>
                <li><strong>Optional - Lấy API key:</strong> Nếu muốn thêm credits, lấy tại <a href="https://openrouter.ai/keys" target="_blank">OpenRouter</a></li>
                <li>Thêm vào file <code>.env</code>: <code>OPENROUTER_API_KEY=your_key_here</code> (không bắt buộc)</li>
                <li>Restart web server (MAMP/XAMPP)</li>
                <li>Refresh trang này để test lại</li>
                <li>Xem chi tiết trong file <code>GEMINI_AI_SETUP.md</code></li>
            </ol>
        </div>

        <div style="margin-top: 20px; text-align: center;">
            <a href="/Worknest/public/jobs" style="padding: 10px 20px; background: #667eea; color: white; text-decoration: none; border-radius: 4px;">
                Quay lại trang Jobs
            </a>
        </div>
    </div>
</body>
</html>

