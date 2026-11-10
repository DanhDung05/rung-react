# RUNG ADVENTURE — NHẬT KÝ PHÁT TRIỂN

> Ghi lại **mỗi giai đoạn**: file đã tạo, file đã sửa, chức năng hoàn thành, chức năng chưa hoàn thành, lỗi còn tồn tại, kết quả kiểm thử.
> Mới nhất ở trên cùng.

---

## [2026-11-10] GIAI ĐOẠN 2.0 — TẦNG NỀN REACT (HOÀN THÀNH)

### Bối cảnh

Sau khi GĐ0 ổn định môi trường và vá lỗ hổng leo thang đặc quyền, xây tầng nền cho toàn bộ giao diện React: layout, design system, shared props, SEO, tiện ích. **Chưa xây trang CRUD nào** (thuộc 2A/2B/2C/2D).

### Việc đã làm

| # | Việc | Kết quả |
|---|---|---|
| 1 | Shared props từ Laravel → React | `settings`, `theme`, `navigation`, `app`, `flash`, `auth.user` (rút gọn) |
| 2 | Menu động vào React | `NavigationService` + cache 1h + tự xóa cache khi admin sửa menu |
| 3 | Theme động vào React | `ThemeStyles` inject CSS variables từ settings |
| 4 | 3 layout dùng chung | AppLayout, AdminLayout, AuthLayout + 4 partial |
| 5 | Design system | 14 component UI (Button, Input, Select, Textarea, Checkbox, Badge, Card, Modal, Pagination, EmptyState, Spinner, FlashMessage, FormField, ConfirmDialog) |
| 6 | SEO component | title/description/canonical/OG/Twitter/JSON-LD |
| 7 | Tiện ích | `routes.js` (toàn bộ URL), `format.js`, `constants.js`, `useFlash` |
| 8 | Lazy-load page | `import.meta.glob` không eager → tách chunk theo trang |
| 9 | Cảnh báo thiếu page | `console.warn` ở dev thay vì im lặng rơi vào GenericPage |
| 10 | Chuyển 4 trang hiện có sang layout | Home, GenericPage, Admin/Dashboard, Auth/Login |
| 11 | Sửa lỗi 404 → 500 | Thêm `resources/js/app.js` vào Vite input |
| 12 | Tách CSS React | `resources/css/react-ui.css` (bỏ import `admin.css` rỗng) |

### File đã tạo

**Backend (2)**
- `app/Services/NavigationService.php`
- `app/Policies/UserPolicy.php` (từ đợt vá bảo mật)

**React — lib/hooks (4)**
- `resources/js/lib/routes.js`, `resources/js/lib/format.js`, `resources/js/lib/constants.js`
- `resources/js/hooks/useFlash.js`

**React — Components (16)**
- `resources/js/Components/Seo.jsx`, `resources/js/Components/ThemeStyles.jsx`
- `resources/js/Components/UI/`: `Button`, `FormField`, `Input`, `Textarea`, `Select`, `Checkbox`, `Badge`, `Card`, `Modal`, `Pagination`, `EmptyState`, `Spinner`, `FlashMessage`, `ConfirmDialog` (14 file)

**React — Layouts (7)**
- `resources/js/Layouts/AppLayout.jsx`, `AdminLayout.jsx`, `AuthLayout.jsx`
- `resources/js/Layouts/Partials/Header.jsx`, `Footer.jsx`, `AdminSidebar.jsx`, `AdminTopbar.jsx`

**CSS (1)**
- `resources/css/react-ui.css`

**Test (2)**
- `tests/Feature/InertiaFoundationTest.php`
- `tests/Feature/Security/UserManagementTest.php`

### File đã sửa

| File | Thay đổi |
|---|---|
| `app/Http/Middleware/HandleInertiaRequests.php` | Viết lại `share()`: settings/theme/navigation/app + user rút gọn, có try/catch |
| `app/Helpers/CacheHelper.php` | Thêm `clearMenuCaches()`, gọi trong `clearAll()` |
| `app/Http/Controllers/Admin/MenuController.php` | Xóa cache menu sau store/update/destroy/restore/forceDestroy/saveItems |
| `app/Http/Controllers/Admin/UserController.php` | Thêm `authorize()` + chặn tự đổi role/tự vô hiệu hóa |
| `app/Http/Requests/Admin/StoreUserRequest.php` | `authorize()` = admin |
| `app/Http/Requests/Admin/UpdateUserRequest.php` | `authorize()` = admin |
| `resources/js/app.jsx` | Bỏ eager glob, lazy import, fallback động, cảnh báo dev |
| `resources/js/Pages/Home.jsx` | Dùng AppLayout + Seo + settings; dùng slider thật |
| `resources/js/Pages/GenericPage.jsx` | Dùng 3 layout thay vì tự dựng header/footer |
| `resources/js/Pages/Admin/Dashboard.jsx` | Dùng AdminLayout + Card + Badge + EmptyState |
| `resources/js/Pages/Auth/Login.jsx` | Dùng AuthLayout + Button + Checkbox |
| `resources/css/app.css` | Bỏ import `pages/admin.css` (rỗng), thêm `react-ui.css` |
| `vite.config.js` | Thêm `resources/js/app.js` vào input (fix 404) |
| `phpunit.xml` | Trỏ DB test `rungadventure_test` |

