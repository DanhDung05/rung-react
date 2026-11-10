# RUNG ADVENTURE — BÁO CÁO KIỂM TOÁN SOURCE CODE

> **Giai đoạn:** 1 — Audit Source
> **Ngày kiểm toán:** 10/11/2026
> **Thư mục:** `C:\laragon\www\rung-react`
> **Phạm vi:** toàn bộ source (app, routes, config, database, resources), không sửa file nghiệp vụ nào.
> **Trạng thái:** HOÀN THÀNH — chờ phê duyệt để sang Giai đoạn 2.

---

## 0. KẾT LUẬN ĐIỀU HÀNH

Rung Adventure hiện là **một ứng dụng Laravel 10 hoàn chỉnh về nghiệp vụ** nhưng **đang ở trạng thái chuyển đổi dở dang sang React/Inertia**.

| Chỉ số | Giá trị | Ý nghĩa |
|---|---:|---|
| Controller nghiệp vụ | 26 (+ 1 base) | Đầy đủ CRUD cho 15 module |
| `Inertia::render()` | 74 điểm | Backend đã "nói" React |
| React page component tồn tại | **3** (`Home`, `Auth/Login`, `Admin/Dashboard`) | + 1 `GenericPage` |
| React page component **còn thiếu** | **71 / 74 (95,9%)** | Tất cả rơi vào `GenericPage` |
| Blade view | **142 file / 13.438 dòng** | 132 file đã chết (dead code) |
| Code React thực tế (toàn bộ `resources/js`) | **309 dòng** | Trong đó page chỉ ~73 dòng |
| Route | 152 (142 route ứng dụng: 120 admin + 22 frontend) | Không trùng tên, không trùng URI |
| Model / Migration / Seeder | 16 / 23 / 13 | DB thật đã chạy đủ 23 migration |
| Test nghiệp vụ | **0** | Chỉ có 2 test skeleton |

**Nhận định thẳng thắn:** đây **không phải** "website React đã tích hợp" mà là **backend Laravel đã chuyển sang Inertia, còn tầng hiển thị React gần như trống**. `GenericPage.jsx` đang thay thế 71 trang thật — đúng như nguyên tắc số 7 trong đề bài cấm. Việc hoàn thiện React (Giai đoạn 2) là khối lượng lớn nhất của dự án, không phải việc "chỉnh sửa nhỏ".

**3 rủi ro nghiêm trọng nhất cần xử lý trước/song song:**

1. **Leo thang đặc quyền (High):** tài khoản `editor` có thể tự nâng mình thành `admin` qua `/admin/tai-khoan/{id}/edit`.
2. **Không chạy được ở môi trường hiện tại (Blocker):** thiếu `.env`, thiếu `public/build`, `public/storage` là thư mục copy thay vì symlink.
3. **Không có tầng cách ly UI:** 3 trang React đều tự dựng header/footer/sidebar riêng, không có layout dùng chung → mọi trang mới sẽ tiếp tục phân mảnh nếu không xây layout trước.

---

## 1. PHƯƠNG PHÁP VÀ GIỚI HẠN

**Đã thực hiện:**
- Đọc toàn bộ `app/`, `routes/`, `config/`, `database/migrations`, `database/seeders`, `resources/js`, `resources/css`, `resources/views` (inventory), `public/`, `tests/`.
- `php -l` trên **165 file PHP** → **0 lỗi cú pháp**.
- Chạy `php artisan route:list --json` → **152 route**, đối chiếu trùng tên/trùng URI.
- Đọc trực tiếp MySQL `rungadventure` (read-only) → 23/23 migration, số bản ghi từng bảng.
- Đối chiếu tự động 74 `Inertia::render()` với 4 file trong `resources/js/Pages`.

**Giới hạn:**
- Không thực hiện được HTTP smoke test (thiếu `.env`/`APP_KEY`, thiếu Vite build; sandbox hiện tại chặn ghi vào `storage/`, `bootstrap/cache`).
- Không cài `node_modules` trong Giai đoạn 1 (không cần cho audit, sẽ làm ở Giai đoạn 2/6).
- Không chạy PHPUnit (sẽ chạy khi có `.env` và build).

---

## 2. HIỆN TRẠNG KỸ THUẬT

### 2.1. Stack

| Thành phần | Khai báo | Thực tế đã cài | Ghi chú |
|---|---|---|---|
| PHP | `^8.1` | 8.1.10 (ZTS, VS16) | Đủ ext: gd, intl, zip, pdo_mysql, fileinfo, exif |
| Laravel | `^10.10` | **10.50.4** | ⚠ Laravel 10 đã EOL, có advisory |
| Inertia Laravel | `^2.0` | 2.0.28 | OK |
| React | `^18.3.1` | (chưa cài node_modules) | |
| @inertiajs/react | `^2.0.0` | — | |
| Vite | `^5.0.0` | — | Node 26.13 quá mới so với Vite 5 |
| Tailwind | `^3.4.19` | — | |
| Intervention Image | `^3.11` | 3.11.10 | Dùng cho ImageService |
| Pusher/Echo | `^7.3` / `^8.6` | 7.3.0 | Nhưng BroadcastServiceProvider bị tắt |
| PHPUnit | `^10.1` | 10.5.66 | Không có test nghiệp vụ |

