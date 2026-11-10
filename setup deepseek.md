# BÀN GIAO DỰ ÁN RUNG ADVENTURE

> Cập nhật lần cuối: 19/10/2026
> Phiên làm việc: chat với AI

---

## 1. THÔNG TIN DỰ ÁN

- **Tên:** Rung Adventure
- **Loại:** Website du lịch (cung cấp thông tin tour, điểm đến, bài viết, cẩm nang)
- **KHÔNG PHẢI:** hệ thống đặt tour / thương mại điện tử
- **Đường dẫn gốc:** `C:\laragon\www\rungadventure`
- **Database:** `rungadventure`

---

## 2. CÔNG NGHỆ ĐANG DÙNG

| Thành phần | Phiên bản | Ghi chú |
|---|---|---|
| PHP | 8.1.10 | |
| Laravel | 10.x | |
| Tailwind CSS | 3.4.19 | KHÔNG nâng lên v4 |
| Vite | 5.x | |
| Alpine.js | 3.17.4 | Đã có sẵn, dùng cho tương tác |
| Swiper | 14.3.0 | |
| AOS | 2.3.4 | |
| GLightbox | 3.3.1 | |
| Intervention Image | 3.11 | |
| Soketi | 1.x | WebSocket server (thay thế Pusher Cloud) |
| Laravel Echo | 2.x | Lắng nghe event real-time |
| Pusher JS | 8.x | Client WebSocket |
| MySQL | — | |
| PHPUnit | 10.1 | |

---

## 3. QUY TẮC LÀM VIỆC VỚI AI

### 3.1. Quy tắc gửi file

- Khi nhờ AI sửa file → AI PHẢI gửi lại **TOÀN BỘ file** sau khi sửa, không chỉ gửi đoạn code cần thay.
- Người dùng chỉ cần **copy đè**, không phải tự tìm đoạn để thay.
- Nếu file dài → chia phần, ghi rõ `(1/N)`, `(2/N)`...
- Ghi rõ **đường dẫn file** ở đầu mỗi file gửi.

### 3.2. Quy tắc làm việc với source code

- Kiểm tra cấu trúc thư mục, file cấu hình, phiên bản framework trước khi sửa.
- Xác định nguyên nhân vấn đề trước khi đề xuất sửa.
- Chỉ thay đổi những phần cần thiết.
- Không giả định file/route/bảng dữ liệu/chức năng đã tồn tại khi chưa có bằng chứng.
- Không nói đã trực tiếp sửa file, chạy lệnh, kiểm thử nếu thực tế không làm được.
- Khi chỉ có thể làm việc qua chat → cung cấp code đầy đủ theo từng đường dẫn file.

### 3.3. Quy trình nhiệm vụ

Mỗi nhiệm vụ quan trọng theo trình tự:

1. Phân tích yêu cầu và tiêu chí hoàn thành.
2. Kiểm tra source code liên quan.
3. Xác định nguyên nhân, phương án, rủi ro.
4. Liệt kê file dự kiến tạo/sửa.
5. Triển khai theo thứ tự phụ thuộc.
6. Kiểm tra tính nhất quán toàn bộ thay đổi.
7. Chạy test/build nếu có môi trường.
8. Báo cáo kết quả thực tế.

### 3.4. Cấu trúc báo cáo

Với nhiệm vụ triển khai, dùng bố cục:

- A. Phân tích yêu cầu và hiện trạng.
- B. Phương án kỹ thuật.
- C. Danh sách file tạo mới/chỉnh sửa.
- D. Nội dung code theo từng file.
- E. Kết quả kiểm thử thực tế.
- F. Những phần chưa xác minh / còn tồn đọng.

Với câu hỏi đơn giản, trả lời trực tiếp.

### 3.5. Những điều KHÔNG ĐƯỢC LÀM

