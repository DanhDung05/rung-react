# LỊCH SỬ CẬP NHẬT (RELEASES)

Cập nhật: 2026-11-16 · v2.3.2.

## 1. Nguồn dữ liệu duy nhất

Trang **Admin → Lịch sử cập nhật** (`/admin/lich-su-cap-nhat`) đọc trực tiếp `CHANGELOG.md` qua `App\Services\ChangelogService`.

- Không nhập liệu trong Admin; không có nút thêm/sửa/xóa.
- Bảng `releases` và migration cũ **được giữ** nhưng không còn dùng để hiển thị; dữ liệu cũ không xuất hiện.
- Chỉ admin/editor (middleware `admin`) truy cập.
- Version hiện tại đọc từ `VERSION`; badge “Hiện tại” so khớp chính xác.

## 2. Định dạng CHANGELOG

Mục mới dùng Keep a Changelog:

```md
## [2.3.2] - 2026-11-16

### Changed
- Mô tả thay đổi

### Fixed
- Mô tả lỗi đã sửa
```

- Nhóm hỗ trợ: `Added` (Thêm), `Changed` (Thay đổi), `Fixed` (Sửa lỗi), `Security` (Bảo mật), `Deprecated`, `Removed`, `Docs`, `Notes`.
- Các mục cũ dạng `## [YYYY-MM-DD] vX.Y.Z — Tiêu đề` vẫn được parse; mục không có version dùng ngày làm khóa (thêm `-2`, `-3` nếu trùng ngày).
- File thiếu/không đọc được: trang vẫn 200, danh sách rỗng, log warning.

## 3. Quy trình phát hành

1. Hoàn tất code + test.
2. Cập nhật `VERSION` (ví dụ `2.3.2`).
3. Thêm mục mới **trên cùng** `CHANGELOG.md` theo định dạng trên.
4. Build + test; commit `VERSION` + `CHANGELOG.md` (và mã nguồn liên quan).
5. Mở lại trang Lịch sử cập nhật — nội dung tự cập nhật theo `filemtime`, không cần xóa cache tay.

Không cần tạo bản ghi trong Admin. Không sửa `CHANGELOG.md` bằng tay trên production nếu không commit — trang sẽ lệch với mã nguồn.

## 4. Lịch sử gần đây

| Version | Ngày | Nội dung chính |
|---|---|---|
| 2.4.3 | 2026-11-16 | Tour tự refresh review khi quay lại tab |
| 2.4.2 | 2026-11-16 | Sửa nút gửi yêu cầu tư vấn tour (thiếu route helper) |
| 2.4.1 | 2026-11-16 | Polling không socket hiện toast mới + chu kỳ cấu hình |
| 2.4.0 | 2026-11-16 | Realtime Admin: đồng bộ trạng thái, hook badge/toast/title, polling dự phòng, Dashboard live |
| 2.3.2 | 2026-11-16 | Lịch sử cập nhật đọc trực tiếp CHANGELOG.md (chỉ đọc) |
| 2.3.1 | 2026-11-16 | Vá 429 Inertia, trusted proxies, login IP 20/phút, Echo WSS, cache related tour |
| 2.3.0 | 2026-11-16 | Đánh giá khách, tư vấn tour, rate limit/honeypot, private channel realtime |
| 2.2.0 | 2026-11-14 | Lịch sử cập nhật, Visual Editor GĐ1b, carousel The Window |

## 5. Kỹ thuật

- `ChangelogService::entries()` cache theo `md5(path) + filemtime`; file đổi → key đổi, không cần flush.
- Parse heading `##`, nhóm `###`, bullet `-`/`*`; lỗi parse trả `[]` + log warning.
- Route: `GET /admin/lich-su-cap-nhat` (index), `GET /admin/lich-su-cap-nhat/{version}` (show).
