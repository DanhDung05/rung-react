# PROJECT STATUS — Rừng Adventure

Cập nhật: 2026-11-16 · version `2.4.0` · Build: OK · Test: **173 passed / 1103 assertions**.

## 1. Trạng thái tổng quan

| Hạng mục | Trạng thái |
|---|---|
| Backend Laravel 10 + MySQL 8 | Hoàn thành |
| Admin React (CRUD đầy đủ) | Hoàn thành |
| Public React (Home, Tour, Destination, Blog, Pages, Search) | Hoàn thành |
| Hero Slider + bật/tắt overlay | Hoàn thành |
| Settings key-value + preview iframe | Hoàn thành |
| Palette 10 màu + Typography 10 nhóm | Hoàn thành |
| Visual Editor (6 section, nút/ảnh/toggle/order) | Hoàn thành (GĐ1b) |
| Thư viện ảnh + carousel The Window | Hoàn thành |
| Đánh giá khách vãng lai (không cần tài khoản) | Hoàn thành |
| Yêu cầu tư vấn tour (lead) | Hoàn thành |
| Chống spam: rate limit + honeypot | Hoàn thành |
| Realtime private channel + badge/toast Admin | Hoàn thành |
| Lịch sử cập nhật (`releases`) | Hoàn thành |
| Trợ giúp Admin render từ Markdown | Hoàn thành |
| Dark mode + sidebar thu gọn | Hoàn thành |
| Một lệnh `npm run dev` (+Soketi tùy chọn) | Hoàn thành |
| UI Text | Giai đoạn 1 (Tour Index) |

## 2. Đã hoàn thành (theo module)

- **CRUD Admin**: Tour, Điểm đến, Danh mục, Bài viết, Tag, Trang tĩnh, FAQ, Slider, Menu 2 cấp, Media (thùng rác/khôi phục), Liên hệ, Đánh giá, Người dùng.
- **Auth & phân quyền**: role admin/editor; policy User/Setting.
- **Settings**: general, contact, social, seo, hero (+overlay), about, palette (10), typography (10 nhóm), theme; appearance ẩn.
- **Design tokens**: `tokens.css` nguồn gốc; `ThemeStyles` override; `tokens:migrate` cho legacy; H1–H3 kế thừa Forest.
- **Visual Editor**: chọn thành phần, style, bật/tắt section, ảnh Banner/CTA, nút, section quote band, kéo-thả thứ tự, draft/publish/history 5 bản.
- **Media/Gallery**: 20 ảnh The Window import idempotent; `home_gallery_media`; carousel 3D responsive khung 4:3, modal.
- **Lịch sử cập nhật**: đọc trực tiếp `CHANGELOG.md` (chỉ đọc), badge Hiện tại theo `VERSION`, lọc/tìm; bảng `releases` giữ nhưng không dùng.
- **Đánh giá khách**: user_id nullable, author_name/email, pending duyệt, email không lộ public.
- **Tư vấn tour**: contacts thêm tour_id/people_count/preferred_date/type; mail + broadcast; admin hiển thị tour.
- **Bảo mật**: limiter contact/review/login (email+IP 5/phút + IP 20/phút), honeypot, private channel admin-notifications.
- **v2.3.1**: 429 form Inertia thân thiện; trusted proxies IP thật; Echo WSS theo env; cache related theo tour + invalidate.
- **v2.4.0 Realtime Admin**: event trạng thái Contact/Review, hook tập trung (badge/toast/title/reload), polling 60s dự phòng, Dashboard live, payload không PII.
- **Admin UX**: dark/light, sidebar sticky + thu gọn, font rõ nét, logo brand, preview Settings.
- **Dev**: `npm run dev` chạy Laravel + Vite + kiểm tra MySQL; Soketi tự chạy khi bật pusher.
- **Docs**: ADMIN_GUIDE, ADMIN_FEATURES, ARCHITECTURE, PROJECT_STATUS, RELEASES, DESIGN_TOKENS, DEVELOPMENT_LOG, quy tắc tài liệu.

## 3. Đang triển khai / dở dang

| Việc | Ghi chú |
|---|---|
| UI Text mở rộng | Mới Tour Index; Tour Show/Điểm đến/Blog/Trang tĩnh chưa |
| Media picker trong Visual Editor | Ảnh đang nhập URL `/storage/...` |
| Upload Media trực tiếp | Chưa có form trong `/admin/media` |
| SSR text Visual Editor | Text xuất bản áp dụng client-side |
| Ảnh nội dung thật | Nhiều tour/điểm đến/bài viết còn placeholder |
| B2.2 visual QA | Chưa đủ 8 trang × 3 breakpoint |
| Realtime Soketi | Tùy chọn, mặc định tắt |

## 4. Lỗi / giới hạn đã biết

- Ảnh placeholder SVG khi thiếu ảnh; 1 file `.webp` cũ thực tế là JPEG.
- Menu item cấp 3 có thể hiển thị sai (giới hạn 2 cấp).
- Carousel hiện dùng ảnh chân dung The Window (dọc) trong khung ngang → crop; cần ảnh phong cảnh ngang.
- Toggle một số module dùng form update thay endpoint riêng.

## 5. Việc tiếp theo

1. Mở rộng UI Text cho Tour Show, Điểm đến, Blog, Trang tĩnh.
2. Media picker + upload trực tiếp trong Media.
3. SSR hoá text Visual Editor.
4. Thay ảnh du lịch ngang cho thư viện.
5. Kiểm thử giao diện đa trang/breakpoint.
6. Bật Soketi nếu cần realtime thông báo.
