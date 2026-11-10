# RUNG ADVENTURE — DANH MỤC CHỨC NĂNG

> **Cập nhật:** 10/11/2026 — Giai đoạn 1 (audit)
> **Nguồn:** đối chiếu trực tiếp source + `php artisan route:list` + DB thật
> **Chú thích:** ✅ hoàn chỉnh · 🟡 một phần · ❌ chưa có · ⛔ bị vô hiệu hóa

Bảng dưới đây phân biệt rõ **3 tầng**: backend (Laravel), admin UI (React), frontend UI (React). Một chức năng có backend đầy đủ nhưng thiếu UI **không được coi là đã hoàn thành** với người dùng cuối.

---

## 1. TỔNG QUAN THEO MODULE

| # | Module | Backend | Admin UI | Frontend UI | Ghi chú |
|---|---|:---:|:---:|:---:|---|
| 1 | Đăng nhập / Đăng xuất | ✅ | ✅ | ✅ | `Auth/Login.jsx` tồn tại và chạy |
| 2 | Quên / đổi mật khẩu | ❌ | ❌ | ❌ | Không có route, không có mail reset |
| 3 | Đăng ký tài khoản | ❌ | — | ❌ | Cố ý chưa có (site không cho tự đăng ký) |
| 4 | Phân quyền (admin/editor) | 🟡 | ❌ | — | Chỉ middleware; **editor leo thang được thành admin** |
| 5 | Tour du lịch (CRUD) | ✅ | ❌ | ❌ | 10 route admin, 3 route frontend |
| 6 | Điểm đến (CRUD) | ✅ | ❌ | ❌ | 10 route admin |
| 7 | Danh mục (CRUD) | ✅ | ❌ | ❌ | 10 route admin, 2 loại tour/post |
| 8 | Bài viết (CRUD) | ✅ | ❌ | ❌ | 10 route admin |
| 9 | Trang tĩnh (CRUD) | ✅ | ❌ | ❌ | 10 route admin |
| 10 | Slider (CRUD) | ✅ | ❌ | ❌ | 10 route admin; **Home không render slider** |
| 11 | Tag (CRUD) | ✅ | ❌ | ❌ | 10 route admin |
| 12 | FAQ (CRUD) | ✅ | ❌ | ❌ | 10 route admin |
| 13 | Menu động (3 cấp) | ✅ | ❌ | ❌ | 12 route admin; React dùng nav hardcode |
| 14 | Người dùng (CRUD) | ✅ | ❌ | — | 7 route; thiếu route xóa |
| 15 | Cài đặt hệ thống | ✅ | ❌ | ❌ | 3 route; React không nhận settings |
| 16 | Media Library | 🟡 | ❌ | — | Chỉ đọc + xóa; **không có upload** |
| 17 | Liên hệ (nhận & xử lý) | ✅ | ❌ | ❌ | Form public chưa có UI React |
| 18 | Đánh giá tour | 🟡 | ❌ | ❌ | Có API store + duyệt; chưa có UI |
| 19 | Tìm kiếm tổng hợp | ✅ | — | ❌ | 96 dòng controller, không có UI |
| 20 | Sitemap XML | ✅ | — | ✅ | `/sitemap.xml` chạy độc lập |
| 21 | SEO meta / OG / JSON-LD | 🟡 | ❌ | ❌ | Chỉ có trong Blade đã chết |
| 22 | Theme động (màu/font) | ✅ | ❌ | ❌ | Chỉ Blade; React không nhận |
| 23 | Thùng rác + khôi phục | ✅ | ❌ | — | 15 module có soft delete |
| 24 | Upload ảnh + thumbnail | ✅ | ❌ | — | `ImageService` tốt, thiếu UI |
| 25 | Cache nội dung | ✅ | ❌ | — | `CacheHelper`, chưa có UI xóa cache |
| 26 | Thông báo real-time | ⛔ | ❌ | — | Provider bị tắt, Soketi không chạy |
| 27 | Dashboard | 🟡 | 🟡 | — | Data đủ, UI thiếu layout + nhiều widget |
| 28 | Version Management | ❌ | ❌ | ❌ | Chưa có bảng/model/route |
| 29 | Changelog / What's New | ❌ | ❌ | ❌ | Chưa có |
| 30 | Trang công khai `/cap-nhat` | ❌ | — | ❌ | Chưa có |
| 31 | Activity Log / Audit Log | ❌ | ❌ | — | Chưa có |
| 32 | Test tự động | ❌ | — | — | 0 test nghiệp vụ |