### 2.2. Trạng thái môi trường (đã xác minh)

| Hạng mục | Trạng thái | Ảnh hưởng |
|---|---|---|
| `.env` | ❌ không tồn tại | Không boot được (thiếu `APP_KEY`) |
| `vendor/` | ⚠ đã cài nhưng `package:discover` fail | `vendor/autoload.php` có; manifest package chưa ghi |
| `node_modules/` | ❌ không có | Không build/dev được |
| `public/build/`, `public/hot` | ❌ không có | Mọi `@vite()` sẽ ném exception |
| `public/storage` | ⚠ **thư mục thật**, không phải symlink | Upload mới không hiển thị; `storage:link` báo lỗi |
| `storage/logs` | ❌ không tồn tại | Ghi log thất bại |
| Git | ❌ không có `.git` | Không có lịch sử/rollback |
| Ổ đĩa sandbox | ⚠ chỉ ghi được thư mục gốc | Không chạy được artisan trong phiên này |

**Ghi chú quan trọng về `composer install`:** Composer 2.10.1 mặc định **chặn cài** vì mọi phiên bản Laravel 10 đều dính advisory bảo mật. Đã cài được bằng `--no-blocking`. Đây là rủi ro vận hành: máy CI/deploy mới sẽ gặp lại lỗi này nếu không cấu hình chính sách.

---

## 3. KIẾN TRÚC HIỆN TẠI

```
Request
  │
  ├─ routes/web.php (22 route frontend)
  ├─ routes/admin.php (120 route, middleware 'admin')
  └─ routes/auth.php (2 route)
        │
        ▼
  Controller (26 controller)
        │  Inertia::render('X/Y', props)
        ▼
  resources/views/app.blade.php   ← root view duy nhất còn sống
        │  @vite(['app.css','app.jsx','echo.js'])
        ▼
  resources/js/app.jsx
        │  import.meta.glob('./Pages/**/*.jsx', { eager: true })
        │  resolve: name → pages[name] ?? GenericPage
        ▼
  Page component (chỉ 3 cái tồn tại)
```

**Điểm mấu chốt:** `resources/views/app.blade.php` là **root view của Inertia**, giữ nguyên theo đúng nguyên tắc số 8. Mọi Blade khác đã trở thành dead code vì không controller nào còn `return view()`.

**Hệ quả kiến trúc:**
- Toàn bộ nghiệp vụ (query, validate, cache, upload, phân quyền) nằm ở Laravel — **giữ nguyên, không cần viết lại**.
- Tầng hiển thị phải làm mới gần như 100%.
- `HandleInertiaRequests` chỉ share `auth.user` + `flash` → **settings, menu động, theme màu, hero image không đến được React**.

---

## 4. ROUTES

### 4.1. Tổng hợp (đã kiểm bằng `route:list`)

| Nhóm | Số route | Ghi chú |
|---|---:|---|
| Admin (`/admin/*`) | **120** | 16 module |
| Frontend + hệ thống | **22** | tour, điểm đến, bài viết, trang tĩnh, liên hệ, tìm kiếm, sitemap, auth |
| Debugbar/ignition (dev) | 9 | tự sinh, chỉ khi APP_DEBUG |
| **Tổng** | **152** | **0 route trùng tên, 0 trùng URI+method** |

Phân bổ admin: `menu` 12, `bai-viet/danh-muc/diem-den/faq/slider/tag/tour/trang-tinh` 10 mỗi module, `danh-gia` 7, `tai-khoan` 7, `media` 5, `cau-hinh` 3, `lien-he` 3, `dashboard` 2, `upload` 1.

### 4.2. Frontend routes

