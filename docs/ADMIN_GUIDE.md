# ADMIN GUIDE — Hướng dẫn quản trị Rừng Adventure

Cập nhật: 2026-11-16 · áp dụng cho version `2.2.0` (commit `3e7854a`).
Trang này là nguồn chính cho mục **Admin → Hướng dẫn sử dụng**; mọi thay đổi chức năng Admin phải cập nhật file này.

## 1. Đăng nhập & phân quyền

- Truy cập `/dang-nhap`, nhập email + mật khẩu được cấp.
- Sau khi đăng nhập, vào `/admin/dashboard`.
- Hai vai trò:
  - **admin**: toàn quyền (người dùng, cài đặt, xóa vĩnh viễn).
  - **editor**: quản lý nội dung (tour, bài viết, điểm đến, media…), không vào Cài đặt/Người dùng.
- Đăng xuất ở góc phải topbar.
- Quên mật khẩu: liên hệ admin; không tự tạo tài khoản ngoài Admin.

## 2. Tổng quan (Dashboard)

`/admin/dashboard`

- Thẻ số liệu: tour, bài viết, điểm đến, liên hệ, người dùng, danh mục, trang tĩnh, slider.
- Biểu đồ 7 ngày, phân bố độ khó tour.
- Danh sách mới nhất + lối tắt tới thùng rác.
- Số liệu lấy trực tiếp từ database, không hardcode.

## 3. Quản lý Tour

`/admin/tour`

- **Thêm**: bấm “Thêm tour”, nhập tên, slug (bỏ trống tự sinh), danh mục, điểm đến, giá, thời lượng, độ khó, mô tả, lịch trình, ảnh đại diện.
- **Sửa**: bấm biểu tượng bút ở dòng tương ứng.
- **Xóa**: chuyển vào thùng rác; khôi phục hoặc xóa vĩnh viễn trong “Thùng rác”.
- **Ảnh**: JPG/PNG/WebP; hệ thống tự tạo thumbnail 400px. Ảnh lưu tại `storage/app/public/media/tours/...`.
- **Trạng thái**: `published` mới hiện trên website; `draft` chỉ trong Admin.
- **Lịch trình**: thêm từng ngày, thứ tự hiển thị theo số ngày.

Lưu ý: tour chưa có ảnh sẽ dùng ảnh placeholder; nên bổ sung ảnh thật trước khi xuất bản.

## 4. Điểm đến, Danh mục

- **Điểm đến** `/admin/diem-den`: CRUD, ảnh, mô tả, vùng miền; thùng rác.
- **Danh mục** `/admin/danh-muc`: dùng chung cho tour/bài viết; có slug, thứ tự, trạng thái; thùng rác.

## 5. Bài viết, Tag, Trang tĩnh, FAQ

- **Bài viết** `/admin/bai-viet`: tiêu đề, slug, danh mục, tag, ảnh, mô tả ngắn, nội dung (TinyMCE), trạng thái, ngày đăng.
- **Tag** `/admin/tag`: CRUD đơn giản.
- **Trang tĩnh** `/admin/trang-tinh`: nội dung các trang như Giới thiệu, Chính sách; có thùng rác.
- **FAQ** `/admin/faq`: câu hỏi, câu trả lời, nhóm, thứ tự, bật/tắt.

## 6. Slider (Hero trang chủ)

`/admin/slider`

- Thêm/sửa slide: tiêu đề, phụ đề, ảnh, liên kết, thứ tự, trạng thái.
- Slide đầu tiên là ảnh hero mặc định cho trang chủ và một số trang con nếu chưa đặt ảnh riêng.
- Bật/tắt lớp phủ tối: **Cài đặt → Ảnh Hero → “Lớp phủ tối trên ảnh Hero”**. Khi tắt vẫn giữ dải tối trên cùng để menu đọc rõ.
- Hero H1 không chịu Typography toàn cục (giữ thiết kế riêng).

## 7. Menu

`/admin/menu`

- Tạo menu vị trí `main`, `footer`, `top`…
- Thêm item: nhãn, URL, thứ tự, cấp cha (tối đa 2 cấp), target.
- Thay đổi hiển thị ngay ở Header/Footer public.

## 8. Media & Thư viện ảnh

`/admin/media`

- Liệt kê ảnh, lọc theo collection, xem thùng rác, khôi phục/xóa vĩnh viễn.
- **Chưa có form upload trực tiếp trong Media**; ảnh được upload qua Tour/Bài viết/Slider/Cài đặt.
- Ảnh import cho trang chủ nằm ở collection `gallery`, thư mục `media/gallery/the-window/`.
- Bộ ảnh “The Window” gồm 20 WebP; nhập lại (idempotent) bằng:

```bash
php artisan media:import-the-window
```

- Trang chủ hiển thị các ảnh này bằng **carousel 3D** ở section Thư viện (5 ảnh đầu theo `sort_order`, tổng tối đa 24 ảnh).
- Muốn thay ảnh: thay file trong `storage/app/public/media/gallery/the-window/`, cập nhật `meta.json`, chạy lại command.
- Ảnh du lịch nên là **ảnh ngang** (3:2 hoặc 16:9) để hợp khung carousel.