---

## 2. CHI TIẾT TỪNG MODULE

### 2.1. Tour du lịch

**Backend ✅**
- `Admin\TourController`: index (filter status/category/difficulty/keyword, phân trang 15), create, store, show, edit, update, destroy (mềm), trash, restore, forceDestroy.
- `Frontend\TourController`: index (filter category/difficulty/price/q, sort newest/price/popular, phân trang 12), category (redirect), show (tăng view theo session, related tours).
- Validation: `StoreTourRequest` / `UpdateTourRequest` — 25+ rule, bao gồm gallery tối đa 20 ảnh, itineraries mảng.
- Quan hệ: category, destinations (n:n), itineraries (1:n), media (morph), reviews.
- Cache: `clearTourCaches()` + `clearHomepage()` sau mỗi thao tác.

**Admin UI ❌** — `Admin/Tours/{Index,Create,Edit,Show,Trash}` không tồn tại → `GenericPage`.
**Frontend UI ❌** — `Tour/{Index,Show}` không tồn tại.

**Việc cần làm:** 5 trang admin (form phức tạp nhất: repeater lịch trình + gallery) + 2 trang frontend.

---

### 2.2. Điểm đến

**Backend ✅** — 10 route admin; frontend index (filter region/featured/q, sort) + show (parent, children, tours).
**UI ❌** — 5 trang admin + 2 trang frontend.
**Đặc thù:** cây cha–con, tọa độ, vùng miền; xóa cứng chặn nếu còn tour/điểm đến con.

---

### 2.3. Danh mục

**Backend ✅** — 10 route; 2 loại `tour`/`post`; cây cha–con; xóa cứng chặn nếu còn tour/post/danh mục con.
**UI ❌** — 5 trang admin.
**Lưu ý:** `slug` unique toàn cục → không thể trùng slug giữa 2 loại.

---

### 2.4. Bài viết

**Backend ✅** — 10 route admin; frontend index/category/tag/show; tăng view theo session; sidebar (categories, recent, popular tags).
**UI ❌** — 5 trang admin + 4 trang frontend.
**Đặc thù:** bắt buộc chọn tác giả (`user_id`), chọn nhiều tag, xóa cứng chặn nếu còn tag.

---

### 2.5. Trang tĩnh

**Backend ✅** — 10 route admin; frontend `about` (slug từ setting `about_page_slug`), `show`.
**UI ❌** — 5 trang admin + `Pages/About` + `Pages/Show`.
**Đặc thù:** trang "Giới thiệu" lấy slug từ settings → đổi setting là đổi trang.

---

### 2.6. Slider

**Backend ✅** — 10 route admin; scope `active()` lọc theo `start_at`/`end_at`; `HomeController` đã truyền `sliders`.
**Admin UI ❌** — 5 trang.
**Frontend ❌** — `Home.jsx` **không render slider**, dù dữ liệu đã có trong props.

---

### 2.7. Tag

**Backend ✅** — 10 route admin.
**UI ❌** — 5 trang admin; frontend `Blog/Tag` thiếu.

---

### 2.8. FAQ

**Backend ✅** — 10 route admin; frontend group theo `category`.
**UI ❌** — 5 trang admin + `Pages/Faq`.

---

### 2.9. Menu động

**Backend ✅**
- `Menu` + `MenuItem` hỗ trợ 3 cấp; `resolved_url` ưu tiên `route_name`, fallback `url`.
- `MenuController`: CRUD + `items` (flatten đệ quy) + `saveItems` (xóa và tạo lại trong transaction).
- `MenuComposer` load `main`/`footer` cho Blade.

**Admin UI ❌** — 6 trang, trong đó **Items** là trang phức tạp (542 dòng Blade cũ).
**Frontend ❌** — React dùng mảng `nav` hardcode (`GenericPage.jsx:5`), không đọc menu từ DB.
**Hệ quả:** quản lý menu trong admin **không có tác dụng** lên website hiện tại.

---

### 2.10. Người dùng

**Backend 🟡** — index/create/store/show/edit/update + toggle-active. **Không có destroy** (đúng thiết kế: chỉ vô hiệu hóa).
**UI ❌** — 4 trang.
**Bảo mật:** editor có thể đổi `role` của bất kỳ ai, kể cả chính mình → **leo thang đặc quyền**.