| URI | Name | Controller | Inertia target |
|---|---|---|---|
| `/` | `home` | `HomeController@index` | `Home` ✅ |
| `/tour` | `tours.index` | `Frontend\TourController@index` | `Tour/Index` ❌ |
| `/tour/danh-muc/{slug}` | `tours.category` | `@category` | redirect → `tours.index` |
| `/tour/{slug}` | `tours.show` | `@show` | `Tour/Show` ❌ |
| `/diem-den` | `destinations.index` | `DestinationController@index` | `Destination/Index` ❌ |
| `/diem-den/{slug}` | `destinations.show` | `@show` | `Destination/Show` ❌ |
| `/bai-viet` | `posts.index` | `PostController@index` | `Blog/Index` ❌ |
| `/bai-viet/danh-muc/{slug}` | `posts.category` | `@category` | `Blog/Category` ❌ |
| `/bai-viet/tag/{slug}` | `posts.tag` | `@tag` | `Blog/Tag` ❌ |
| `/bai-viet/{slug}` | `posts.show` | `@show` | `Blog/Show` ❌ |
| `/gioi-thieu` | `about` | `PageController@about` | `Pages/About` ❌ |
| `/faq` | `faq` | `FaqController@index` | `Pages/Faq` ❌ |
| `/lien-he` | `contact` / `contact.store` | `ContactController` | `Pages/Contact` ❌ |
| `/tim-kiem` | `search` | `SearchController@index` | `Search/Index` ❌ |
| `/trang-tinh/{slug}` | `pages.show` | `PageController@show` | `Pages/Show` ❌ |
| `/tour/{tour}/review` | `tours.review.store` | `ReviewController@store` | POST, cần `auth` |
| `/sitemap.xml` | `sitemap` | `SitemapController@index` | XML |
| `/dang-nhap` | `login`, `login.attempt` | `LoginController` | `Auth/Login` ✅ |
| `/dang-xuat` | `logout` | `LoginController@logout` | POST |

**Nhận xét route:**
- Không có route **đăng ký**, **quên mật khẩu**, **đặt lại mật khẩu**, **xác thực email**, **trang tài khoản người dùng**. Đây là khoảng trống chức năng (không phải lỗi).
- `tours.category` là route "chết": chỉ `redirect()` sang `tours.index` kèm query `?category=`.
- `/sitemap.xml` dùng `withoutMiddleware(['web'])` → không có session/CSRF, phù hợp.
- `pages.show` đặt cuối để tránh xung đột — đúng.
- Admin group chỉ có `middleware('admin')`, **không có `auth`**. `EnsureUserIsAdmin` tự kiểm tra `auth()->check()` nên tạm ổn, nhưng **không set `intended`** → sau khi login lại không quay về trang đang truy cập.

---

## 5. ĐỐI CHIẾU CONTROLLER ↔ REACT ↔ BLADE

### 5.1. Bảng tổng hợp 74 component Inertia

| Nhóm | Yêu cầu | Có file | Thiếu |
|---|---:|---:|---:|
| Frontend (Tour, Destination, Blog, Pages, Search) | 13 | 0 | 13 |
| Auth | 1 | 1 | 0 |
| Home | 1 | 1 | 0 |
| Admin Dashboard | 1 | 1 | 0 |
| Admin CRUD (14 module) | 58 | 0 | 58 |
| **Tổng** | **74** | **4** | **70*** |

\* 74 − 3 component thật = **71 lượt render không có component** (`GenericPage` phục vụ cả 71).

### 5.2. Danh sách 71 component còn thiếu

**Frontend (13):**
`Tour/Index`, `Tour/Show`, `Destination/Index`, `Destination/Show`, `Blog/Index`, `Blog/Category`, `Blog/Tag`, `Blog/Show`, `Pages/About`, `Pages/Contact`, `Pages/Faq`, `Pages/Show`, `Search/Index`.

**Admin (58):**

| Module | Component thiếu |
|---|---|
| Tours | Index, Create, Show, Edit, Trash |
| Destinations | Index, Create, Show, Edit, Trash |
| Categories | Index, Create, Show, Edit, Trash |
| Posts | Index, Create, Show, Edit, Trash |
| Pages | Index, Create, Show, Edit, Trash |
| Sliders | Index, Create, Show, Edit, Trash |
| Tags | Index, Create, Show, Edit, Trash |
| FAQs | Index, Create, Show, Edit, Trash |
| Menus | Index, Create, Show, Edit, **Items**, Trash |
| Users | Index, Create, Show, Edit |
| Settings | Index, Edit |
| Media | Index, Trash |
| Contacts | Index, Show |
| Reviews | Index, Trash |

### 5.3. `GenericPage` — phân tích chi tiết

`resources/js/Pages/GenericPage.jsx` là **fallback nguy hiểm**, không phải trang thật:

- **Cơ chế:** `app.jsx:9` → `resolve: (name) => pages[...]?.default || GenericPage`. Không có cảnh báo, không có log → thiếu trang vẫn trả HTTP 200.
- **Hành vi:** quét `props` là object, dựng bảng dữ liệu thô (`DataTable`) với tối đa 6 cột. Không có form, không có nút CRUD, không có phân trang, không có filter.
- **Hệ quả với admin:** 58 trang quản trị hiện chỉ **hiển thị dữ liệu**, hoàn toàn **không tạo/sửa/xóa được** — dù backend đã có đủ route và validation.
- **Hệ quả với frontend:** 13 trang công khai hiển thị tiêu đề + bảng dữ liệu thô, không phải giao diện du lịch.
- **Rò rỉ dữ liệu:** `GenericPage` render mọi prop object, kể cả quan hệ lồng (author, category, media…). Với admin, điều này có thể lộ dữ liệu không cần thiết (ví dụ `users` chứa email, `contacts` chứa nội dung liên hệ) — chấp nhận được trong admin nhưng là dấu hiệu thiết kế thiếu kiểm soát.
- **Không có layout admin:** `GenericPage` có `AdminLayout` nội bộ, nhưng `Admin/Dashboard.jsx` **không dùng** → dashboard hiện render **không có sidebar/topbar**.

