# Hướng Dẫn Setup Worknest AI Assistant với OpenRouter + Mistral

## Tổng Quan

Worknest AI Assistant **KHÔNG CẦN API KEY** - sử dụng **OpenRouter free tier** với **Mistral 7B** model!

### Tính Năng:
- 🔍 **Tìm kiếm việc làm thông minh**: Phân tích câu hỏi của người dùng và tìm công việc phù hợp
- 📋 **Tóm tắt JD**: Biến job description dài thành các bullet points dễ đọc
- 💬 **Hỏi đáp về công việc**: Trả lời các câu hỏi về yêu cầu, kỹ năng, mức lương, v.v.

## ✨ HOÀN TOÀN MIỄN PHÍ - KHÔNG CẦN API KEY!

OpenRouter cung cấp **free tier** với model Mistral 7B:
- ✅ **Không cần đăng ký**
- ✅ **Không cần API key**
- ✅ **Hoạt động ngay lập tức**
- ⚡ **Nhanh và ổn định**

## Bước 1: Kiểm Tra Setup

### Không Cần Làm Gì! 
Code đã được cấu hình sẵn để dùng OpenRouter free tier. Chỉ cần:

1. **Đảm bảo PHP CURL extension đã enabled** (thường đã có sẵn)
2. **Truy cập trang test**:
   ```
   http://localhost:8888/Worknest/public/test-gemini-api.php
   ```
3. **Nếu tất cả đều ✅**: Bạn có thể dùng ngay!

## Bước 2: (Tùy Chọn) Lấy API Key để Có Thêm Credits

Nếu bạn muốn:
- ⚡ Tăng tốc độ
- 🔄 Có nhiều requests hơn
- 🎯 Truy cập các model mạnh hơn

Thì có thể lấy OpenRouter API key:

1. **Đăng ký tại**: [OpenRouter](https://openrouter.ai/)
   - Hoặc truy cập: https://openrouter.ai/keys

2. **Tạo API Key**:
   - Đăng nhập/Đăng ký
   - Vào mục "Keys"
   - Click "Create Key"
   - Copy API key

3. **Thêm vào file `.env`**:
   ```env
   OPENROUTER_API_KEY=your_key_here
   ```

4. **Restart web server**

**Lưu ý**: API key là **TÙY CHỌN** - code sẽ hoạt động tốt ngay cả khi không có!

## Bước 3: Test & Sử Dụng

1. **Test kết nối**:
   ```
   http://localhost:8888/Worknest/public/test-gemini-api.php
   ```

2. **Sử dụng chatbot**:
   - Truy cập: `http://localhost:8888/Worknest/public/jobs`
   - Click nút chat bubble 💬 ở góc dưới bên phải
   - Thử các câu hỏi:
     - "Tìm việc data science"
     - "Em học IT năm 2, biết PHP, tìm part-time ở Tân Phú"
     - "Tóm tắt công việc này" (trên trang job detail)

## Model Đang Sử Dụng

- **Model**: `mistralai/mistral-7b-instruct:free`
- **Provider**: OpenRouter
- **Cost**: FREE
- **Performance**: Nhanh, phù hợp cho chatbot

### Nếu Muốn Đổi Model

Trong `AIAssistantService.php`, bạn có thể đổi model:

```php
// Hiện tại (free)
$this->model = 'mistralai/mistral-7b-instruct:free';

// Các model khác (cần API key và có thể tốn phí):
// $this->model = 'mistralai/mistral-medium'; // Mạnh hơn
// $this->model = 'openai/gpt-3.5-turbo'; // GPT-3.5
// $this->model = 'anthropic/claude-3-haiku'; // Claude
```

Xem danh sách đầy đủ: https://openrouter.ai/models

## Xử Lý Lỗi

### Lỗi: "CURL Error"

**Nguyên nhân**: PHP CURL extension chưa được enable

**Giải pháp**:
1. Mở file `php.ini`
2. Tìm dòng: `;extension=curl`
3. Bỏ dấu `;` để thành: `extension=curl`
4. Restart web server

### Lỗi: "HTTP 429 - Rate Limit"

**Nguyên nhân**: Đã vượt quá rate limit của free tier

**Giải pháp**:
1. Đợi một chút rồi thử lại
2. Hoặc lấy OpenRouter API key để có thêm credits

### Lỗi: "HTTP 401/403"

**Nguyên nhân**: API key không hợp lệ (nếu có)

**Giải pháp**:
1. Kiểm tra API key trong file `.env`
2. Hoặc xóa API key đi để dùng free tier

## So Sánh với Gemini

| Tính năng | OpenRouter + Mistral | Google Gemini |
|-----------|---------------------|---------------|
| API Key | ❌ Không cần | ✅ Cần |
| Free Tier | ✅ Có sẵn | ⚠️ Cần setup |
| Dễ dùng | ✅ Rất dễ | ⚠️ Phức tạp hơn |
| Tốc độ | ⚡ Nhanh | ⚡ Nhanh |
| Chất lượng | ⭐⭐⭐⭐ | ⭐⭐⭐⭐⭐ |

## FAQ

**Q: Tôi có thể dùng mà không cần API key không?**
A: ✅ Có! Code đã được cấu hình để hoạt động ngay mà không cần API key.

**Q: Free tier có giới hạn gì không?**
A: Có rate limit nhưng đủ dùng cho mục đích demo/test.

**Q: Tôi có thể đổi sang model khác không?**
A: Có, sửa `$this->model` trong `AIAssistantService.php`. Một số model có thể cần API key và tốn phí.

**Q: Model Mistral có tốt không?**
A: Mistral 7B là model rất tốt, được đánh giá cao trong cộng đồng AI. Đủ mạnh cho chatbot và xử lý ngôn ngữ tự nhiên.

## Cấu Trúc Files

```
Worknest/
├── app/
│   ├── services/
│   │   └── AIAssistantService.php    # Service dùng OpenRouter
│   └── controllers/
│       └── AIAssistantController.php  # Controller
├── public/
│   ├── css/
│   │   └── ai-chatbot.css            # CSS cho chatbot
│   ├── javascript/
│   │   └── ai-chatbot.js             # JavaScript
│   └── test-gemini-api.php           # Test script
└── .env                               # (Optional) OPENROUTER_API_KEY
```

## Support

- **OpenRouter Docs**: https://openrouter.ai/docs
- **Mistral AI**: https://mistral.ai/
- **Test Script**: `/Worknest/public/test-gemini-api.php`

---

**Chúc bạn sử dụng vui vẻ! 🚀**