---

### 2.11. Cài đặt

**Backend ✅**
- 7 group / 45 field trong `config/settings.php`.
- `SettingController`: index (danh sách group), edit (render field theo schema + default), update (xử lý image/boolean/color, xóa cache).
- `Setting::get/set/getGroup/flushCache` + cache 1 giờ.

**Admin UI ❌** — 2 trang (Index + Edit render động 45 field).
**Frontend ❌** — React không nhận settings; tên site/hotline/địa chỉ/màu/font đều hardcode hoặc không hiển thị.
**Hệ quả:** chỉnh cài đặt trong admin **không thay đổi** giao diện React.

---

### 2.12. Media Library

**Backend 🟡**
- Có: index (filter collection/type/keyword, phân trang 24), trash, destroy (mềm), restore, forceDestroy (xóa file + thumb).
- **Không có:** upload, update metadata, bulk action.
- Dữ liệu: `media` chỉ 2 bản ghi, trong khi đĩa có 17 file → phần lớn ảnh orphan.

**UI ❌** — 2 trang; không có MediaPicker.

---

### 2.13. Liên hệ

**Backend ✅**
- Public: `ContactController@store` — validate, lưu IP/UA, gửi mail queue cho `contact_notification_emails` (fallback `contact_email`), bắn event real-time.
- Admin: index (mặc định ẩn archived), show (tự chuyển new→read), updateStatus (4 trạng thái).

**UI ❌** — `Pages/Contact` (form) + 2 trang admin.
**Phụ thuộc:** Mailpit (port 1025) không chạy → gửi mail sẽ lỗi; queue `sync` nên lỗi mail có thể làm chậm request (đã bọc try/catch).

---

### 2.14. Đánh giá tour

**Backend 🟡**
- `Frontend\ReviewController@store`: yêu cầu đăng nhập, 1–5 sao, bình luận ≥ 10 ký tự, mỗi user 1 review/tour, mặc định `pending`, bắn event.
- `Admin\ReviewController`: index, duyệt, từ chối, xóa mềm, thùng rác, khôi phục, xóa cứng.
- `Tour::reviews()` chỉ trả đã duyệt; có `average_rating`, `reviews_count`.

**UI ❌** — form đánh giá trong `Tour/Show` + 2 trang admin.
**Dữ liệu:** bảng `reviews` hiện **0 dòng**.

---

### 2.15. Tìm kiếm

**Backend ✅** — `SearchController@index`: tìm tour/điểm đến/bài viết/trang, giới hạn 8/8/8/5, lọc theo `type`, đếm tổng.
**UI ❌** — `Search/Index`.

---

### 2.16. SEO

| Hạng mục | Trạng thái |
|---|---|
| `sitemap.xml` động | ✅ (`SitemapController`, 121 dòng) |
| `robots.txt` | ✅ |
| Meta title/description | 🟡 chỉ Blade (đã chết) |
| Open Graph / Twitter | 🟡 chỉ Blade |
| Canonical | 🟡 chỉ Blade |
| JSON-LD (Product/Article/FAQ/Breadcrumb) | 🟡 chỉ Blade |
| `hreflang` | ❌ |
| Meta cho trang React | ❌ (chỉ `<Head title>`) |

---

### 2.17. Theme động

**Backend ✅** — `theme` group: 2 màu chính + màu/font H1–H6; `head.blade.php` inject CSS variables + `!important` cho heading.
**React ❌** — không nhận theme → màu/font trong admin không áp dụng.
**Lưu ý:** cách dùng `!important` cho mọi heading trong Blade là giải pháp thô; khi làm React nên dùng CSS variables + class có kiểm soát.

---

### 2.18. Thùng rác & khôi phục

**Backend ✅** — 15 module dùng SoftDeletes: Tour, Post, Destination, Category, Page, Slider, Tag, Faq, Menu, MenuItem, Media, Contact, Review, User, TourItinerary.
**Quy tắc xóa cứng khác nhau:**

| Module | Quy tắc |
|---|---|
| Tour | Detach destination, force delete itinerary + media, xóa ảnh |
| Post | **Chặn nếu còn tag** (bất thường — phải gỡ tag trước) |
| Destination | Chặn nếu còn tour hoặc điểm đến con |
| Category | Chặn nếu còn tour/post/danh mục con |
| Menu | Chặn nếu còn item |
| Tag | Chặn nếu còn bài viết |
| Page/Slider/Faq/Review/Media | Xóa tự do |