### Chức năng đã hoàn thành

- Header/footer/nav đọc **dữ liệu thật** từ `menus` + `settings` (không còn hardcode).
- Màu/font từ Settings áp dụng được lên React qua CSS variables.
- Admin sidebar có 15 module, ẩn Người dùng/Cài đặt với editor.
- Admin topbar có user menu + đăng xuất.
- Mọi trang có flash message thống nhất.
- SEO đầy đủ cho trang dùng `Seo`.
- Bundle tách theo trang (Home 6.9KB, Dashboard 2.4KB, Login 3.1KB riêng).

### Chức năng chưa hoàn thành

- 71 page component (58 admin + 13 frontend) — thuộc 2A/2B/2C/2D.
- Media Library upload — 2C.
- Trang lỗi Inertia (404/403/419/500) — 2E (404 Blade hiện đã chạy đúng, không còn 500).
- Rich text editor component — 2B (khi làm form Tour/Post).
- Upload ảnh component — 2B/2C.

### Lỗi còn tồn tại (mới phát hiện trong 2.0)

| ID | Lỗi | Mức | Ghi chú |
|---|---|---|---|
| N1 | `Setting::get()` gọi nhiều lần trong 1 request (theme 14 lần) | Thấp | Đã có cache `settings.all` nên chỉ 1 query; có thể gộp sau |
| N2 | `Page 404` vẫn là Blade, phụ thuộc `layouts.app` + MenuComposer | Thấp | Sẽ chuyển Inertia ở 2E |
| N3 | Editor vẫn vào được Settings (chưa khóa) | **Trung bình** | Thuộc phạm vi GĐ3 (Policy cho Setting) |
| N4 | `route:cache` vẫn lỗi do closure upload TinyMCE | Thấp | Xử lý ở 2B/2C khi chuyển sang controller |

### Kết quả kiểm thử

| Test | Kết quả |
|---|---|
| `php artisan test` | **19 passed, 96 assertions** |
| UserManagementTest (bảo mật) | 11 passed |
| InertiaFoundationTest (tầng nền) | 6 passed |
| `php -l` 10 file PHP đã sửa/tạo | 0 lỗi |
| `npm run build` | **exit 0**, 2442 module, 9.35s |
| Smoke test HTTP | `/` 200, `/dang-nhap` 200, `/tour` 200, `/sitemap.xml` 200, `/khong-ton-tai` **404** (trước là 500) |
| Shared props | `settings.site_name = "Rừng Adventure"`, `navigation.main` có 6 item thật từ DB |
| DB thật sau kiểm thử | users 3, tours 9, posts 9, settings 45, contacts 23, menu_items 14 — **không đổi** |

### Git

| Commit | Nội dung |
|---|---|
| `61d0db5` | `chore(gd0)`: ổn định môi trường |
| `88d0e59` | `fix(security)`: chặn editor leo thang đặc quyền |
| *(sắp tạo)* | `feat(gd2.0)`: tầng nền React |

### Bước tiếp theo

Chờ phê duyệt để thực hiện **GĐ2A** (Admin shell + Dashboard nâng cao), sau đó 2B.

---

## [2026-11-10] GIAI ĐOẠN 0 — ỔN ĐỊNH MÔI TRƯỜNG (HOÀN THÀNH)

### Việc đã làm