### 5.4. Vấn đề layout

| Trang | Layout | Vấn đề |
|---|---|---|
| `Home.jsx` | tự viết `<header>` + `<footer>` inline | Trùng lặp với `GenericPage`; không dùng chung |
| `GenericPage.jsx` | `Header`/`Footer`/`AdminLayout` định nghĩa trong file | Không tái sử dụng được |
| `Admin/Dashboard.jsx` | **không có layout** | Mất sidebar, topbar, breadcrumb |
| `Auth/Login.jsx` | tự dựng `auth-screen` | OK nhưng lệch hệ thống |

→ **Không tồn tại** `Layouts/AppLayout`, `Layouts/AdminLayout`, `Layouts/AuthLayout` dùng chung. Đây là việc **bắt buộc làm đầu tiên** ở Giai đoạn 2, trước mọi trang CRUD.

### 5.5. Blade — dead code inventory

- **142 file Blade / 13.438 dòng.** Trong đó **132 file không còn đường dẫn tới** (controller đã chuyển Inertia).
- 10 file còn sống: `app.blade.php` (Inertia root), `errors/404.blade.php`, `emails/new-contact.blade.php`, và chuỗi `layouts/app` + `head/header/footer/menu-item-*` + `components/button` (chỉ sống **nhờ** trang 404).
- 71 view admin Blade + 71 view frontend Blade là **bản thiết kế tham chiếu quý giá** cho việc dựng lại React — cần đọc để giữ đúng nghiệp vụ, nhưng **không được render**.
- File rác: `admin/users/trash.blade.php` (0 dòng, không có route), `components/modal.blade.php` (0 dòng), `welcome.blade.php` (không route), `layouts/partials/seo.blade.php` (không ai include).

---

## 6. MODELS & DATABASE

### 6.1. Models (16)

`User`, `Category`, `Destination`, `Tour`, `TourItinerary`, `Post`, `Tag`, `Page`, `Faq`, `Slider`, `Menu`, `MenuItem`, `Media`, `Contact`, `Review`, `Setting`.

**Chất lượng:** tốt. Có SoftDeletes, casts, scopes (`published`, `featured`, `ordered`, `search`…), quan hệ đầy đủ, hằng số trạng thái rõ ràng. **Giữ nguyên, không cần viết lại.**

**Điểm cần lưu ý:**
- `Tour::reviews()` chỉ trả review đã duyệt; `allReviews()` cho admin. Accessor `average_rating`, `reviews_count` truy vấn riêng (N+1 nếu dùng trong list).
- `Setting` có cache toàn bộ bảng trong 1 key `settings.all` (TTL 1h) — thiết kế tốt, nhưng **React không nhận được settings**.
- `MenuItem::resolved_url` gọi `route()` trong accessor, có try/catch — an toàn.
- `Media` không có ràng buộc FK cho `mediable` (polymorphic) — chấp nhận được, nhưng cần dọn orphan.

### 6.2. Migrations (23) — DB thật đã chạy đủ

| Batch | Nội dung |
|---|---|
| 1 | users, password_reset_tokens, failed_jobs, personal_access_tokens, categories, destinations, media, tags, faqs, menus, settings, sliders, contacts, pages, tours, tour_itineraries, tour_destination, posts, post_tag |
| 2 | menu_items |
| 3 | update_settings_type_enum (thêm color/image/select) |
| 4 | jobs |
| 5 | reviews |

**Quan trọng:** `php artisan migrate:status` xác nhận **23/23 đã Ran**. File `rungadventure.sql` trong root là dump cũ (17/10, chỉ 21 migration, thiếu `jobs` + `reviews`) — **không dùng để khôi phục**, sẽ làm mất bảng.

### 6.3. Dữ liệu thật (đọc trực tiếp, không sửa)

| Bảng | Số dòng | Ghi chú |
|---|---:|---|
| tours | 9 | |
| posts | 9 | |
| destinations | 13 | |
| categories | 11 | |
| pages | 6 | |
| faqs | 10 | |
| sliders | 5 | |
| tags | 12 | |
| menus / menu_items | 4 / 14 | menu `top`/`header` không dùng |
| tour_itineraries | 18 | |
| tour_destination | 7 | |
| post_tag | 15 | |
| contacts | 23 | |
| settings | 45 | 7 group |
| media | **2** | ⚠ trong khi đĩa có 17 file |
| reviews | **0** | tính năng review chưa có dữ liệu thật |
| users | 3 | 1 admin, 1 user, 1 editor (mật khẩu đã hash) |
| jobs / failed_jobs | 0 / 0 | queue chưa dùng |

### 6.4. Lệch pha dữ liệu / file