## 9. Liên hệ & Đánh giá

### 9.1 Hộp thư liên hệ — `/admin/lien-he`

- Hiển thị tên, email, SĐT, tiêu đề, thời gian; lọc theo trạng thái và **Loại** (Liên hệ chung / Tư vấn tour).
- **Tư vấn tour**: hiện thêm tên tour, số người, ngày dự kiến; bấm tên tour mở trang quản trị tour.
- Chi tiết: SĐT bấm gọi được (`tel:`), email trả lời nhanh, nút đổi trạng thái (mới/đã đọc/đã trả lời/lưu trữ).
- Lead mới gửi email thông báo cho admin và (nếu bật) hiện toast realtime.
- Form liên hệ/lead có **honeypot** và giới hạn **5 lượt/giờ/IP**; vượt sẽ nhận lỗi 429.

### 9.2 Đánh giá — `/admin/danh-gia`

- Khách **không cần đăng nhập** vẫn gửi được đánh giá (họ tên + email bắt buộc).
- Mọi đánh giá mới ở trạng thái **Chờ duyệt**; chỉ đánh giá **Đã duyệt** hiện trên trang Tour.
- Danh sách hiển thị tên + email người gửi (kể cả khách), tour, số sao, nội dung, thời gian.
- Duyệt / Từ chối / chuyển vào thùng rác; khôi phục hoặc xóa vĩnh viễn trong Thùng rác.
- Một email chỉ đánh giá một tour một lần; khách trùng email sẽ bị từ chối.
- Giới hạn gửi: **3 lượt/giờ/IP**; email người đánh giá **không** xuất hiện trên website public.
- Thông báo realtime chỉ gửi id/loại/tiêu đề/thời gian — không gửi email/SĐT/nội dung.

## 10. Người dùng

`/admin/tai-khoan` (chỉ admin)

- Tạo/sửa tài khoản, đổi vai trò admin/editor, khóa/mở.
- Không thể tự khóa hoặc tự hạ quyền chính mình.
- Không nhập mật khẩu vào tài liệu/ghi chú.

## 11. Cài đặt

`/admin/cau-hinh`

### 11.1 Cấu hình chung
Tên website, mô tả, logo, favicon, thông tin pháp lý. Logo hiển thị Header/Footer; ảnh logo nên nền trong suốt.

### 11.2 Liên hệ
Email, số điện thoại, địa chỉ, giờ làm việc, email nhận thông báo.

### 11.3 Mạng xã hội
Facebook, Instagram, YouTube, TikTok (dán URL đầy đủ).

### 11.4 SEO
Title/description/keywords mặc định; kiểm tra độ dài trước khi lưu.

### 11.5 Ảnh Hero
6 ảnh hero cho các trang con và công tắc lớp phủ tối. Để trống → dùng ảnh slider đầu tiên.

### 11.6 Trang giới thiệu
Tiêu đề, phụ đề, nội dung, ảnh cho Home/Giới thiệu.

### 11.7 Bảng màu website
10 màu gốc: Forest, Cream, White, Earth, Text, Muted, Gold, Surface, Border, On-dark.

- Khối **“Màu này ảnh hưởng phần nào?”** hiển thị mẫu thành phần thật.
- Preview iframe Home/Tour, Desktop/Tablet/Mobile.
- “Đang lưu” xem cấu hình đã lưu; “Đang chỉnh” xem giá trị trên form.
- Bấm **Lưu cấu hình** mới áp dụng cho khách; F5 trang public để thấy.
- Nếu preview lệch: kiểm tra đang ở chế độ “Đang lưu” và đã Lưu chưa.

### 11.8 Typography
10 nhóm (H1–H3, Page Title, Section Title, Body, Lead, Eyebrow, Breadcrumb, Footer) × font/màu/weight/size/line-height.
Giá trị trống = mặc định. H1 hero slider được loại trừ.

### 11.9 Màu giao diện Admin
Chỉ 2 màu nút/badge của khu quản trị, không ảnh hưởng website public.

### 11.10 Nút hỗ trợ
- **Hủy thay đổi**: nạp lại giá trị đã lưu.
- **Khôi phục mặc định**: chỉ điền lại form, chưa ghi DB tới khi bấm Lưu.
- Công tắc bật/tắt lưu đúng sau khi sửa (đã fix).

## 12. Nội dung trang chủ

`/admin/noi-dung-trang-chu`

- Chỉnh section Thư viện: bật/tắt section, màu nền kem/trắng, nhãn, tiêu đề, chữ nhấn, mô tả, nút và màu override.
- Ảnh do Media/Gallery quyết định; section này không chọn ảnh.
- Tắt override để kế thừa Palette/Typography toàn cục.

## 13. Nội dung giao diện (UI Text)

`/admin/noi-dung-giao-dien/{page}`

- Hiện hỗ trợ **Tour Index** (hero, filter, kết quả, CTA).
- Sửa text/label, khôi phục mặc định từng section.
- Chưa phủ Tour Show, Điểm đến, Blog, Trang tĩnh.

## 14. Visual Editor

`/admin/visual-editor`

