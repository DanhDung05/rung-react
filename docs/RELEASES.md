# LỊCH SỬ CẬP NHẬT (RELEASES)

Cập nhật: 2026-11-14.

## 1. Vị trí

Admin → **Lịch sử cập nhật** (`/admin/lich-su-cap-nhat`).

- Phiên bản hiện tại: **2.3.0** (VERSION).
- Timeline version mới nhất trên cùng.
- Lọc theo version, loại thay đổi, trạng thái, khoảng ngày.
- Xem chi tiết / sửa / xóa từng bản (có xác nhận trước khi xóa).
- Chỉ quản trị viên (`admin` middleware) truy cập.

## 2. Dữ liệu

Bảng `releases`:

| Cột | Ý nghĩa |
|---|---|
| `version` | Duy nhất, dạng `X.Y.Z` (cho phép tiền tố `v`) |
| `previous_version` | Version trước |
| `title` | Tiêu đề bản cập nhật |
| `category` | `feature` / `improvement` / `fix` / `security` |
| `status` | `released` (Đã phát hành) / `development` (Đang phát triển) |
| `content` | Nội dung nhiều dòng; dòng bắt đầu `-` hiển thị thành danh sách |
| `released_at` | Ngày phát hành (có thể trống khi đang phát triển) |
| `created_by` | Người tạo (FK `users`, null nếu user bị xóa) |

Không có seed/ bản ghi mặc định — không tự tạo “bản phát hành giả”.

## 3. Quy trình phát hành

1. Hoàn tất code + test trên nhánh làm việc.
2. Cập nhật `VERSION` (ví dụ `2.3.0`).
3. Thêm mục vào `CHANGELOG.md` (ngày, nhóm thay đổi).
4. Vào Admin → Lịch sử cập nhật → **Thêm bản cập nhật**:
   - Version khớp `VERSION`, chọn **Đã phát hành**, ngày phát hành.
   - Nhập nội dung giống CHANGELOG (không chứa đường dẫn máy chủ, lỗi kỹ thuật nội bộ).
5. Build + test; commit kèm `VERSION`, `CHANGELOG.md` và migration (nếu có).

**Không** tạo bản ghi khi chỉ đang sửa code hoặc chưa hoàn tất; dùng trạng thái **Đang phát triển** nếu cần theo dõi nội bộ (bản ghi này vẫn hiển thị trong Admin nhưng không đại diện bản phát hành chính thức).

## 4. Lịch sử phát hành gần đây

| Version | Ngày | Nội dung chính |
|---|---|---|
| 2.3.0 | 2026-11-16 | Đánh giá khách vãng lai, yêu cầu tư vấn tour, rate limit/honeypot, private channel realtime |
| 2.2.0 | 2026-11-14 | Lịch sử cập nhật, Visual Editor GĐ1b, carousel The Window |

## 5. Đồng bộ

- `VERSION` = nguồn version hiện tại, hiển thị ở đầu trang Lịch sử cập nhật.
- `CHANGELOG.md` = bản ghi kỹ thuật đầy đủ.
- Bảng `releases` = bản trình bày cho Admin/người dùng nội bộ.

Ba nguồn phải cùng version/ngày khi phát hành. Không sửa một nơi mà bỏ hai nơi còn lại.
