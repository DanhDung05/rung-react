# RUNG ADVENTURE — HƯỚNG DẪN TRIỂN KHAI

> **Cập nhật:** 10/11/2026 — Giai đoạn 1
> **Đối tượng:** lập trình viên / người vận hành
> **Nguyên tắc:** không bao giờ chạy `migrate:fresh`, luôn backup trước migration.

---

## 1. YÊU CẦU HỆ THỐNG

| Thành phần | Tối thiểu | Đang dùng | Ghi chú |
|---|---|---|---|
| PHP | 8.1 | 8.1.10 | Extension bắt buộc: `gd`, `intl`, `zip`, `pdo_mysql`, `mbstring`, `fileinfo`, `openssl`, `curl`, `exif` |
| Composer | 2.x | 2.10.1 | ⚠ 2.10 chặn Laravel 10 do advisory (xem §8.1) |
| MySQL | 8.0 | 8.0.30 | utf8mb4 |
| Node.js | 18/20 LTS | 26.13.0 | ⚠ Node 26 ngoài ma trận hỗ trợ Vite 5 |
| npm | 9+ | 11.20 | Trên Windows phải dùng `npm.cmd` |
| Web server | Apache/Nginx | Laragon (Apache) | Cần `mod_rewrite`, `AllowOverride All` |

---

## 2. CÀI ĐẶT LOCAL (Laragon / Windows)

### 2.1. Lấy source và dependency

```powershell
cd C:\laragon\www\rung-react

# PHP deps — bắt buộc --no-blocking với Composer 2.10+
composer install --no-interaction --prefer-dist --no-blocking

# JS deps — dùng npm.cmd, KHÔNG dùng npm (npm.ps1 bị ExecutionPolicy chặn)
npm.cmd install
```

### 2.2. Tạo file môi trường

```powershell
Copy-Item .env.example .env
php artisan key:generate
```

Sửa `.env` tối thiểu:

```dotenv
APP_NAME="Rung Adventure"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://rung-react.test

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rungadventure
DB_USERNAME=root
DB_PASSWORD=

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=database
FILESYSTEM_DISK=public

MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_FROM_ADDRESS="hello@rungadventure.com"

BROADCAST_DRIVER=log
```

> `FILESYSTEM_DISK=public` chỉ ảnh hưởng thao tác mặc định; code hiện tại luôn gọi tường minh `Storage::disk('public')`, nên giá trị này không bắt buộc.

### 2.3. Chuẩn bị thư mục ghi

```powershell
New-Item -ItemType Directory -Force storage\logs
New-Item -ItemType Directory -Force storage\framework\cache\data
New-Item -ItemType Directory -Force storage\framework\sessions
New-Item -ItemType Directory -Force storage\framework\views
```

### 2.4. Storage link (quan trọng)

Hiện `public/storage` là **thư mục copy thật**, không phải symlink → upload mới **không hiển thị**.

```powershell
# 1. BACKUP trước khi xóa
Copy-Item -Recurse storage\app\public storage\app\public_backup_20261110

# 2. Xóa thư mục copy
Remove-Item -Recurse -Force public\storage

# 3. Tạo symlink (Windows cần quyền Admin hoặc Developer Mode)
php artisan storage:link
```

Kiểm tra: `Get-Item public\storage | Select LinkType` phải trả `Junction` hoặc `SymbolicLink`.

### 2.5. Database

```powershell
php artisan migrate:status   # phải thấy 23/23 Ran
# KHÔNG chạy migrate:fresh
```

Nếu là máy mới chưa có DB:

```powershell
# Tạo DB rỗng rồi migrate (KHÔNG import rungadventure.sql — dump cũ thiếu bảng)
mysql -u root -e "CREATE DATABASE rungadventure CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
php artisan migrate
```

### 2.6. Build frontend

```powershell
npm.cmd run dev      # dev server, HMR
# hoặc
npm.cmd run build    # production, sinh public/build
```

### 2.7. Chạy

```powershell
php artisan serve
# hoặc tạo vhost Laragon trỏ tới C:\laragon\www\rung-react\public
```

---

## 3. BIẾN MÔI TRƯỜNG

### 3.1. Bắt buộc

| Biến | Ý nghĩa | Ghi chú |
|---|---|---|
| `APP_NAME` | Tên app (cookie, cache prefix) | Đặt `"Rung Adventure"` |
| `APP_ENV` | `local` / `production` | |
| `APP_KEY` | Khóa mã hóa | Bắt buộc, sinh bằng `key:generate` |
| `APP_DEBUG` | Hiện stack trace | **`false` trên production** |
| `APP_URL` | URL gốc | Ảnh hưởng `url()`, canonical |
| `DB_*` | Kết nối DB | |
| `SESSION_DRIVER` | `file` / `database` / `redis` | |
| `CACHE_DRIVER` | `file` / `redis` | Cache settings + trang chủ |
| `QUEUE_CONNECTION` | `sync` / `database` | Mail queue cần `database` + worker |
| `MAIL_*` | SMTP | Không có mail thật thì form liên hệ vẫn lưu DB (mail lỗi được bắt) |