- Chọn thành phần trên iframe (viền vàng) để sửa chữ, màu, font, size, weight, line-height.
- **Bật/tắt section**: chọn vùng section → checkbox “Hiện section này”.
- **Ảnh Banner/CTA**: chọn ảnh → nhập đường dẫn Media `/storage/media/...`.
- **Nút**: chọn nút để sửa liên kết; chọn phần chữ để sửa nội dung (giữ icon).
- **Thứ tự section**: kéo-thả hoặc ↑/↓; mỗi section bổ sung là một mục riêng; Hero luôn đầu.
- **Section bổ sung (quote band)**: thêm nhiều khối ảnh nền + nhãn + trích dẫn + nguồn.
- Quy trình: chỉnh → **Lưu nháp** → kiểm tra → **Xuất bản**. Có **Lịch sử 5 bản** để khôi phục.
- Giới hạn: text đã xuất bản áp dụng phía client (chưa SSR), ảnh nhập URL (chưa có Media picker), chưa có kéo-thả ảnh.

## 15. Lịch sử cập nhật

`/admin/lich-su-cap-nhat`

- Trang **chỉ đọc** từ `CHANGELOG.md`; không có nút thêm/sửa/xóa.
- Timeline version mới nhất trên cùng; badge **Hiện tại** cho version khớp `VERSION`.
- Tìm theo version/từ khóa và lọc theo nhóm thay đổi (Thêm/Thay đổi/Sửa lỗi/Bảo mật…).
- Bấm “Xem chi tiết” để xem đầy đủ các nhóm và từng dòng.
- Muốn cập nhật lịch sử: sửa `CHANGELOG.md` + `VERSION` trong mã nguồn rồi commit; trang tự đổi, không cần xóa cache.
- Mục cũ không có số version sẽ dùng ngày làm khóa; vẫn xem được bình thường.

## 16. Hướng dẫn sử dụng (Trợ giúp)

`/admin/huong-dan`

- Tài liệu render trực tiếp từ file Markdown trong `docs/` (nguồn chính), không phải nhập lại.
- Tìm kiếm theo tên mục/nội dung; chọn tài liệu ở cột trái.
- Các tài liệu: Hướng dẫn quản trị, Danh mục tính năng, Phát hành, Design Tokens, Kiến trúc.
- Link nội bộ giữa các tài liệu hoạt động trong trang.

## 17. Giao diện Admin

- Nút **mặt trăng/mặt trời**: đổi sáng/tối, lưu theo trình duyệt.
- Nút **thu gọn sidebar**: còn icon, lưu trạng thái.
- Sidebar cố định khi cuộn.

## 18. Lỗi thường gặp

| Hiện tượng | Cách xử lý |
|---|---|
| 403 khi vào Admin | Tài khoản không đủ quyền hoặc bị khóa; nhờ admin cấp quyền |
| Ảnh 404 | Kiểm tra `public/storage` đã link (`php artisan storage:link`), file còn trong `storage/app/public/...` |
| Lưu cấu hình báo thành công nhưng không đổi | Ctrl+F5; kiểm tra đúng nhóm/nút Lưu; xem preview chế độ “Đang lưu” |
| Công tắc tự bật lại | Đã fix; nếu vẫn gặp, báo kèm tên trường |
| Trang trắng sau khi sửa | `npm run build` lại; xem log trình duyệt/ Laravel log |
| Lỗi database | Bật MySQL (Laragon), kiểm tra `.env` |
| 419 Page Expired | Phiên hết hạn; tải lại và đăng nhập |
| 429 Too Many Requests | Đã vượt giới hạn liên hệ/đánh giá/đăng nhập; chờ theo thời gian thông báo |
| Toast/badge realtime không xuất hiện | Kiểm tra `VITE_PUSHER_APP_KEY`, Soketi đang chạy; không có key thì tính năng tự tắt, form vẫn hoạt động |

## 19. Tài liệu liên quan

- [ADMIN_FEATURES.md](ADMIN_FEATURES.md) — danh mục tính năng + route.
- [RELEASES.md](RELEASES.md) — quy trình phát hành.
- [DESIGN_TOKENS.md](DESIGN_TOKENS.md) — màu/typography.
- [ARCHITECTURE.md](ARCHITECTURE.md) — kiến trúc hệ thống.
- [PROJECT_STATUS.md](PROJECT_STATUS.md) — trạng thái dự án.
- [development/DOCUMENTATION_RULES.md](development/DOCUMENTATION_RULES.md) — quy tắc cập nhật tài liệu.

## 20. Ghi chú v2.3.1

- Khi bị giới hạn tần suất, form hiện thông báo “Bạn thao tác quá nhanh…” và giữ nguyên dữ liệu đã nhập; chờ theo số giây/phút thông báo rồi gửi lại.
- Đăng nhập: tối đa 5 lần sai cho mỗi email+IP mỗi phút và **20 lần sai/phút cho mỗi IP**; đăng nhập thành công không xóa bộ đếm IP.
- Nếu deploy sau proxy, cấu hình `TRUSTED_PROXIES` (xem `docs/DEPLOYMENT.md`) để rate limit và log nhận đúng IP khách.
