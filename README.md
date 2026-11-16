# Rung Adventure — Laravel + React + Inertia.js

Dự án được chuyển từ giao diện Blade sang React chạy trên Inertia.js. Laravel tiếp tục xử lý routing, controller, validation, authentication, nghiệp vụ và database.

## Công nghệ

- PHP 8.1+ / Laravel 10
- Inertia.js Laravel adapter 2.x
- React 18 + Inertia React 2.x
- Vite 5
- Tailwind CSS 3 và CSS hiện có
- Database cấu hình qua Laravel (`.env`)

## Cài đặt

### 1. PHP dependencies

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Do dự án đã bổ sung `inertiajs/inertia-laravel`, hãy chạy `composer update inertiajs/inertia-laravel --with-dependencies` nếu Composer báo lock file chưa đồng bộ.

### 2. Cấu hình database

Sửa `.env` theo database đang sử dụng, sau đó:

```bash
php artisan migrate
php artisan storage:link
```

Nếu đang có database cũ, hãy sao lưu trước khi chạy migration. Không chạy `migrate:fresh` trên dữ liệu thật.

### 3. Chạy môi trường dev (một lệnh)

```bash
npm install
npm run dev
```

`npm run dev` sẽ tự:

- copy TinyMCE vào `public/vendor/tinymce`
- chạy **Laravel** tại `http://127.0.0.1:8000`
- chạy **Vite HMR** tại `http://localhost:5173`
- dừng cả hai khi nhấn `Ctrl+C`

Yêu cầu: MySQL đang chạy (Laragon), `.env` đã cấu hình và đã chạy `php artisan migrate`.

Tùy chọn:

```bash
npm run dev:client   # chỉ Vite
npm run serve        # chỉ Laravel
PORT=8080 npm run dev  # đổi cổng Laravel
npm run build        # build production
```

Không chạy `php artisan serve` riêng khi đang dùng `npm run dev` (sẽ trùng cổng 8000).

## Cấu trúc React/Inertia

- `resources/js/app.jsx`: điểm khởi tạo React + Inertia.
- `resources/js/Pages/Home.jsx`: trang chủ.
- `resources/js/Pages/Auth/Login.jsx`: đăng nhập.
- `resources/js/Pages/Admin/Dashboard.jsx`: dashboard quản trị.
- `resources/js/Pages/GenericPage.jsx`: trang hiển thị dùng chung cho các màn hình Inertia chưa có page component riêng.
- `app/Http/Middleware/HandleInertiaRequests.php`: chia sẻ auth, flash message và root view.
- `resources/views/app.blade.php`: HTML shell duy nhất cho Inertia.

Các Controller hiện tại được giữ lại và các response `view()` đã được chuyển thành `Inertia::render()`. Models, migrations, request validation, routes và logic xử lý dữ liệu Laravel được giữ nguyên. Các Blade templates cũ vẫn được giữ trong `resources/views` để đối chiếu trong quá trình chuyển đổi giao diện chi tiết.

## Lưu ý trước khi deploy

- Tạo `.env` riêng cho từng môi trường; không commit `.env`.
- Kiểm tra lại thông tin database, mail, storage và dịch vụ bên ngoài.
- Chạy `npm run build` và `php artisan optimize` trên môi trường deploy.
- Các màn CRUD chuyên biệt cần được hoàn thiện React form tương ứng theo từng module; page dùng chung hiện hiển thị dữ liệu Inertia, chưa thay thế toàn bộ tương tác form của Blade cũ.