- ❌ Khởi tạo lại dự án.
- ❌ Viết lại toàn bộ website.
- ❌ Tự ý đổi kiến trúc đã thống nhất.
- ❌ Chuyển sang React/Vue/Inertia/Livewire khi chưa được đồng ý.
- ❌ Nâng cấp Laravel hoặc thư viện quan trọng khi chưa phân tích tác động.
- ❌ Tuyên bố đã kiểm thử thành công khi chưa thực hiện.
- ❌ Bịa dữ liệu (giá tour, lịch khởi hành, đánh giá, số chỗ...).
- ❌ Tạo class/interface/service/repository chỉ để trông chuyên nghiệp.
- ❌ Đưa mật khẩu, API key vào source code.
- ❌ Vô hiệu hóa bảo mật để chức năng chạy được.
- ❌ Thay đổi giao diện ngoài phạm vi khi chỉ yêu cầu sửa backend.

---

## 4. CẤU TRÚC THƯ MỤC HIỆN TẠI

### 4.1. `app/`
app/
├── Console/Kernel.php
├── Events/
│ ├── NewContactNotification.php ← MỚI TẠO (real-time)
│ └── NewReviewNotification.php ← MỚI TẠO (real-time)
├── Exceptions/Handler.php
├── Helpers/
│ └── CacheHelper.php
├── Http/
│ ├── Controllers/
│ │ ├── Admin/
│ │ │ ├── CategoryController.php
│ │ │ ├── ContactController.php
│ │ │ ├── DashboardController.php ← ĐÃ SỬA (thêm method stats)
│ │ │ ├── DestinationController.php
│ │ │ ├── FaqController.php
│ │ │ ├── MenuController.php
│ │ │ ├── PageController.php
│ │ │ ├── PostController.php
│ │ │ ├── ReviewController.php ← MỚI TẠO
│ │ │ ├── SettingController.php
│ │ │ ├── SliderController.php
│ │ │ ├── TagController.php
│ │ │ ├── TourController.php
│ │ │ └── UserController.php
│ │ ├── Auth/LoginController.php
│ │ ├── Frontend/
│ │ │ ├── ContactController.php ← ĐÃ SỬA (dispatch event)
│ │ │ ├── DestinationController.php
│ │ │ ├── FaqController.php
│ │ │ ├── PageController.php
│ │ │ ├── PostController.php
│ │ │ ├── ReviewController.php ← MỚI TẠO
│ │ │ ├── SearchController.php
│ │ │ └── TourController.php
│ │ ├── Controller.php
│ │ ├── HomeController.php
│ │ └── SitemapController.php
│ ├── Middleware/
│ │ ├── EnsureUserIsAdmin.php
│ │ └── ... (mặc định Laravel)
│ ├── Requests/
│ │ ├── Admin/
│ │ │ ├── SaveMenuItemsRequest.php
│ │ │ ├── Store/Update{Categ,Dest,Faq,Menu,Page,Post,Slider,Tag,Tour,User}Request.php
│ │ │ └── UpdateContactStatusRequest.php
│ │ ├── Auth/LoginRequest.php
│ │ └── Frontend/StoreContactRequest.php
│ └── Kernel.php
├── Mail/
│ ├── NewContactNotification.php ← MỚI TẠO
│ └── (ContactConfirmation đã bỏ)
├── Models/
│ ├── Category.php
│ ├── Contact.php
│ ├── Destination.php
│ ├── Faq.php
│ ├── Media.php
│ ├── Menu.php
│ ├── MenuItem.php
│ ├── Page.php
│ ├── Post.php
│ ├── Review.php ← MỚI TẠO
│ ├── Setting.php
│ ├── Slider.php
│ ├── Tag.php
│ ├── Tour.php ← ĐÃ SỬA (thêm reviews, average_rating)
│ ├── TourItinerary.php
│ └── User.php
├── Providers/
│ ├── AppServiceProvider.php ← ĐÃ SỬA (đăng ký MenuComposer + HeroImageComposer)
│ ├── AuthServiceProvider.php
│ ├── BroadcastServiceProvider.php
│ ├── EventServiceProvider.php
│ └── RouteServiceProvider.php
├── Services/
│ └── ImageService.php
└── View/
├── Components/
│ ├── Breadcrumb.php ← MỚI TẠO
│ ├── BreadcrumbSchema.php
│ ├── DestinationCard.php
│ ├── HeroBanner.php ← MỚI TẠO
│ ├── JsonLd.php
│ ├── PostCard.php
│ └── TourCard.php
└── Composers/
├── HeroImageComposer.php ← MỚI TẠO
└── MenuComposer.php



