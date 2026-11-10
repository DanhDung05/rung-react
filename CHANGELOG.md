# Changelog

Mọi thay đổi đáng chú ý của dự án Rung Adventure được ghi tại đây.

Định dạng dựa trên [Keep a Changelog](https://keepachangelog.com/vi/1.1.0/),
phiên bản theo [Semantic Versioning](https://semver.org/lang/vi/).

> **Quy ước ghi:**
> - `Added` — chức năng mới
> - `Changed` — thay đổi hành vi
> - `Deprecated` — sắp bị loại bỏ
> - `Removed` — đã loại bỏ
> - `Fixed` — sửa lỗi
> - `Security` — vá bảo mật
>
> Mỗi mục ghi kèm module và file chính. Chi tiết kỹ thuật xem `docs/DEVELOPMENT_LOG.md`.

---

## [Unreleased]

### Planned — Giai đoạn 2 (React hoàn thiện)

- Tầng nền React: `AppLayout`, `AdminLayout`, `AuthLayout`, design system, shared props (settings/menu/theme).
- 71 page component còn thiếu (58 admin + 13 frontend).
- Media Library có upload, chọn nhiều, MediaPicker.
- Trang lỗi Inertia (404/403/419/500).
- Loại bỏ `GenericPage` fallback.

### Planned — Giai đoạn 3–6

- Phân quyền Policy/Gate; vá lỗi leo thang đặc quyền của `editor`.
- Dashboard nâng cao.
- Version Management, Changelog, What's New, `/cap-nhat`.
- Activity Log / Audit Log.
- Bộ test tự động + build production.

---

## [0.2.0] — 2026-11-10

**Giai đoạn 0 + vá bảo mật + Giai đoạn 2.0 (tầng nền React).**

### Added

- **Shared props Inertia**: `settings` (7 group), `theme` (màu + font H1–H6), `navigation` (menu main/footer), `app`, `flash`, `auth.user` rút gọn — `HandleInertiaRequests`.
- **`App\Services\NavigationService`**: dựng cây menu 3 cấp, cache 1 giờ, tự xóa cache khi admin sửa menu.
- **3 layout dùng chung**: `AppLayout` (công khai), `AdminLayout` (sidebar + topbar), `AuthLayout`.
- **4 partial layout**: `Header`, `Footer`, `AdminSidebar`, `AdminTopbar`.
- **14 UI component**: Button, FormField, Input, Textarea, Select, Checkbox, Badge, Card, Modal, Pagination, EmptyState, Spinner, FlashMessage, ConfirmDialog.
- **`Seo` component**: title, description, canonical, Open Graph, Twitter card, JSON-LD.
- **`ThemeStyles`**: inject màu/font từ Settings vào CSS variables → theme trong admin có tác dụng thật.
- **Thư viện tiện ích**: `lib/routes.js` (toàn bộ URL), `lib/format.js`, `lib/constants.js`, `hooks/useFlash.js`.
- **`resources/css/react-ui.css`**: CSS cho design system, tách khỏi khối inline cũ.
- **Test**: `InertiaFoundationTest` (6 test), `UserManagementTest` (11 test).

### Changed

- `resources/js/app.jsx`: bỏ `eager: true` → lazy-load từng page, tách bundle theo trang; cảnh báo ở dev khi thiếu page component.
- `Home.jsx`, `GenericPage.jsx`, `Admin/Dashboard.jsx`, `Auth/Login.jsx` chuyển sang dùng layout chung; bỏ header/footer/sidebar tự dựng.
- `Home.jsx` dùng slider thật từ DB thay vì ảnh nền hardcode.
- `CacheHelper` bổ sung `clearMenuCaches()`.
- `phpunit.xml`: trỏ DB test riêng `rungadventure_test`.

### Fixed

- **404 trả 500** khi build production: thêm `resources/js/app.js` vào Vite input.
- **`GenericPage` fallback im lặng**: nay có cảnh báo rõ trong môi trường dev.
- **Menu / Settings / Theme không tới được React**: đã chia sẻ qua Inertia.
- **`admin.css` rỗng gây warning build**: bỏ import, thay bằng `react-ui.css`.
- **Môi trường không chạy được**: `.env` + `APP_KEY` + `storage/logs` + `npm install` + build.
- **`public/storage` là thư mục copy** → đã chuyển thành symlink sau khi xác minh SHA256.

### Security

- **Vá lỗ hổng High**: editor tự nâng quyền thành admin.
  - `App\Policies\UserPolicy` — chỉ admin quản lý người dùng.
  - `UserController` gọi `authorize()` ở 7 action.
  - Chặn tự đổi `role` và tự vô hiệu hóa tài khoản.
  - `StoreUserRequest`/`UpdateUserRequest` chỉ cho admin.
- **Xóa cache cũ** chứa hash bcrypt admin + email/PII.

### Notes

- **Không sửa nghiệp vụ backend** nào ngoài phần phân quyền người dùng.
- **Không chạy `migrate:fresh`**, không import lại `rungadventure.sql`.
- DB thật sau toàn bộ thay đổi: users 3, tours 9, posts 9, settings 45, contacts 23, menu_items 14 — **không đổi**.
- Backup tại `C:\laragon\www\_backups-rung-react\20261110-115656` — **chưa xóa**, chờ xác nhận website ổn định.
- 71 page component vẫn còn thiếu (thuộc 2A/2B/2C/2D).

---

## [0.1.0] — 2026-11-10

**Giai đoạn 1 — Audit Source.** Bản phát hành **chỉ gồm tài liệu**, không thay đổi source nghiệp vụ.

### Added

- `docs/PROJECT_AUDIT.md` — báo cáo kiểm toán toàn bộ source:
  - Hiện trạng stack, môi trường, kiến trúc.
  - 152 route (120 admin + 22 frontend), 0 trùng lặp.
  - Ma trận đối chiếu 74 `Inertia::render()` ↔ 4 page component ↔ 142 Blade view.
  - Danh sách 71 component React còn thiếu.
  - Phân tích Models, 23 migration, dữ liệu thật 23 bảng.
  - 11 vấn đề bảo mật, 6 vấn đề dữ liệu, 6 vấn đề hiệu năng.
  - Rủi ro tổng hợp và điều kiện bắt đầu Giai đoạn 2.
- `docs/IMPLEMENTATION_PLAN.md` — kế hoạch 6 giai đoạn:
  - Giai đoạn 0 (ổn định môi trường), Giai đoạn 2 (React), 3 (CMS nâng cao), 4 (Version), 5 (Activity Log), 6 (Test & Deploy).
  - Chi tiết từng đợt, file cần tạo, tiêu chí nghiệm thu, ước lượng 68–87 dev-day.
  - Kiến trúc thư mục đích, quy ước code, rủi ro và biện pháp.
- `docs/FEATURES.md` — danh mục 32 chức năng theo 3 tầng (backend / admin UI / frontend UI).
- `docs/DATABASE.md` — 23 bảng, quan hệ, ràng buộc, quy ước bảng mới, quy tắc backup.
- `docs/DEPLOYMENT.md` — yêu cầu hệ thống, cài đặt, build, web server, queue, checklist, troubleshooting, rollback.
- `docs/DEVELOPMENT_LOG.md` — nhật ký Giai đoạn 1.
- `CHANGELOG.md` — file này.

### Changed

- Không thay đổi source nghiệp vụ.

### Fixed

- Không sửa lỗi nào trong Giai đoạn 1 (theo phạm vi). 12 lỗi đã được ghi nhận trong `docs/DEVELOPMENT_LOG.md`.

### Security

- Ghi nhận **1 lỗ hổng High**: tài khoản `editor` có thể tự nâng thành `admin` qua `/admin/tai-khoan/{id}/edit`.
- Ghi nhận 4 vấn đề Medium: không throttle đăng nhập, broadcast channel công khai, cho upload SVG, `APP_DEBUG=true` trong `.env.example`.
- Ghi nhận 5 vấn đề Low: admin route thiếu `auth`, cache cũ chứa hash/PII, `HOME` sai, thiếu security headers, contact form không captcha.
- **Chưa vá** — thuộc Giai đoạn 3.

### Notes

- Source hiện **chưa chạy được** ở môi trường sạch: thiếu `.env`, `public/build`, `node_modules`; `public/storage` là thư mục copy thay vì symlink.
- DB thật đã migrate đủ 23/23. **Không** import `rungadventure.sql` (dump cũ, thiếu bảng `jobs` và `reviews`).
- **Không** chạy `migrate:fresh`.

---

## Mốc tham chiếu của source tại thời điểm audit

| Hạng mục | Giá trị |
|---|---|
| Laravel | 10.50.4 |
| Inertia Laravel | 2.0.28 |
| React / @inertiajs/react | 18.3.1 / 2.0 |
| Vite / Tailwind | 5 / 3.4 |
| PHP | 8.1.10 |
| Migration | 23/23 |
| Route | 152 |
| Controller / Model / FormRequest | 26 / 16 / 25 |
| Blade view | 142 (132 dead code) |
| React page | 4 (3 thật + GenericPage) |
| Test nghiệp vụ | 0 |
