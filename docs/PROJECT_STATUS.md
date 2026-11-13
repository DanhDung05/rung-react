# PROJECT STATUS — Rừng Adventure

Cập nhật: 2026-11-11 · commit `9f03af2` · Build: OK · Test: 103 passed / 656 assertions.

## 1. Trạng thái tổng quan

| Hạng mục | Trạng thái |
|---|---|
| Backend Laravel 10 + MySQL | Hoàn thành |
| Admin React (59 trang CRUD) | Hoàn thành |
| Public React (Home, Tour, Destination, Blog, Pages, Search) | Hoàn thành |
| Hero Slider | Hoàn thành (không nằm trong phạm vi editor) |
| Settings key-value + phân quyền | Hoàn thành |
| UI Text (nội dung giao diện từng trang) | Giai đoạn 1 (Tour Index) |
| Home Section Editor (Gallery) | Hoàn thành |
| Palette + Typography toàn cục | Hoàn thành |
| Preview iframe trong Settings | Hoàn thành (chưa kiểm thử trực quan đầy đủ) |
| Visual Editor (trang chủ) | Giai đoạn 1 (13 phần tử) |

## 2. Đã hoàn thành

- CRUD: Tour, Destination, Category, Post, Tag, Page, FAQ, Slider, Menu (2 cấp), User, Media (thùng rác/khôi phục), Contact, Review (duyệt/xóa).
- Auth + phân quyền admin/editor; policy cho User & Setting.
- Settings 9 nhóm: general, contact, social, seo, hero, about, palette, typography, appearance (+ theme cũ).
- Public: Home, Tour Index/Show, Destination Index/Show, Blog Index/Category/Tag/Show, About, Contact (form), FAQ, Pages/Show, Search, trang lỗi 403/404/419/500.
- Theme: palette 6 màu → CSS vars; typography 10 nhóm; loại trừ Hero Slider H1.
- Visual Editor: draft/published/history (5 bản), undo/redo, 3 breakpoint, iframe preview.
- Màu nền: Palette (palette_cream, palette_white) là nguồn duy nhất; Appearance chỉ còn thông báo + liên kết, g_* giữ làm fallback không ghi đè.
- Design tokens: 10 palette + 10 nhóm typography; 	okens.css là nguồn gốc, ThemeStyles override từ Settings; command 	okens:migrate cho legacy.

## 3. Đang triển khai / dở dang

| Việc | Ghi chú |
|---|---|
| Visual Editor GĐ1b | Mới gắn `data-ui` cho 13 phần tử; thiếu tiêu đề đầy đủ, nút, ảnh, bật/tắt section |
| UI Text | Mới cấu hình Tour Index; các trang khác còn hardcode |
| Preview B2.3 | Đã có iframe; chưa kiểm thử tương tác do không có phiên browser |
| Palette | Đã thay màu chính; còn 68 hex (shade, màu chức năng) |

## 4. Lỗi / giới hạn đã biết

- Ảnh nội dung thiếu: 12/13 điểm đến, 7/9 tour, 0/9 bài viết; 1 file `.webp` thực chất JPEG (sai MIME) → dùng placeholder SVG.
- Media Library: chưa có upload trực tiếp trong trang Media.
- Toggle trạng thái ở một số module dùng form update thay vì endpoint riêng.
- Visual Editor chỉ hỗ trợ Home, chưa có ảnh/button/section toggle.
- B2.2 chưa kiểm 8 trang × 3 breakpoint.
- Menu item cấp 3 có thể hiển thị sai (giới hạn cũ của Menu 2 cấp).

## 5. Việc tiếp theo

1. Hoàn thiện Visual Editor GĐ1b (đủ 6 section, ảnh, nút).
2. Mở rộng UI Text cho Tour Show, Destination, Blog, Pages.
3. Hoàn tất kiểm thử palette/typography đa trang, đa breakpoint.
4. Bổ sung upload Media.
5. Chuẩn hóa ảnh nội dung (đúng MIME, đủ ảnh).