### 4.2. `resources/views/`
resources/views/
├── admin/
│ ├── dashboard.blade.php ← ĐÃ SỬA (data-stat, latest-contacts-list)
│ ├── categories/...
│ ├── contacts/...
│ ├── destinations/...
│ ├── faqs/...
│ ├── menus/
│ │ ├── items.blade.php ← ĐÃ SỬA (hỗ trợ 3 cấp)
│ │ └── ...
│ ├── pages/...
│ ├── posts/...
│ ├── reviews/
│ │ └── index.blade.php ← MỚI TẠO
│ ├── settings/...
│ ├── sliders/...
│ ├── tags/...
│ ├── tours/...
│ └── users/...
├── auth/login.blade.php
├── blog/...
├── components/
│ ├── breadcrumb.blade.php ← MỚI TẠO
│ ├── hero-banner.blade.php ← MỚI TẠO
│ └── ...
├── destination/...
├── emails/
│ └── new-contact.blade.php ← MỚI TẠO
├── errors/
│ └── 404.blade.php ← MỚI TẠO
├── layouts/
│ ├── admin.blade.php
│ ├── app.blade.php
│ └── partials/
│ ├── admin-sidebar.blade.php
│ ├── admin-topbar.blade.php ← ĐÃ SỬA (real-time + AJAX)
│ ├── footer.blade.php
│ ├── header.blade.php
│ ├── menu-item-desktop.blade.php ← MỚI TẠO
│ └── menu-item-mobile.blade.php ← MỚI TẠO
├── pages/{about,contact,faq,show}.blade.php
├── partials/home/...
├── search/index.blade.php
├── tour/...
│ └── partials/reviews.blade.php ← MỚI TẠO
├── home.blade.php
└── welcome.blade.php


### 4.3. `database/migrations/`
2014_10_12_000000_create_users_table.php
...
2026_10_09_101021_create_menu_items_table.php
2026_10_13_083822_update_settings_type_enum.php
2026_10_19_xxxxxx_create_reviews_table.php ← MỚI TẠO



---

## 5. ROUTES

### 5.1. Frontend (`routes/web.php`)

- `/` → `HomeController@index`
- `/tour`, `/tour/danh-muc/{slug}`, `/tour/{slug}`
- `/diem-den`, `/diem-den/{slug}`
- `/bai-viet`, `/bai-viet/danh-muc/{slug}`, `/bai-viet/tag/{slug}`, `/bai-viet/{slug}`
- `/gioi-thieu`
- `/faq`
- `/lien-he` (GET + POST)
- `/tim-kiem`
- `/trang-tinh/{slug}`
- `/sitemap.xml` → `SitemapController@index`
- `POST /tour/{tour}/review` → `Frontend\ReviewController@store` (middleware `auth`) ← MỚI

### 5.2. Admin (`routes/admin.php`)

Prefix `/admin`, middleware `admin`, name prefix `admin.`:

- `/dashboard` → `DashboardController@index`
- `/dashboard/stats` → `DashboardController@stats` (AJAX) ← MỚI
- CRUD cho: `danh-muc`, `diem-den`, `tour`, `bai-viet`, `tag`, `slider`, `trang-tinh`, `faq`, `menu`, `tai-khoan`, `lien-he`
- Menu có thêm: `menu/{menu}/items` (GET), `menu/{menu}/items` (PUT)
- Setting: `cau-hinh`, `cau-hinh/{group}`
- User: toggle-active
- **Review:** `danh-gia` (index), `danh-gia/{review}/duyet` (approve), `danh-gia/{review}/tu-choi` (reject), `danh-gia/{review}` (destroy) ← MỚI

