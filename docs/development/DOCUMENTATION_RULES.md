# QUY TẮC CẬP NHẬT TÀI LIỆU (BẮT BUỘC)

Áp dụng cho **mọi task** từ 2026-11-16. Tài liệu phải phản ánh **code đang chạy**, không suy đoán từ tên commit.

## 1. Ma trận bắt buộc theo loại thay đổi

| Thay đổi | Phải cập nhật |
|---|---|
| Mọi tính năng/sửa lỗi | `CHANGELOG.md` (Added/Changed/Fixed/Removed + ngày + commit) |
| Chức năng Admin mới/đổi thao tác | `docs/ADMIN_GUIDE.md` + `docs/ADMIN_FEATURES.md` |
| Đổi trạng thái/hoàn thành/tồn đọng | `docs/PROJECT_STATUS.md` |
| Đổi kiến trúc, module, luồng, bảng, config | `docs/ARCHITECTURE.md` (+ `docs/DATABASE.md` nếu đổi schema) |
| Đổi phát hành/version | `docs/RELEASES.md`, `VERSION`, `CHANGELOG.md`, bản ghi Admin |
| Đổi token/màu/typography | `docs/DESIGN_TOKENS.md` |
| Đổi setup/build/deploy | `README.md`, `docs/DEPLOYMENT.md` |
| Đổi tài liệu/trợ giúp | `docs/README.md`, `config/admin_docs.php` nếu thêm file |

## 2. Quy tắc bắt buộc

- Cập nhật tài liệu **trong cùng đợt công việc**, trước khi báo hoàn thành/commit. Không dồn lại sau.
- Không ghi tính năng chưa chạy/chưa kiểm thử là “đã hoàn thành”.
- Không tạo tài liệu trùng lặp; sửa file hiện có thay vì tạo bản song song.
- Không để tài liệu mâu thuẫn code; nếu phát hiện lệch, sửa tài liệu hoặc ghi rõ tồn đọng.
- `docs/ADMIN_GUIDE.md` là nguồn duy nhất cho trang **Admin → Hướng dẫn sử dụng** (render Markdown). Không nhập nội dung lần hai vào config.
- `config/admin_guide.php` là legacy, không dùng cho trang Trợ giúp nữa.
- Không ghi mật khẩu, token, `.env`, đường dẫn máy chủ, dữ liệu nhạy cảm.
- Không xóa lịch sử CHANGELOG; chỉ thêm mục mới.

## 3. Quy ước nội dung

- Mỗi mục CHANGELOG: ngày, phạm vi, file chính, commit hash.
- Hướng dẫn Admin: đường dẫn, các bước, ý nghĩa tùy chọn, lỗi thường gặp.
- Ghi rõ giới hạn/known issues thay vì mô tả lý tưởng.
- Liên kết chéo giữa ADMIN_GUIDE ↔ ADMIN_FEATURES ↔ RELEASES ↔ ARCHITECTURE.

## 4. Checklist trước khi kết thúc task

- [ ] `CHANGELOG.md` có mục mới
- [ ] `PROJECT_STATUS.md` khớp thực tế
- [ ] `ADMIN_GUIDE.md`/`ADMIN_FEATURES.md` nếu có thay đổi Admin
- [ ] `ARCHITECTURE.md` nếu đổi cấu trúc
- [ ] Trang `/admin/huong-dan` render đúng tài liệu mới
- [ ] `npm run build` exit 0
- [ ] `php artisan test` toàn bộ pass
- [ ] `git status` sạch sau commit