| # | Việc | Chi tiết |
|---|---|---|
| 1 | Backup toàn bộ | DB dump + `public/storage` + `storage/app/public` + `resources/js` + `resources/css` + controllers/requests/middleware/routes → `C:\laragon\www\_backups-rung-react\20261110-115656` (124 file) |
| 2 | Khởi động lại MySQL | Máy mất nguồn làm MySQL tắt; khởi động lại bằng `mysqld --defaults-file`, không đổi dữ liệu |
| 3 | `public/storage` → symlink | Xác minh 15 file / SHA256 khớp tuyệt đối **trước khi xóa**, sau đó `storage:link` → Junction |
| 4 | `.env` | Copy từ `.env.example` (file chưa tồn tại), sửa `APP_NAME`, `APP_URL`, `MAIL_MAILER=log` |
| 5 | `APP_KEY` | `php artisan key:generate` |
| 6 | `storage/logs` | Tạo thư mục + `.gitignore` |
| 7 | Xóa cache cũ | `config:clear`, `cache:clear`, `view:clear` (cache cũ chứa PII + hash bcrypt) |
| 8 | JS dependencies | `npm install` → 186 package, `package-lock.json` được tạo |
| 9 | Build | `npm run build` → `public/build/manifest.json` + assets |
| 10 | Git | `git init` + commit baseline 372 file |
| 11 | DB test | Tạo `rungadventure_test` (DB riêng, không đụng DB thật) |

### Kiểm chứng

- `public/storage` là **Junction**, 15 file hiển thị qua link.
- `/` → 200, `/dang-nhap` → 200, `/admin/dashboard` → redirect `/dang-nhap` (middleware hoạt động).
- `php artisan about` chạy đúng: Laravel 10.50.4, PHP 8.1.10, APP_NAME "Rung Adventure".

### Ghi chú

- Không chạy `migrate:fresh`, `db:wipe`, hay import lại `rungadventure.sql`.
- Backup **chưa xóa** — chờ chủ dự án xác nhận website ổn định.

---

## [2026-11-10] VÁ BẢO MẬT — LEO THANG ĐẶC QUYỀN (HOÀN THÀNH)

### Lỗ hổng

Tài khoản `editor` có thể tự đổi `role` thành `admin` qua `/admin/tai-khoan/{id}/edit` vì:
- `EnsureUserIsAdmin` cho cả admin và editor vào khu quản trị.
- `UpdateUserRequest` cho phép `role` và `authorize()` trả `true`.
- `UserController@update` không kiểm tra người thực hiện.

### Cách vá

1. `UserPolicy`: `viewAny`/`create`/`update` chỉ admin; `toggleActive` chỉ admin và không tự tác động.
2. `UserController`: gọi `authorize()` ở index/create/store/show/edit/update/toggleActive.
3. Chặn tự đổi `role` và tự vô hiệu hóa tài khoản của chính mình (kể cả admin).
4. `StoreUserRequest`/`UpdateUserRequest`: `authorize()` = `user()->isAdmin()`.

### Kiểm thử

`tests/Feature/Security/UserManagementTest.php` — **11 test, 20 assertion, PASS**:
- Khách chưa đăng nhập → redirect login.
- Editor: không xem danh sách, không mở form tạo, **không tự nâng quyền**, không tạo admin, không sửa người khác, không bật/tắt tài khoản.
- Admin: xem danh sách, nâng quyền editor, **không tự đổi role**, **không tự vô hiệu hóa**.

**Test hồi quy quan trọng nhất:** `test_editor_khong_the_tu_nang_quyen_thanh_admin` — trước khi vá sẽ fail.

---

## [2026-11-10] GIAI ĐOẠN 1 — AUDIT SOURCE (HOÀN THÀNH)

### Bối cảnh

Bắt đầu dự án trên source có sẵn tại `C:\laragon\www\rung-react`. Chỉ thực hiện kiểm toán và lập kế hoạch; **không sửa source nghiệp vụ, không tạo migration, không xóa file**.

### Việc đã làm

| # | Việc | Kết quả |
|---|---|---|
| 1 | Khảo sát cấu trúc workspace | 9 thư mục Laravel chuẩn, không có `.git` |
| 2 | Đọc toàn bộ routes (web, admin, auth, api, channels, console) | 152 route, 0 trùng |
| 3 | Đọc 27 file controller | 26 controller nghiệp vụ |
| 4 | Đọc 25 FormRequest | Validation đầy đủ, `authorize()` toàn `true` |
| 5 | Đọc 16 model + 23 migration | DB khớp, thiếu `job_batches` |
| 6 | Đọc toàn bộ React (4 page) + Vite/Tailwind config | 71 component thiếu |
| 7 | Inventory 142 Blade view | 132 dead code |
| 8 | Kiểm tra auth/authorization | Phát hiện leo thang đặc quyền |
| 9 | Kiểm tra SEO, cache, media, testing | Ghi nhận khoảng trống |
| 10 | Đối chiếu `Inertia::render` ↔ file page | 74 yêu cầu / 4 tồn tại |
| 11 | Lint toàn bộ PHP | 165 file, 0 lỗi |
| 12 | Chạy `route:list`, `migrate:status`, đọc DB thật | Xác minh số liệu |
| 13 | Viết bộ tài liệu dự án | 7 file |