---

## 6. HỆ THỐNG MENU ĐỘNG

### 6.1. Models

**`Menu`** — fillable: `name`, `location`, `description`, `is_active`. Constants: `LOCATION_MAIN='main'`, `LOCATION_FOOTER='footer'`, `LOCATION_TOP='top'`. Quan hệ: `items()` (chỉ root, sắp sort_order), `allItems()` (tất cả, sắp sort_order).

**`MenuItem`** — fillable: `menu_id`, `parent_id`, `label`, `url`, `route_name`, `target`, `sort_order`, `is_active`. Constants: `TARGET_SELF='_self'`, `TARGET_BLANK='_blank'`. Quan hệ: `menu()`, `parent()`, `children()`. Scopes: `active()`, `root()`, `ordered()`. Accessor: `resolved_url`.

### 6.2. Dữ liệu trong DB

| ID | Name | Location | Số item | Dùng |
|---|---|---|---|---|
| 1 | Menu 1 | top | 1 | Không dùng |
| 2 | Menu 2 | header | 0 | Không dùng |
| 3 | [SAMPLE] Menu chính | main | 6 | Header |
| 4 | [SAMPLE] Menu footer | footer | 5 | Footer |

### 6.3. View Composer

`app/View/Composers/MenuComposer.php` — load menu `main` và `footer`, chỉ lấy item `is_active`, eager load children. Đăng ký trong `AppServiceProvider::boot()`.

### 6.4. File đã sửa/tạo

- ✅ `app/View/Composers/MenuComposer.php` — TẠO MỚI
- ✅ `app/Providers/AppServiceProvider.php` — đăng ký composer
- ✅ `resources/views/layouts/partials/header.blade.php`
- ✅ `resources/views/layouts/partials/footer.blade.php`
- ✅ `app/Http/Controllers/Admin/MenuController.php`
- ✅ `app/Http/Requests/Admin/SaveMenuItemsRequest.php`
- ✅ `resources/views/admin/menus/items.blade.php`
- ✅ `resources/views/layouts/partials/menu-item-desktop.blade.php` — TẠO MỚI
- ✅ `resources/views/layouts/partials/menu-item-mobile.blade.php` — TẠO MỚI

### 6.5. Bug đang tồn tại

Không có. Menu động hoạt động đầy đủ.

---

## 7. HỆ THỐNG SETTINGS

### 7.1. Config

`config/settings.php` — schema tĩnh với 6 group:

- **general** — `site_name`, `site_description`, `site_logo`, `site_favicon`
- **contact** — `contact_email`, `contact_notification_emails`, `contact_phone`, `contact_address`
- **social** — `social_facebook`, `social_instagram`, `social_youtube`, `social_tiktok`
- **seo** — `seo_default_title`, `seo_default_description`, `seo_keywords`, `google_analytics_id`
- **theme** — màu chính/phụ + H1–H6 color + font
- **about** — nội dung trang giới thiệu

**Lưu ý:** `contact_email` (hiển thị) và `contact_notification_emails` (nhận thông báo) là 2 setting riêng biệt.

### 7.2. Model

`Setting::get($key, $default = null)` — đọc giá trị setting.

### 7.3. Admin

`SettingController` với index, edit, update.

---

## 8. CACHE

`app/Helpers/CacheHelper.php` — dùng `Cache` facade với key dạng chuỗi.

**Đã có:**
- `clearHomepage()`
- `clearDestinationCaches()`
- `clearTourCaches()`
- `clearPostCaches()`
- `clearAll()`

**Chưa có:** `clearMenuCaches()`.

---