**UI ❌** — 15 trang Trash chưa có.

---

### 2.19. Dashboard

**Backend 🟡** — `DashboardController@index` tính 11 prop; `@stats` trả JSON cho AJAX.
**Admin UI 🟡** — `Admin/Dashboard.jsx` chỉ dùng `stats`, `topTours`, `topPosts`; **không có layout**; bỏ phí biểu đồ, liên hệ mới, thùng rác, phân bố độ khó, menu.
**Việc cần làm:** viết lại, dùng hết dữ liệu backend đã tính (hoặc bỏ bớt query).

---

### 2.20. Chức năng chưa tồn tại (Giai đoạn 4–5)

| Chức năng | Trạng thái | Kế hoạch |
|---|---|---|
| Quản lý phiên bản | ❌ | GĐ4 |
| Changelog (added/changed/fixed/removed/security) | ❌ | GĐ4 |
| What's New (modal sau khi cập nhật) | ❌ | GĐ4 |
| Lịch sử phát hành | ❌ | GĐ4 |
| Trang công khai `/cap-nhat` | ❌ | GĐ4 |
| Theo dõi đăng nhập | ❌ | GĐ5 |
| Theo dõi create/update/delete + before/after | ❌ | GĐ5 |
| Lưu người thực hiện + thời gian | ❌ | GĐ5 |
| Giao diện tra cứu lịch sử | ❌ | GĐ5 |
| Phân quyền chi tiết (Policy/Gate) | ❌ | GĐ3 |
| Media upload | ❌ | GĐ2 |

---

## 3. NGƯỜI DÙNG THỰC SỰ LÀM ĐƯỢC GÌ HÔM NAY

Để tránh hiểu nhầm về tiến độ, đây là danh sách **đầy đủ** những gì đang hoạt động trên giao diện React hiện tại:

**Khách:**
1. Mở trang chủ → thấy hero + 3 khối card tour/điểm đến/bài viết + 4 giá trị + CTA.
2. Bấm menu → điều hướng tới `/tour`, `/diem-den`, `/bai-viet`, `/gioi-thieu`, `/lien-he` → **tất cả đều ra `GenericPage`** (bảng dữ liệu thô).
3. Không gửi được form liên hệ (chưa có form).
4. Không tìm kiếm được (chưa có UI).

**Quản trị:**
1. Đăng nhập → vào dashboard (không có sidebar).
2. Bấm link trên dashboard → ra `GenericPage` hiển thị bảng dữ liệu thô.
3. **Không tạo/sửa/xóa được bất kỳ nội dung nào qua UI** (chưa có form).

**Kết luận:** backend đã sẵn sàng, nhưng sản phẩm chưa dùng được cho người dùng cuối. Đây là lý do Giai đoạn 2 là ưu tiên tuyệt đối.

---

## 4. ĐỐI CHIẾU NHANH: BLADE CŨ vs REACT HIỆN TẠI

| Khả năng | Bản Blade cũ | Bản React hiện tại |
|---|---|---|
| Trang chủ có slider | ✅ | ❌ (props có, không render) |
| Danh sách tour + filter | ✅ | ❌ |
| Chi tiết tour + lịch trình + gallery + review | ✅ | ❌ |
| Danh sách/chi tiết điểm đến | ✅ | ❌ |
| Blog + sidebar + tag | ✅ | ❌ |
| Trang giới thiệu/liên hệ/FAQ | ✅ | ❌ |
| Form liên hệ | ✅ | ❌ |
| Tìm kiếm | ✅ | ❌ |
| SEO meta + JSON-LD | ✅ | ❌ |
| Admin CRUD đầy đủ | ✅ | ❌ |
| Menu động | ✅ | ❌ |
| Theme từ settings | ✅ | ❌ |
| Responsive | ✅ (CSS cũ) | 🟡 (CSS inline) |

**Đây là bảng quan trọng nhất của báo cáo:** bản React hiện tại **kém hơn** bản Blade cũ về mọi mặt chức năng. Việc chuyển đổi mới hoàn thành phần "xương" (routing + controller), chưa hoàn thành phần "thịt" (giao diện).
