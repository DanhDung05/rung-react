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