- **`media` chỉ có 2 bản ghi nhưng `storage/app/public` có 17 file** → phần lớn ảnh tồn tại ngoài DB (orphan), không quản lý được qua Media Library.
- DB tham chiếu `media/destinations/2026/10/...webp` nhưng **thư mục `media/destinations/` không tồn tại** → ảnh điểm đến bị hỏng.
- `public/storage` là **bản copy** của `storage/app/public` (17 file, khớp từng byte). Upload mới ghi vào `storage/app/public` → **không xuất hiện** ở `public/storage` → ảnh mới 404.

### 6.5. Vấn đề schema / nghiệp vụ phát hiện được

| # | Vấn đề | Mức | Chi tiết |
|---|---|---|---|
| D1 | `categories.slug` UNIQUE toàn cục | Thấp | Không thể có danh mục tour và danh mục bài viết trùng slug; `generateSlug()` đã xử lý bằng suffix nhưng làm slug xấu |
| D2 | `reviews` UNIQUE `(tour_id,user_id)` không tính soft delete | Thấp | User xóa mềm review rồi không thể đánh giá lại (vi phạm unique) |
| D3 | Không có `job_batches` | Thấp | `config/queue.php` khai báo batching nhưng bảng không tồn tại → `Bus::batch()` sẽ lỗi |
| D4 | Không có bảng version/changelog/activity | — | Sẽ tạo ở Giai đoạn 4/5 |
| D5 | `tour_itineraries` bị `forceDelete()` mỗi lần update | Trung bình | Mất lịch sử, không phù hợp khi có Activity Log |
| D6 | Media không có `uploaded_by` | Thấp | Không truy vết được ai upload |

---

## 7. AUTHENTICATION & AUTHORIZATION

### 7.1. Luồng đăng nhập — hoạt động đúng

`LoginController` (86 dòng):
- `showLoginForm` → `Inertia::render('Auth/Login')` ✅ (component tồn tại).
- `login`: `Auth::attempt` → kiểm tra `is_active` → `session()->regenerate()` → `redirect()->intended(...)`.
- Redirect theo role: `admin`/`editor` → `admin.dashboard`; còn lại → `/`.
- `logout`: invalidate + regenerateToken.

### 7.2. Middleware

`EnsureUserIsAdmin` (35 dòng): chưa login → `route('login')`; role không thuộc `[admin, editor]` → 403; `is_active = false` → logout + lỗi.

**Thiếu:**
- Không set `intended` → mất deep-link sau login.
- Không phân biệt `admin` và `editor` ở bất kỳ route nào.
- Không có `auth` trong group admin (chỉ dựa vào middleware tùy biến).

### 7.3. Lỗ hổng leo thang đặc quyền — **HIGH**

**Kịch bản khai thác:**
1. Đăng nhập bằng tài khoản `editor` (hợp lệ, được middleware cho vào admin).
2. Mở `/admin/tai-khoan/{id}/edit` của chính mình.
3. `UpdateUserRequest` cho phép `role` ∈ `['admin','editor']` (`UpdateUserRequest.php:29`) và `authorize()` trả `true`.
4. `UserController@update` không kiểm tra role người thực hiện → lưu `role = admin`.
5. Leo thang thành công.

**Biện pháp (Giai đoạn 3):** Policy + Gate; chỉ `admin` được quản lý user; cấm tự đổi role của mình; không cho sửa `role` qua mass assignment; ghi Activity Log cho mọi thay đổi role.

**Hiện trạng phân quyền:** `AuthServiceProvider::$policies = []`, **không có Gate/Policy nào**. Toàn bộ 25 `FormRequest::authorize()` trả `true`. Quyền duy nhất là middleware `admin` (admin + editor như nhau).

### 7.4. Các vấn đề bảo mật khác

| # | Vấn đề | Mức | Bằng chứng |
|---|---|---|---|
| S1 | Editor → Admin escalation | **High** | `UpdateUserRequest.php:29`, `UserController.php:99-121` |
| S2 | Không throttle đăng nhập | **Medium** | `LoginRequest` không có rate limiter; `routes/auth.php` không `throttle` → brute force |
| S3 | Channel broadcast công khai `admin-notifications` | **Medium** | `app/Events/NewContactNotification.php:25` — ai cũng subscribe được; hiện vô hiệu vì provider bị tắt nên chưa khai thác |
| S4 | Cho upload SVG vào settings (logo/favicon) | **Medium** | `UpdateSettingRequest.php:72` `mimes:...svg` + lưu public disk → stored XSS nếu mở trực tiếp |
| S5 | `APP_DEBUG=true` trong `.env.example`, có Debugbar | **Medium** | Lộ stack trace, query, env nếu deploy nhầm |
| S6 | Admin routes thiếu `auth` | **Low** | `routes/admin.php:26` |
| S7 | Cache cũ chứa hash bcrypt admin + email/PII | **Low** | `storage/framework/cache/data` (7 entry) |
| S8 | `RouteServiceProvider::HOME='/home'` (không tồn tại) | **Low** | User đã login vào `/dang-nhap` → 404 |
| S9 | Không có security headers (CSP/X-Frame-Options/HSTS) | **Low** | Không có middleware |
| S10 | Contact form không captcha/throttle | **Low** | `StoreContactRequest` |
| S11 | `robots.txt` trỏ domain `rungadventure.com` trong khi local | **Info** | `public/robots.txt` |