## 9. CÁC HẠNG MỤC (CẬP NHẬT)

| # | Hạng mục | Trạng thái | Ghi chú |
|---|---|---|---|
| 1 | Menu động | ✅ HOÀN THÀNH | Hỗ trợ 3 cấp, admin nâng cao |
| 2 | Dashboard thực | ✅ HOÀN THÀNH | Số liệu thật, biểu đồ, top xem nhiều |
| 3 | Email thông báo liên hệ | ✅ HOÀN THÀNH | Gửi nhiều email, dùng Queue |
| 4 | Trang 404 + Breadcrumb | ✅ HOÀN THÀNH | Đã tạo 404, component Breadcrumb, áp dụng toàn site |
| 5 | Hero Banner (dùng chung) | ✅ HOÀN THÀNH | Component `<x-hero-banner>` hỗ trợ 3 variant (full/compact/minimal) |
| 6 | Review tour | ✅ HOÀN THÀNH | Người dùng đánh giá sao + bình luận, admin duyệt |
| 7 | Thông báo Real-time | ✅ HOÀN THÀNH | Soketi + Echo + AJAX, cập nhật không cần reload |
| 8 | **Ảnh phụ (Gallery) cho Tour** | ✅ |
---

## 10. CÁC QUYẾT ĐỊNH KỸ THUẬT ĐÃ THỐNG NHẤT

1. Menu động dùng **View Composer** — không dùng helper hay static method.
2. Menu động **không cache** trong lần triển khai đầu.
3. Chỉ dùng location `main` (header) và `footer`.
4. Dropdown menu con render bằng Alpine.js có sẵn.
5. Active state menu dùng `request()->is(...)`.
6. Settings schema để trong `config/settings.php`.
7. Cache dùng `CacheHelper` — key dạng chuỗi.
8. **Email thông báo:** Tách `contact_email` (hiển thị) và `contact_notification_emails` (nhận thông báo).
9. **Queue:** Dùng `QUEUE_CONNECTION=database`, chạy qua `composer dev`.
10. **Hero Banner:** Dùng component `<x-hero-banner>` với 3 variant: `full`, `compact`, `minimal`. Ảnh nền ưu tiên lấy từ slider hoặc ảnh đại diện của từng item.
11. **Review tour:** Chỉ người dùng đã đăng nhập mới được đánh giá, admin duyệt trước khi hiển thị.
12. **Real-time:** Dùng **Soketi** (self-host) thay vì Pusher Cloud, cấu hình qua driver `pusher` với `host=127.0.0.1`, `port=6001`.
13. **AJAX cập nhật dashboard:** Khi có event real-time, JS gọi AJAX `admin.dashboard.stats` để cập nhật số liệu, không reload trang.
14. **Ảnh phụ (Gallery):** Dùng quan hệ `media()` (morphMany) có sẵn trong model Tour. Upload qua route `admin.upload.image` và lưu vào bảng `media` với collection `gallery`.
---

## 11. VIỆC TIẾP THEO

### Tất cả các hạng mục chính đã hoàn thành.

**Các việc có thể làm tiếp theo (nếu có nhu cầu):**
- Tối ưu SEO (sitemap, robots, meta tags động).
- Kiểm thử toàn diện (PHPUnit).
- Chuẩn bị deploy production (cấu hình mail thật, queue supervisor, cache...).
- Bổ sung tính năng mới (nếu có yêu cầu).

---

## 12. CÁCH DÙNG FILE BÀN GIAO NÀY

- File này là nguồn thông tin chính thức về trạng thái dự án.
- Khi mở cuộc trò chuyện mới với AI → **dán toàn bộ file này vào đầu cuộc trò chuyện**.
- Cập nhật file mỗi khi hoàn thành một hạng mục hoặc có quyết định kỹ thuật mới.
- Không giả định những gì mới lên kế hoạch đã thực sự được triển khai — luôn đối chiếu với source code thực tế.