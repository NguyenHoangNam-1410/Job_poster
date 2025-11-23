# Hướng dẫn cấu hình OAuth (Google & Facebook)

## 🔴 Lỗi hiện tại: "OAuth client was not found" / "invalid_client"

Lỗi này xảy ra khi Google Client ID hoặc Facebook App ID chưa được cấu hình đúng trong file `.env`.

---

## 📋 Bước 1: Tạo Google OAuth Credentials

### 1.1. Truy cập Google Cloud Console
- Vào: https://console.cloud.google.com/
- Đăng nhập bằng tài khoản Google của bạn (`nguyenduydang225@gmail.com`)

### 1.2. Tạo Project mới (nếu chưa có)
1. Click vào dropdown project ở top bar
2. Click "New Project"
3. Đặt tên: `WorkNest` (hoặc tên khác)
4. Click "Create"

### 1.3. Bật Google+ API
1. Vào **APIs & Services** > **Library**
2. Tìm "Google+ API" hoặc "Google Identity"
3. Click vào và bật API

### 1.4. Tạo OAuth 2.0 Credentials
1. Vào **APIs & Services** > **Credentials**
2. Click **"+ CREATE CREDENTIALS"** > **"OAuth client ID"**
3. Nếu chưa có OAuth consent screen, sẽ được yêu cầu cấu hình:
   - **User Type**: Chọn "External" (cho development)
   - **App name**: `WorkNest`
   - **User support email**: Chọn email của bạn
   - **Developer contact**: Nhập email của bạn
   - Click "Save and Continue"
   - Bỏ qua Scopes (click "Save and Continue")
   - Bỏ qua Test users (click "Save and Continue")
   - Click "Back to Dashboard"

4. Tạo OAuth Client ID:
   - **Application type**: Chọn "Web application"
   - **Name**: `WorkNest Web Client`
   - **Authorized JavaScript origins**: 
     ```
     http://localhost
     ```
     ⚠️ **LƯU Ý**: Google chỉ chấp nhận domain hoặc `localhost` (không có path). Nếu MAMP dùng port khác (ví dụ 8888), thêm:
     ```
     http://localhost:8888
     ```
   - **Authorized redirect URIs**: 
     ```
     http://localhost/Worknest/public/auth/login/google/callback
     http://localhost/Worknest/public/auth/register/google/callback
     ```
     ⚠️ **LƯU Ý**: Redirect URIs có thể có path, nhưng phải khớp chính xác với URL trong code
   - Click "Create"

5. **Copy Client ID** (sẽ có dạng: `xxxxx.apps.googleusercontent.com`)

### 1.5. Thêm vào file `.env`
Mở file `.env` và thêm/kiểm tra:
```env
GOOGLE_CLIENT_ID=your-client-id-here.apps.googleusercontent.com
```

---

## 📋 Bước 2: Tạo Facebook App Credentials

### 2.1. Truy cập Facebook Developers
- Vào: https://developers.facebook.com/
- Đăng nhập bằng tài khoản Facebook

### 2.2. Tạo App mới
1. Click **"My Apps"** > **"Create App"**
2. Chọn **"Consumer"** hoặc **"None"**
3. Đặt tên app: `WorkNest`
4. Nhập email liên hệ
5. Click "Create App"

### 2.3. Thêm Facebook Login
1. Trong dashboard, tìm **"Add Product"**
2. Tìm **"Facebook Login"** và click **"Set Up"**
3. Chọn **"Web"** platform

### 2.4. Cấu hình Settings
1. Vào **Settings** > **Basic**
2. Thêm **App Domains**: `localhost`
3. Thêm **Site URL**: `http://localhost/Worknest/public`
4. Click **"Save Changes"**

### 2.5. Cấu hình Facebook Login Settings
1. Vào **Facebook Login** > **Settings**
2. Thêm **Valid OAuth Redirect URIs**:
   ```
   http://localhost/Worknest/public/auth/login/facebook/callback
   ```
3. Click **"Save Changes"**

### 2.6. Cấu hình Email Permission (Quan trọng để tránh warning)
1. Vào **App Review** > **Permissions and Features** (hoặc **Settings** > **Basic** > scroll xuống **App Review**)
2. Tìm **"email"** trong danh sách permissions
3. Nếu app đang ở chế độ **Development**:
   - Email permission sẽ tự động available cho test users
   - Không cần submit for review
4. Nếu app đang ở chế độ **Live**:
   - Cần click **"Request"** cho email permission
   - Submit for review nếu cần
5. **Lưu ý**: Trong Development mode, email permission sẽ hoạt động với:
   - Test users được thêm vào app
   - Developers và Admins của app

### 2.6. Lấy App ID và App Secret
1. Vào **Settings** > **Basic**
2. Copy **App ID** và **App Secret**
3. **Lưu ý**: App Secret cần click "Show" để hiện

### 2.7. Thêm vào file `.env`
```env
FACEBOOK_CLIENT_ID=your-facebook-app-id
FACEBOOK_CLIENT_SECRET=your-facebook-app-secret
FACEBOOK_REDIRECT_URI=http://localhost/Worknest/public/auth/login/facebook/callback
```

---

## 📋 Bước 3: Kiểm tra file `.env`

File `.env` của bạn cần có đầy đủ:

```env
# Google OAuth
GOOGLE_CLIENT_ID=xxxxx.apps.googleusercontent.com

# Facebook OAuth
FACEBOOK_CLIENT_ID=your-facebook-app-id
FACEBOOK_CLIENT_SECRET=your-facebook-app-secret
FACEBOOK_REDIRECT_URI=http://localhost/Worknest/public/auth/login/facebook/callback

# Base URL
BASE_URL=http://localhost/Worknest/public
```

---

## ⚠️ Lưu ý quan trọng

1. **Google OAuth**:
   - Phải thêm đúng **Authorized JavaScript origins** và **Authorized redirect URIs**
   - Client ID phải có đuôi `.apps.googleusercontent.com`
   - Nếu đang development, có thể cần thêm email vào "Test users" trong OAuth consent screen

2. **Facebook OAuth**:
   - App phải ở chế độ **Development** hoặc **Live**
   - Phải thêm đúng **Valid OAuth Redirect URIs**
   - App Secret phải được giữ bí mật

3. **Sau khi cấu hình**:
   - Restart web server (MAMP)
   - Clear browser cache
   - Thử lại đăng nhập

---

## 🔍 Kiểm tra lỗi

Nếu vẫn gặp lỗi, kiểm tra:
1. File `.env` có đúng format không (không có khoảng trắng thừa)
2. Client ID/Secret có copy đầy đủ không
3. Redirect URIs có khớp với cấu hình trong Google/Facebook không
4. Web server đã restart chưa

---

## 📞 Cần hỗ trợ?

Nếu vẫn gặp vấn đề, kiểm tra:
- Error logs trong browser console (F12)
- PHP error logs
- Google/Facebook developer console để xem chi tiết lỗi