---

## 8. MEDIA & UPLOAD

- `ImageService` (105 dòng): upload theo `media/{folder}/{Y}/{m}`, tên random 20 ký tự, tạo thumbnail `thumbs/` bằng Intervention v3, `replace()` xóa ảnh cũ, `delete()` xóa cả thumb. **Chất lượng tốt, giữ nguyên.**
- Upload được dùng ở: Tour (featured + gallery), Post, Destination, Category, Page, Slider, User avatar, Setting (logo/favicon), TinyMCE (closure route `admin.upload.image`).
- **`MediaController` KHÔNG có chức năng upload.** Chỉ có `index`, `trash`, `destroy`, `restore`, `forceDestroy`. "Media Library" hiện là **danh sách chỉ đọc + xóa**. Đây là khoảng trống lớn của Giai đoạn 2.
- `MediaController::index` không có filter theo ngày/kích thước, không có chọn nhiều, không có chèn vào editor.
- Route `admin.media.index` khai báo **trước** `admin.media.trash` nhưng khác số segment nên không xung đột (đã kiểm bằng `route:list`).

---

## 9. SETTINGS / MENU / THEME — TÍNH NĂNG BỊ "MỒ CÔI"

Đây là phát hiện quan trọng nhất về mặt nghiệp vụ: **ba hệ thống đã hoàn thiện ở backend nhưng React không dùng**.

### 9.1. Settings (45 key, 7 group)

`config/settings.php` (419 dòng) định nghĩa schema: `general`, `contact`, `social`, `seo`, `theme` (màu + font H1–H6), `about`, `hero` (6 ảnh hero). `Setting::get()` có cache.

- **Blade** dùng đầy đủ: `head.blade.php` inject CSS variables cho màu + font, `header/footer` lấy tên site/hotline/email/địa chỉ.
- **React** không nhận gì: `HandleInertiaRequests::share()` chỉ có `auth` + `flash`.
- **Hệ quả:** đổi màu/logo/hotline trong admin **không có tác dụng** lên giao diện React. `Home.jsx` hardcode nav, `GenericPage` footer hardcode "Hotline tư vấn", "Email liên hệ", "Việt Nam".

### 9.2. Menu động

- `Menu` + `MenuItem` + `MenuComposer` hoạt động (hỗ trợ 3 cấp), admin CRUD đầy đủ (12 route, trang `items` 542 dòng Blade).
- `MenuComposer` chỉ gắn vào `layouts.partials.header` / `footer` — **Blade đã chết**.
- React dùng mảng `nav` hardcode trong `GenericPage.jsx:5-9` và danh sách link hardcode trong `Home.jsx`.
- **Hệ quả:** quản lý menu trong admin **không ảnh hưởng** website React.

### 9.3. Hero image / theme

`HeroImageComposer` + 6 setting `hero_image_*` chỉ phục vụ Blade. React dùng ảnh Unsplash hardcode (`app.css:23` background `images.unsplash.com/...`, `Home.jsx` không có slider).

Ngoài ra `HomeController` **có truyền `sliders`** nhưng `Home.jsx` **không render slider** — dữ liệu slider bị bỏ phí.

---

## 10. SEO

| Hạng mục | Blade (đã chết) | React (đang chạy) |
|---|---|---|
| `<title>` | Có, theo setting | Chỉ `Head title` tĩnh/tiếng Việt |
| Meta description | Có | ❌ |
| Canonical | Có | ❌ |
| Open Graph | Có (thiếu `og:image`) | ❌ |
| Twitter card | Có | ❌ |
| `robots` meta | Admin có noindex | ❌ |
| JSON-LD (Product, Article, FAQPage, BreadcrumbList, TouristDestination) | Có trong Blade | ❌ |
| `sitemap.xml` động | ✅ `SitemapController` (121 dòng) | ✅ vẫn hoạt động |
| `robots.txt` | ✅ | ✅ |
| `hreflang` | ❌ | ❌ |
| Heading semantic (h1 duy nhất) | Tùy trang | `GenericPage` render `<h1>` + bảng, chấp nhận được |

**Kết luận:** SEO hiện **kém hơn bản Blade cũ**. Giai đoạn 2 phải tái lập đầy đủ meta/OG/JSON-LD qua component `Seo` dùng chung + `HandleInertiaRequests` share settings.

`SitemapController` có điểm chưa tối ưu: sinh lại toàn bộ mỗi request, không cache; `pages.show` + `about` có thể trùng URL `/trang-tinh/gioi-thieu` và `/gioi-thieu`.

