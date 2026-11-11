# QUY TẮC CẬP NHẬT TÀI LIỆU (BẮT BUỘC)

Áp dụng cho mọi task từ 2026-11-11. Tài liệu phải phản ánh **code đang chạy**, không suy đoán.

## Sau mỗi task hoàn thành

1. Cập nhật `CHANGELOG.md` (Added / Changed / Fixed / Removed, kèm commit hash).
2. Cập nhật `docs/PROJECT_STATUS.md` nếu trạng thái module thay đổi.
3. Cập nhật tài liệu module tương ứng (`ADMIN_FEATURES.md`, `ARCHITECTURE.md`, `DATABASE.md`, `DEPLOYMENT.md`, `FEATURES.md`).
4. Cập nhật trang **Admin → Hướng dẫn sử dụng** (`config/admin_guide.php`) nếu thao tác Admin thay đổi.
5. Ghi commit hash vào mục tương ứng sau khi commit thành công.

## Quy tắc nội dung

- Chỉ mô tả route/controller/component đã kiểm tra trong code.
- Không đánh dấu “hoàn thành” cho phần chưa chạy được hoặc chưa kiểm thử.
- Phần dở dang ghi rõ ở `PROJECT_STATUS.md` mục “Đang triển khai”.
- Không ghi mật khẩu, token, thông tin đăng nhập vào tài liệu.
- Không xóa lịch sử CHANGELOG cũ.

## Kiểm tra trước khi kết thúc task

- [ ] `npm run build` exit 0
- [ ] `php artisan test` toàn bộ pass
- [ ] `git status` sạch sau commit
- [ ] Tài liệu và trang Hướng dẫn khớp code
