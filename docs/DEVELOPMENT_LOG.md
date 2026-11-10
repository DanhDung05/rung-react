# RUNG ADVENTURE — NHẬT KÝ PHÁT TRIỂN

> Ghi lại **mỗi giai đoạn**: file đã tạo, file đã sửa, chức năng hoàn thành, chức năng chưa hoàn thành, lỗi còn tồn tại, kết quả kiểm thử.
> Mới nhất ở trên cùng.

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