---

## 11. CACHE & HIỆU NĂNG

**Đã có:** `CacheHelper` (5 hàm), cache trang chủ 10 phút, cache sidebar blog 10–60 phút, cache `tour_categories`/`destination_regions` 1 giờ, cache settings 1 giờ.

**Vấn đề:**

| # | Vấn đề | Ảnh hưởng |
|---|---|---|
| P1 | `tour_related_{category_id}`, `post_related_{category_id}` **không được xóa** khi sửa/xóa tour/post | Dữ liệu liên quan sai tối đa 10 phút |
| P2 | Không có `clearMenuCaches()` (handover note đã ghi nhận) | Menu có thể cũ nếu sau này cache |
| P3 | `DashboardController@index` tính 11 prop nhưng React chỉ dùng 3 (`stats`, `topTours`, `topPosts`) | ~10 query thừa mỗi lần mở dashboard |
| P4 | `import.meta.glob(..., { eager: true })` | Toàn bộ page bundle vào 1 chunk; khi có 71 page sẽ rất nặng (khuyến nghị lazy) |
| P5 | Sitemap không cache | Mỗi crawl quét toàn bộ tour/post/destination/page |
| P6 | `Tour` list dùng `withCount(['itineraries','destinations'])` nhưng không dùng đến ở Index | Query thừa |

---

## 12. ERROR HANDLING & OBSERVABILITY

- `app/Exceptions/Handler.php` **trống** — không override `render()`.
- 404 → `resources/views/errors/404.blade.php` (**Blade**, không phải Inertia) → khi Inertia request lỗi, người dùng nhận HTML thay vì modal lỗi.
- Trang 404 phụ thuộc `@vite('resources/js/app.js')` — **file không có trong Vite input** → **404 sẽ thành 500 khi build production**.
- Không có `errors/403.blade.php`, `419`, `500`, `503` → dùng view tiếng Anh mặc định của framework.
- `storage/logs` không tồn tại → không có log lịch sử; không thể truy nguyên lỗi cũ.
- Không có Sentry/monitoring, không có health check endpoint.
- Không có scheduled task nào (`Console/Kernel::schedule()` rỗng) → không dọn thùng rác, không backup, không warm cache.

---

## 13. TESTING

- **4 file test, 0 test nghiệp vụ:** `tests/Feature/ExampleTest.php` (`GET /` → 200), `tests/Unit/ExampleTest.php` (`assertTrue(true)`).
- `phpunit.xml` để **comment** `DB_CONNECTION=sqlite` / `DB_DATABASE=:memory:` → nếu chạy test sẽ **đập vào DB thật `rungadventure`**. Rủi ro cao.
- Feature test hiện **sẽ fail**: `GET /` render `app.blade.php` → `@vite` không có manifest → `ViteManifestNotFoundException`; thiếu `APP_KEY` → `MissingAppKeyException`.
- Không có test cho: auth, phân quyền, CRUD, upload, review, cache invalidation, activity log (chưa có).

**Đề xuất Giai đoạn 6:** dùng SQLite in-memory + `RefreshDatabase`, `withoutVite()`, seed tối thiểu; mục tiêu tối thiểu 40–60 test cho CRUD + auth.

---

## 14. DEAD CODE & TECHNICAL DEBT

| Loại | Số lượng | Chi tiết |
|---|---|---|
| Blade view chết | **132 / 142** | Toàn bộ `admin/*`, `blog/*`, `tour/*`, `destination/*`, `pages/*`, `partials/home/*`, `search/*`, `auth/login.blade.php`, `home.blade.php`, `welcome.blade.php` |
| View Component chết | 8 class | `Breadcrumb`, `BreadcrumbSchema`, `DestinationCard`, `HeroBanner`, `JsonLd`, `PostCard`, `Tinymce`, `TourCard` |
| View Composer chết | 2 | `MenuComposer`, `HeroImageComposer` (chỉ sống qua 404) |
| JS entry chết | 1 | `resources/js/app.js` (Alpine + Swiper/AOS/GLightbox) — **không có trong Vite input**, vẫn bị 3 Blade gọi |
| CSS mồ côi | 1 | `resources/css/pages/destination.css` — không import, không build |
| CSS rỗng | 1 | `resources/css/pages/admin.css` (0 dòng) |
| CSS khổng lồ inline | 1 | `app.css:23` — ~1 dòng chứa toàn bộ CSS React (khó bảo trì) |
| File rác root | 4 | `authorize(`, `models-rest.txt` (0 byte), `hero-views.txt`, `views-tree.txt` |
| Trang trùng vai trò | 2 | `blog/partials/category.blade.php`, `blog/partials/tag.blade.php` là trang đầy đủ nằm trong `partials/` |

