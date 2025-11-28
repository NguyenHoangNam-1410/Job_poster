# Hướng Dẫn Setup Worknest AI Assistant với Google Gemini API

## Tổng Quan

Worknest AI Assistant sử dụng Google Gemini API để cung cấp các tính năng:
- 🔍 **Tìm kiếm việc làm thông minh**: Phân tích câu hỏi của người dùng và tìm công việc phù hợp
- 📋 **Tóm tắt JD**: Biến job description dài thành các bullet points dễ đọc
- 💬 **Hỏi đáp về công việc**: Trả lời các câu hỏi về yêu cầu, kỹ năng, mức lương, v.v.

## Bước 1: Lấy Gemini API Key (MIỄN PHÍ)

### Google Gemini API - Free Tier

Google Gemini API cung cấp **FREE tier rất hào phóng**:

1. **Đăng ký tại**: [Google AI Studio](https://makersuite.google.com/app/apikey)
   - Hoặc truy cập: https://aistudio.google.com/app/apikey

2. **Đăng nhập** bằng tài khoản Google của bạn

3. **Tạo API Key mới**:
   - Click "Create API Key"
   - Chọn project (hoặc tạo mới)
   - Copy API key được tạo

4. **Free Tier Limits** (theo Google):
   - **60 requests/phút** (RPM)
   - **15 requests/giây** (RPS)
   - **1,500 requests/ngày** (RPD) cho model `gemini-1.5-flash`
   - Hoàn toàn **MIỄN PHÍ** trong free tier

**Lưu ý về Model:**
- Code sử dụng model `gemini-1.5-flash` (nhanh, phù hợp cho chatbot)
- Nếu muốn đổi sang `gemini-1.5-pro` (mạnh hơn, chậm hơn), sửa trong file `AIAssistantService.php`

### Các API AI Free Khác (Nếu cần thay thế)

Nếu bạn muốn thử các API khác:

1. **OpenAI API** (có free credits khi đăng ký)
   - Website: https://platform.openai.com/
   - Free credits: $5 khi đăng ký lần đầu

2. **Anthropic Claude API** (trial period)
   - Website: https://www.anthropic.com/
   - Có thể thử nghiệm miễn phí

3. **Hugging Face Inference API**
   - Website: https://huggingface.co/
   - Free tier có sẵn

4. **Cohere API**
   - Website: https://cohere.com/
   - Có free tier

**Lưu ý**: Code hiện tại đã được thiết kế cho Gemini API. Nếu muốn dùng API khác, cần modify file `AIAssistantService.php`.

## Bước 2: Cấu Hình API Key

1. **Mở file `.env`** trong thư mục gốc của project:
   ```bash
   /Applications/MAMP/htdocs/Worknest/.env
   ```

2. **Thêm dòng sau** vào file `.env`:
   ```env
   GEMINI_API_KEY=your_api_key_here
   BASE_URL=/Worknest/public
   ```

   Thay `your_api_key_here` bằng API key bạn vừa copy.

3. **Ví dụ**:
   ```env
   DB_HOST=localhost
   DB_USERNAME=root
   DB_PASSWORD=
   DB_NAME=job_poster
   GEMINI_API_KEY=AIzaSyA1B2C3D4E5F6G7H8I9J0K1L2M3N4O5P6Q
   BASE_URL=/Worknest/public
   ```

4. **Lưu file** và **restart web server** (MAMP/Apache)

## Bước 3: Kiểm Tra Cài Đặt

### Option 1: Test Script (Khuyên dùng)

1. **Truy cập test script** để kiểm tra toàn bộ cấu hình:
   ```
   http://localhost:8888/Worknest/public/test-gemini-api.php
   ```
   
2. **Script sẽ kiểm tra**:
   - ✅ File .env có tồn tại không
   - ✅ API key đã được cấu hình chưa
   - ✅ PHP CURL extension có sẵn không
   - ✅ Kết nối đến Gemini API có thành công không
   - ✅ Database connection

3. **Nếu tất cả đều ✅**: Bạn có thể sử dụng AI Assistant ngay!

### Option 2: Test Trực Tiếp

1. **Mở trình duyệt** và truy cập:
   - Trang job listing: `http://localhost:8888/Worknest/public/jobs`
   - Hoặc trang job detail: `http://localhost:8888/Worknest/public/jobs/show/1`

2. **Bạn sẽ thấy** nút chat bubble ở góc dưới bên phải: 💬

3. **Click vào nút** để mở AI Assistant

4. **Thử một trong các câu hỏi**:
   - "Em học IT năm 2, biết PHP, tìm part-time ở Tân Phú"
   - "Tìm việc data science"
   - "Tóm tắt công việc này" (trên trang job detail)
   - "Yêu cầu chính của công việc này là gì?"

## Bước 4: Xử Lý Lỗi (Nếu Có)

### Lỗi: "Gemini API key is not configured"

**Nguyên nhân**: API key chưa được set trong file `.env`

**Giải pháp**:
1. Kiểm tra file `.env` có chứa `GEMINI_API_KEY`
2. Đảm bảo không có khoảng trắng thừa
3. Restart web server

### Lỗi: "Gemini API returned HTTP 400/401/403"

**Nguyên nhân**: API key không hợp lệ hoặc đã bị vô hiệu hóa

**Giải pháp**:
1. Kiểm tra API key trên [Google AI Studio](https://aistudio.google.com/app/apikey)
2. Tạo API key mới nếu cần
3. Update lại trong file `.env`

### Lỗi: "HTTP 404 - models/gemini-pro is not found"

**Nguyên nhân**: Model name đã thay đổi. Google đã deprecated `gemini-pro` và thay bằng `gemini-1.5-flash` hoặc `gemini-1.5-pro`

**Giải pháp**:
1. Code đã được cập nhật để dùng `gemini-1.5-flash`
2. Nếu vẫn gặp lỗi, đảm bảo file `AIAssistantService.php` đã được cập nhật
3. Hoặc thử model khác: `gemini-1.5-pro` hoặc `gemini-1.5-flash-latest`

### Lỗi: "Rate limit exceeded"

**Nguyên nhân**: Đã vượt quá giới hạn free tier

**Giải pháp**:
1. Đợi một chút (limits reset mỗi phút/ngày)
2. Hoặc upgrade lên paid tier của Google Cloud

### Lỗi: CORS hoặc Network Error

**Nguyên nhân**: Vấn đề với CURL hoặc SSL

**Giải pháp**:
1. Kiểm tra PHP có extension `curl` enabled
2. Kiểm tra firewall/antivirus không chặn outbound requests

## Cấu Trúc Files

```
Worknest/
├── app/
│   ├── services/
│   │   └── AIAssistantService.php    # Service xử lý AI
│   └── controllers/
│       └── AIAssistantController.php  # Controller xử lý API requests
├── public/
│   ├── css/
│   │   └── ai-chatbot.css            # CSS cho chatbot UI
│   ├── javascript/
│   │   └── ai-chatbot.js             # JavaScript cho chatbot
│   └── index.php                      # Routes đã được thêm
└── .env                               # File cấu hình (cần thêm GEMINI_API_KEY)
```

## API Endpoints

Các API endpoints đã được tạo:

- `POST /Worknest/public/api/ai/chat` - Chat tổng quát
- `POST /Worknest/public/api/ai/search-jobs` - Tìm kiếm việc làm
- `POST /Worknest/public/api/ai/summarize-job` - Tóm tắt JD
- `POST /Worknest/public/api/ai/answer-job-question` - Trả lời câu hỏi về job

## Tính Năng

### 1. Tìm Kiếm Việc Làm Thông Minh
- Người dùng mô tả profile và yêu cầu
- AI phân tích và tìm công việc phù hợp từ database
- Hiển thị kết quả với link đến job detail

### 2. Tóm Tắt Job Description
- Chỉ trên trang job detail
- Biến JD dài thành bullet points
- Highlight: lương, yêu cầu, chế độ

### 3. Hỏi Đáp Về Công Việc
- Trả lời các câu hỏi về JD
- Phân tích kỹ năng còn thiếu
- Tư vấn về công việc

## Customization

### Thay Đổi Model

Trong `AIAssistantService.php`, bạn có thể thay đổi model:

```php
// Hiện tại dùng gemini-1.5-flash (nhanh, phù hợp cho chatbot)
$this->apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent';

// Có thể đổi sang gemini-1.5-pro (mạnh hơn, chậm hơn)
// $this->apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-pro:generateContent';

// Hoặc gemini-1.5-flash-latest (phiên bản mới nhất)
// $this->apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-latest:generateContent';
```

### Thay Đổi Style

Chỉnh sửa file `/public/css/ai-chatbot.css` để thay đổi màu sắc, kích thước, v.v.

### Thay Đổi Vị Trí

Trong `ai-chatbot.js`, thay đổi CSS class để di chuyển chatbot sang vị trí khác.

## Troubleshooting

Nếu gặp vấn đề, kiểm tra:

1. **PHP Error Logs**: 
   - MAMP: `/Applications/MAMP/logs/php_error.log`
   - XAMPP: `C:\xampp\apache\logs\error.log`

2. **Browser Console**: 
   - Mở DevTools (F12) > Console tab
   - Xem có lỗi JavaScript không

3. **Network Tab**:
   - Mở DevTools > Network tab
   - Xem API requests có thành công không

## Support

Nếu cần hỗ trợ:
- Kiểm tra [Google Gemini API Documentation](https://ai.google.dev/docs)
- Kiểm tra logs trong file error log của PHP

---

**Lưu ý**: API key là thông tin nhạy cảm. **KHÔNG** commit file `.env` lên Git!