### 3.2. Tùy chọn

| Biến | Dùng cho |
|---|---|
| `ADMIN_EMAIL`, `ADMIN_PASSWORD`, `EDITOR_EMAIL`, `EDITOR_PASSWORD` | `UserSeeder` — **không dùng trên production** |
| `PUSHER_*`, `VITE_PUSHER_*` | Realtime (Soketi) — hiện chưa hoạt động |
| `APP_VERSION` | *(sẽ thêm ở GĐ4)* |

---

## 4. BUILD PRODUCTION

```bash
composer install --no-dev --optimize-autoloader --no-blocking
npm ci
npm run build

php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

**Lưu ý:**
- `route:cache` hoạt động vì toàn bộ route dùng controller, không có closure (trừ route `admin.upload.image` dùng closure → **`route:cache` SẼ LỖI**).
  → **Bắt buộc** chuyển closure upload TinyMCE thành method controller trước khi bật `route:cache`. Đây là việc cần làm ở GĐ2 (`routes/admin.php:340-356`).
- Không commit `public/build` nếu CI tự build; hoặc commit nếu deploy thủ công.
- Không commit `.env`, `node_modules`, `storage/*` (trừ `.gitignore`).

---

## 5. CẤU HÌNH WEB SERVER

### 5.1. Apache (Laragon)

```apache
<VirtualHost *:80>
    ServerName rung-react.test
    DocumentRoot "C:/laragon/www/rung-react/public"

    <Directory "C:/laragon/www/rung-react/public">
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog  "C:/laragon/www/rung-react/storage/logs/apache-error.log"
    CustomLog "C:/laragon/www/rung-react/storage/logs/apache-access.log" combined
</VirtualHost>
```

Bắt buộc `AllowOverride All` để `.htaccess` hoạt động. Thêm `rung-react.test` vào `C:\Windows\System32\drivers\etc\hosts`:
```
127.0.0.1 rung-react.test
```

### 5.2. Nginx

```nginx
server {
    listen 80;
    server_name rung-react.test;
    root /var/www/rung-react/public;

    index index.php;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* { deny all; }

    client_max_body_size 25m;   # upload ảnh tới 20MB
}
```

### 5.3. Quyền thư mục (Linux)

```bash
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

---

## 6. QUEUE & SCHEDULER

### 6.1. Queue worker

Mail liên hệ dùng `Mail::queue()`. Với `QUEUE_CONNECTION=database` cần worker:

**Linux — Supervisor:**
```ini
[program:rung-queue]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/rung-react/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/rung-react/storage/logs/queue.log
stopwaitsecs=3600
```

**Windows — NSSM hoặc chạy `composer dev` khi phát triển.**

### 6.2. Scheduler

Hiện `Console/Kernel::schedule()` **rỗng**. Khi bổ sung (GĐ3/GĐ5) cần cron:

```
* * * * * cd /var/www/rung-react && php artisan schedule:run >> /dev/null 2>&1
```

---

## 7. CHECKLIST TRƯỚC KHI DEPLOY

### 7.1. Cấu hình

- [ ] `.env` production có `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL` đúng.
- [ ] `APP_KEY` đã sinh, **khác** môi trường dev.
- [ ] DB credential đúng, user DB chỉ có quyền cần thiết (không dùng root).
- [ ] `MAIL_*` trỏ SMTP thật đã test gửi.
- [ ] `SESSION_SECURE_COOKIE=true` nếu chạy HTTPS.
- [ ] `BROADCAST_DRIVER` phù hợp (nếu chưa dùng realtime: `log`).

### 7.2. Dữ liệu

- [ ] **Backup DB**: `mysqldump rungadventure > backup_$(date +%Y%m%d_%H%M).sql`.
- [ ] `php artisan migrate:status` → không có migration pending bất thường.
- [ ] Không có kế hoạch chạy `migrate:fresh`.
- [ ] KHÔNG import `rungadventure.sql` (dump cũ).

### 7.3. File & quyền

- [ ] `storage/` và `bootstrap/cache/` ghi được bởi web user.
- [ ] `public/storage` là symlink, trỏ `storage/app/public`.
- [ ] `storage/logs/laravel.log` ghi được.
- [ ] `public/build/manifest.json` tồn tại.

### 7.4. Bảo mật

- [ ] `APP_DEBUG=false`.
- [ ] `.env` không nằm trong webroot, không commit.
- [ ] `/admin` yêu cầu đăng nhập; tài khoản `editor` **không** vào được Users/Settings (sau GĐ3).
- [ ] Mật khẩu admin mặc định (`dung2005`) đã đổi.
- [ ] Debugbar không xuất hiện trên production (`APP_DEBUG=false` là đủ).
- [ ] HTTPS + redirect HTTP→HTTPS.
- [ ] `robots.txt` trỏ đúng domain production.
- [ ] `php artisan cache:clear` + `view:clear` sau deploy (xóa cache cũ chứa PII).

### 7.5. Chức năng (smoke test)

- [ ] `GET /` → 200, đúng giao diện.
- [ ] `GET /tour`, `/diem-den`, `/bai-viet` → 200.
- [ ] `GET /sitemap.xml` → XML hợp lệ.
- [ ] Đăng nhập admin → dashboard.
- [ ] Tạo/sửa/xóa thử 1 tour.
- [ ] Upload ảnh → hiển thị ngoài frontend.
- [ ] Gửi form liên hệ → lưu DB + nhận mail.
- [ ] Trang 404 trả đúng.

---

## 8. XỬ LÝ SỰ CỐ THƯỜNG GẶP

### 8.1. `composer install` báo "affected by security advisories"

**Nguyên nhân:** Composer ≥ 2.9 chặn package có advisory; Laravel 10 đã EOL nên mọi phiên bản 10.x đều bị đánh dấu.

**Xử lý tạm:**
```bash
composer install --no-blocking
# hoặc
COMPOSER_NO_SECURITY_BLOCKING=1 composer install
```
**Xử lý lâu dài:** nâng Laravel 11/12 (ngoài phạm vi dự án này). Ghi nhận rủi ro trong `PROJECT_AUDIT.md`.

### 8.2. `npm` báo "running scripts is disabled on this system"

**Nguyên nhân:** ExecutionPolicy chặn `npm.ps1`.
**Xử lý:** dùng `npm.cmd` hoặc `cmd /c npm`.

### 8.3. `ViteManifestNotFoundException` / mọi trang 500

**Nguyên nhân:** chưa build hoặc chưa chạy dev server, `public/build` và `public/hot` đều không có.
**Xử lý:** `npm run dev` (dev) hoặc `npm run build` (production).

### 8.4. `storage/logs` không tạo được / Permission denied

**Nguyên nhân:** quyền thư mục hoặc sandbox hạn chế.
**Xử lý:** tạo thư mục bằng user sở hữu source, cấp quyền ghi cho web user. Trên Windows: kiểm tra ACL của `storage` (kế thừa từ thư mục cha).

### 8.5. Upload ảnh xong nhưng ảnh 404

**Nguyên nhân:** `public/storage` là thư mục copy, không phải symlink.
**Xử lý:** xem §2.4.

### 8.6. `storage:link` báo "link already exists"

**Nguyên nhân:** `public/storage` đang là thư mục thật.
**Xử lý:** backup rồi xóa thư mục, chạy lại `storage:link`.

### 8.7. `route:cache` lỗi "Unable to prepare route for serialization"

**Nguyên nhân:** `routes/admin.php:340-356` dùng closure cho upload TinyMCE.
**Xử lý:** chuyển closure thành `MediaController@uploadImage` (việc của GĐ2), sau đó mới bật `route:cache`.

### 8.8. Đăng nhập xong bị 404

**Nguyên nhân:** `RouteServiceProvider::HOME = '/home'` nhưng không có route `/home`.
**Xử lý:** đổi thành `'/'` (một dòng). Cần sửa ở GĐ2/GĐ3.

### 8.9. 404 trả 500

**Nguyên nhân:** `errors/404.blade.php` → `layouts.app` → `@vite('resources/js/app.js')`, file không có trong Vite input.
**Xử lý:** bỏ `@vite` khỏi layout 404 hoặc chuyển 404 sang Inertia (GĐ2).

### 8.10. Trang chủ hiển thị dữ liệu cũ

**Nguyên nhân:** cache `home_*` (10 phút) và `storage/framework/cache/data` còn dữ liệu cũ.
**Xử lý:** `php artisan cache:clear`.

---

## 9. ROLLBACK

| Tình huống | Cách xử lý |
|---|---|
| Deploy code lỗi | `git checkout <tag trước>` + `npm run build` + `php artisan optimize:clear` |
| Migration mới lỗi | `php artisan migrate:rollback --step=1` (chỉ khi migration có `down()` đúng) |
| Dữ liệu hỏng | Restore backup SQL đã tạo trước deploy |
| Asset lỗi | Build lại từ commit trước, xóa `public/build` cũ |

**Không có git repo hiện tại** → cần `git init` trước khi bắt đầu Giai đoạn 2 để có thể rollback. Đây là khuyến nghị bắt buộc.

---

## 10. TRIỂN KHAI NHANH (tóm tắt)

```bash
# Build & deploy
composer install --no-dev --optimize-autoloader --no-blocking
npm ci && npm run build
php artisan migrate --force
php artisan config:cache
php artisan view:cache
php artisan cache:clear
php artisan queue:restart

# Kiểm tra
curl -I https://rungadventure.com/
curl -s https://rungadventure.com/sitemap.xml | head
```