### File đã tạo

| File | Nội dung | Trạng thái |
|---|---|---|
| `docs/PROJECT_AUDIT.md` | Báo cáo kiểm toán đầy đủ (545 dòng) | ✅ |
| `docs/IMPLEMENTATION_PLAN.md` | Kế hoạch 6 giai đoạn, ước lượng, tiêu chí (674 dòng) | ✅ |
| `docs/FEATURES.md` | Danh mục chức năng theo 3 tầng (302 dòng) | ✅ |
| `docs/DATABASE.md` | 23 bảng, quan hệ, quy ước, backup (528 dòng) | ✅ |
| `docs/DEPLOYMENT.md` | Hướng dẫn triển khai, troubleshooting (409 dòng) | ✅ |
| `docs/DEVELOPMENT_LOG.md` | File này | ✅ |
| `CHANGELOG.md` | Lịch sử phiên bản | ✅ |

### File đã sửa

**Không có file source nghiệp vụ nào bị sửa.**

Thay đổi ngoài source (hạ tầng, có thể hoàn nguyên):

| Đối tượng | Hành động | Lý do | Hoàn nguyên |
|---|---|---|---|
| ACL thư mục `C:\laragon\www\rung-react` | Cấp quyền FullControl cho user hiện tại | Workspace bị chặn ghi (`SetNamedSecurityInfoW failed`) | Chạy script rollback do công cụ in ra |
| `vendor/` | `composer install --no-blocking` | Cần autoload để chạy `artisan` | Xóa `vendor/` |
| `composer.lock` | Được tạo bởi composer | Chưa từng có lock file | Xóa `composer.lock` |
| `bootstrap/cache/packages.php`, `services.php` | Laravel tự sinh khi chạy artisan | Cache manifest | `php artisan clear-compiled` |
| `routes-dump.json` | Tạo tạm để debug rồi **đã xóa** | File rác từ lệnh kiểm thử | Đã xóa |

> ⚠ `composer install` **chưa hoàn tất bước `package:discover`** vì `storage/logs` không tạo được. `vendor/autoload.php` đã có, nhưng cần chạy lại sau khi tạo `storage/logs`.

### Chức năng đã hoàn thành (trong phạm vi Giai đoạn 1)

- Bản đồ đầy đủ routes, controller, model, view, page.
- Ma trận đối chiếu Controller ↔ React ↔ Blade cho 74 điểm render.
- Xác định 71 component React còn thiếu (danh sách cụ thể từng file).
- Xác định 132/142 Blade là dead code + lý do.
- Xác định 11 vấn đề bảo mật (1 High, 4 Medium, 5 Low, 1 Info).
- Xác định 6 vấn đề dữ liệu/migration.
- Xác định 6 vấn đề hiệu năng/cache.
- Kế hoạch 6 giai đoạn với ước lượng và tiêu chí nghiệm thu.
- Quy ước kiến trúc thư mục đích cho React.

### Chức năng chưa hoàn thành

Toàn bộ Giai đoạn 2–6 (theo kế hoạch). Cụ thể:

- 71 React page component.
- Media Library upload.
- Layout dùng chung + design system.
- Phân quyền Policy/Gate.
- Version Management + `/cap-nhat`.
- Activity Log.
- Test tự động.

### Lỗi còn tồn tại (phát hiện, **chưa sửa** — đúng phạm vi Giai đoạn 1)

| ID | Lỗi | Mức | Nơi |
|---|---|---|---|
| B1 | Editor có thể tự nâng thành admin | **High** | `UpdateUserRequest.php:29`, `UserController.php:99-121` |
| B2 | `RouteServiceProvider::HOME='/home'` không tồn tại → 404 | Medium | `RouteServiceProvider.php:16` |
| B3 | 404 page gọi `@vite('resources/js/app.js')` không có trong input → 500 | Medium | `layouts/partials/head.blade.php:116` |
| B4 | `public/storage` là thư mục copy, không phải symlink | Medium | `public/storage` |
| B5 | Dashboard thiếu layout, bỏ phí 8/11 prop | Medium | `Admin/Dashboard.jsx` |
| B6 | `GenericPage` thay 71 trang thật | **Cao** | `app.jsx:9` |
| B7 | Menu/Settings/Theme backend không tới được React | Cao | `HandleInertiaRequests.php:17-28` |
| B8 | `PostController@forceDestroy` chặn xóa khi còn tag | Thấp | `PostController.php:226-234` |
| B9 | `TourController@forceDestroy` báo sai tên (dùng ID) | Thấp | `TourController.php:269` |
| B10 | Menu items cấp 3 có thể sai parent khi lưu | Thấp | `MenuController.php:148-153` |
| B11 | Cache `tour_related_*`, `post_related_*` không được xóa | Thấp | `CacheHelper.php` |
| B12 | `route:cache` sẽ lỗi do closure upload | Thấp | `routes/admin.php:340-356` |