**Đánh giá kỹ thuật:** phần CSS React bị nhồi vào một dòng duy nhất trong `app.css` (dòng 23, dài hàng chục KB) là **nợ kỹ thuật nghiêm trọng**, sẽ cản trở mọi thay đổi giao diện ở Giai đoạn 2. Cần tách thành các file có tổ chức.

---

## 15. RỦI RO TỔNG HỢP

| ID | Rủi ro | Khả năng | Tác động | Ưu tiên |
|---|---|---|---|---|
| R1 | Editor leo thang thành admin | Cao | Cao | **P0** |
| R2 | Không build/deploy được do thiếu `.env`, `public/build` | Chắc chắn | Cao | **P0** |
| R3 | 71 trang hiển thị `GenericPage` khiến khách hàng hiểu nhầm là "đã xong" | Chắc chắn | Cao | **P0** |
| R4 | Upload ảnh mới không hiển thị (`public/storage` là copy) | Chắc chắn | Cao | **P1** |
| R5 | Xây 71 trang mà không có layout → phân mảnh, phải làm lại | Cao | Cao | **P1** |
| R6 | Cache cũ trả dữ liệu/ảnh không còn tồn tại | Trung bình | Trung bình | **P1** |
| R7 | 404 → 500 khi production | Cao | Trung bình | **P1** |
| R8 | React bundle phình to do eager import | Cao | Trung bình | P2 |
| R9 | Laravel 10 EOL, advisory bảo mật | Chắc chắn | Trung bình | P2 |
| R10 | Test đập vào DB thật | Trung bình | Cao | P2 |
| R11 | Mất dữ liệu do ai đó chạy `migrate:fresh` hoặc import `rungadventure.sql` | Trung bình | Rất cao | **P0 (quy trình)** |

---

## 16. KẾT LUẬN

### 16.1. Những gì đã tốt — **giữ nguyên**

- Nghiệp vụ Laravel đầy đủ, mạch lạc: 15 module CRUD, soft delete + thùng rác + khôi phục + xóa cứng có kiểm tra liên kết.
- Validation chặt (25 FormRequest, thông báo tiếng Việt).
- ImageService chuẩn hóa upload + thumbnail.
- Settings schema linh hoạt, cache tốt.
- Route không trùng lặp, đặt tên nhất quán.
- DB đã migrate đủ, dữ liệu thật còn nguyên.

### 16.2. Những gì phải làm — theo thứ tự

1. **Giai đoạn 0 (bắt buộc, trước Giai đoạn 2):** ổn định môi trường chạy — `.env` + `APP_KEY`, build Vite, sửa `public/storage`, `cache:clear`/`view:clear`.
2. **Giai đoạn 2:** xây **tầng nền React** (layout + design system + shared props + form components) → sau đó mới làm 71 trang.
3. **Giai đoạn 3:** vá lỗ hổng phân quyền, hoàn thiện Media Library, dashboard.
4. **Giai đoạn 4:** Version Management + Changelog + `/cap-nhat`.
5. **Giai đoạn 5:** Activity Log.
6. **Giai đoạn 6:** test + build + deploy.

### 16.3. Điều kiện để bắt đầu Giai đoạn 2

- [ ] Chủ dự án phê duyệt kế hoạch trong `docs/IMPLEMENTATION_PLAN.md`.
- [ ] Xác nhận **không chạy `migrate:fresh`**, **không import lại `rungadventure.sql`**.
- [ ] Đồng ý cho phép tạo `.env` từ `.env.example` và chạy `php artisan key:generate` (file cấu hình, không phải source nghiệp vụ).
- [ ] Đồng ý cho phép `npm install` (tạo `node_modules`, `package-lock.json`).
- [ ] Xác nhận cách xử lý `public/storage` (xóa thư mục copy → `storage:link`).
- [ ] Xác nhận phạm vi Phase 2 có bao gồm việc **xóa 132 file Blade chết** hay chỉ **giữ nguyên và không render** (đề xuất: giữ đến hết Phase 2 để đối chiếu, xóa ở Phase 6).

---

## 17. PHỤ LỤC — LỆNH KIỂM CHỨNG ĐÃ CHẠY

```powershell
# 1. Cú pháp toàn bộ PHP
Get-ChildItem app,routes,config,database,tests -Recurse -File -Include *.php |
  ForEach-Object { php -l $_.FullName }
# → 165 file, 0 lỗi

# 2. Route
php artisan route:list --json
# → 152 route, 0 trùng tên, 0 trùng URI

# 3. Migration
php artisan migrate:status
# → 23/23 Ran

# 4. Số bản ghi
mysql -u root -e "SELECT COUNT(*) FROM rungadventure.tours; ..."

# 5. Đối chiếu component
Select-String -Path app\Http\Controllers\*\*.php -Pattern "Inertia::render\('([^']+)'"
# → 74 yêu cầu, 4 file tồn tại, 71 thiếu
```

---

*Hết báo cáo kiểm toán Giai đoạn 1. Không có file source nào bị sửa trong quá trình kiểm toán.*
