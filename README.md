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

### 3. JavaScript dependencies

```bash
npm install
npm run dev
```

Build production:

```bash
npm run build
```

### 4. Chạy Laravel

```bash
php artisan serve
```

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