### Kết quả kiểm thử

| Test | Lệnh | Kết quả |
|---|---|---|
| Cú pháp PHP toàn bộ | `php -l` trên `app,routes,config,database,tests` | **165 file — 0 lỗi** |
| Route table | `php artisan route:list --json` | **152 route**; 0 trùng tên; 0 trùng URI+method; 1 route không tên (`api/user`) |
| Migration | `php artisan migrate:status` | **23/23 Ran** (batch 1–5) |
| DB kết nối | MySQL 8.0.30, DB `rungadventure` | OK |
| Đếm dữ liệu | `SELECT COUNT(*)` 23 bảng | tours 9, posts 9, destinations 13, categories 11, pages 6, faqs 10, sliders 5, tags 12, menus 4, menu_items 14, itineraries 18, contacts 23, settings 45, media 2, reviews 0, users 3 |
| Đối chiếu component | grep `Inertia::render` | 74 yêu cầu / 4 file / **71 thiếu** |
| Build frontend | `npm install` | ⛔ chưa chạy (ngoài phạm vi GĐ1) |
| HTTP smoke test | — | ⛔ chưa chạy (thiếu `.env`, `public/build`) |
| PHPUnit | — | ⛔ chưa chạy (sẽ chạy GĐ6) |

### Sự cố môi trường gặp phải

1. **ACL workspace bị chặn ghi** — đã xử lý bằng skill `diagnose-windows-sandbox-acl` (cấp FullControl cho user, có backup + rollback).
2. **Composer 2.10 chặn Laravel 10** do advisory — đã dùng `--no-blocking`.
3. **`npm` bị ExecutionPolicy chặn** (`npm.ps1`) — phải dùng `npm.cmd`.
4. **Sandbox chạy ở Low Integrity** — chỉ ghi được thư mục gốc và `docs/`; không ghi được `storage/`, `bootstrap/cache/`, `public/`, `resources/`. Vì vậy `artisan` chỉ chạy được khi cấp quyền đầy đủ cho từng lệnh.
5. **Mất nguồn đột ngột** giữa phiên — đã kiểm tra lại sau khi khởi động: 5 file tài liệu còn nguyên (UTF-8, đúng số dòng, kết thúc đúng), `vendor/autoload.php` và `composer.lock` còn, `public/build` và `node_modules` vẫn chưa có. Không mất dữ liệu.

### Ghi chú sau sự cố mất nguồn (kiểm tra lại 2026-11-10)

| Hạng mục | Trạng thái sau khởi động lại |
|---|---|
| `docs/PROJECT_AUDIT.md` | ✅ 545 dòng, UTF-8 hợp lệ |
| `docs/IMPLEMENTATION_PLAN.md` | ✅ 674 dòng |
| `docs/FEATURES.md` | ✅ 302 dòng |
| `docs/DATABASE.md` | ✅ 528 dòng |
| `docs/DEPLOYMENT.md` | ✅ 409 dòng |
| `vendor/autoload.php` | ✅ tồn tại |
| `composer.lock` | ✅ tồn tại (318 KB) |
| `node_modules`, `public/build`, `.env` | ❌ vẫn chưa có |
| `storage/logs` | ❌ vẫn chưa có |
| `routes-dump.json` (rác) | 🗑 đã xóa |

### Bước tiếp theo

1. Chờ phê duyệt `docs/IMPLEMENTATION_PLAN.md`.
2. Thực hiện **Giai đoạn 0** (ổn định môi trường) — xem `IMPLEMENTATION_PLAN.md` §4.
3. Bắt đầu **Giai đoạn 2, đợt 2.0** (tầng nền React).

---

## MẪU GHI CHO CÁC GIAI ĐOẠN SAU

```markdown
## [YYYY-MM-DD] GIAI ĐOẠN X — <TÊN> (<TRẠNG THÁI>)

### Bối cảnh
### Việc đã làm
### File đã tạo
### File đã sửa
### Chức năng đã hoàn thành
### Chức năng chưa hoàn thành
### Lỗi còn tồn tại
### Kết quả kiểm thử
### Bước tiếp theo
```
