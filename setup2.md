
### 4.3. `resources/css/` và `resources/js/`

- CSS: `app.css`, `base.css`, `components.css`, `tokens.css`, `utilities.css` + `pages/` (6 file).
- JS: `app.js` (không còn import tinymce/echo — Đợt 7 A2/A3), `bootstrap.js`, `echo.js` (chỉ nạp ở admin — Đợt 7 A3) + `modules/` (gallery, header, scroll, slider).

### 4.4. File rác cần dọn ⚠️

| File | Ghi chú |
|---|---|
| `app copy.js.txt` (nằm trong `resources/js/`) | Backup rác |
| `bundle-*.txt` (6 file) | File làm việc tạm |
| `app-tree.txt`, `views-tree.txt`, `migrations-tree.txt`, `danhsachfile.txt`, `hero-views.txt` | File làm việc tạm |
| `setup deepseek.md`, `setup2.md` | File làm việc tạm |
| `lấy noi dung file.txt`, `dot6-check.txt`, `models-rest.txt`, file tên `origin)` | File rác (rỗng hoặc tạm), phát hiện khi đọc zip |
| `rungadventure.sql` (webroot) | ⚠️ Rủi ro bảo mật nếu deploy nguyên thư mục |
| `public/info.php` | ⚠️ Lộ thông tin cấu hình PHP nếu để lên production, nên xóa |
| `storage/debugbar/*.json` | Debugbar cache — xóa định kỳ |

**Ghi chú Đợt 7:** `public/hot` **không tồn tại** trên máy hiện tại (đã kiểm tra bằng `dir public\hot`). Không cần xóa.

---

## 5. ROUTES

### 5.1. Frontend (`routes/web.php`)

- `/` → `HomeController@index`
- `/tour`, `/tour/danh-muc/{slug}`, `/tour/{slug}`
- `/diem-den`, `/diem-den/{slug}`
- `/bai-viet`, `/bai-viet/danh-muc/{slug}`, `/bai-viet/tag/{slug}`, `/bai-viet/{slug}`
- `/gioi-thieu` → `PageController@about` (slug lấy từ setting `about_page_slug`)
- `/faq`, `/lien-he` (GET+POST), `/tim-kiem`, `/trang-tinh/{slug}`
- `/sitemap.xml` → `SitemapController@index` (ngoài middleware `web`)
- `POST /tour/{tour}/review` → `Frontend\ReviewController@store` (middleware `auth`)

### 5.2. Admin (`routes/admin.php`)

Prefix `/admin`, middleware `admin`, name prefix `admin.`:

- `/dashboard` + `/dashboard/stats` (AJAX)
- CRUD + trash/restore/force-destroy cho: `danh-muc`, `diem-den`, `tour`, `bai-viet`, `tag`, `slider`, `trang-tinh`, `faq`, `menu`
- Menu: `menu/{menu}/items` (GET/PUT)
- User: CRUD (không destroy) + `toggle-active`
- Setting: `cau-hinh`, `cau-hinh/{group}` (GET/PUT)
- Contact: `lien-he`, `lien-he/{contact}`, `lien-he/{contact}/status`
- Review: `danh-gia`, `danh-gia/thung-rac`, `danh-gia/{review}/duyet`, `danh-gia/{review}/tu-choi`, `danh-gia/{review}` (destroy), `danh-gia/{id}/khoi-phuc`, `danh-gia/{id}/xoa-cung` ← **MỚI Đợt 6**
- Media: `media`, `media/thung-rac`, `media/{media}` (soft), `media/{id}/khoi-phuc`, `media/{id}/xoa-cung` ← **MỚI Đợt 2**
- `upload/image` (closure cho TinyMCE — Đợt 1 khôi phục)

---

## 6. HỆ THỐNG MENU ĐỘNG

### 6.1. Models

- `Menu` — `LOCATION_MAIN/FOOTER/TOP`, `items()` (root), `allItems()` (tất cả).
- `MenuItem` — `TARGET_SELF/BLANK`, `parent/children` self-ref, `resolvedUrl` accessor.

### 6.2. View Composer

`MenuComposer` — load menu `main` + `footer`, eager load **3 cấp** (`items.children.children`).

### 6.3. Vấn đề tồn tại ⚠️

- **Lệch cấp:** `MenuComposer` chỉ eager load 3 cấp, nhưng `MenuController@items` hỗ trợ **không giới hạn cấp**. Nếu menu có 4 cấp → cấp 4 không hiển thị frontend.
- **Chưa có `clearMenuCaches()`** trong `CacheHelper`.
- **Hiệu năng:** `MenuComposer` chạy 2 truy vấn (main + footer), mỗi truy vấn eager load 3 cấp, **không cache**, chạy ở mọi request trang khách. Xem mục 12 (A4 — chưa xử lý).

---

## 7. HỆ THỐNG SETTINGS

### 7.1. Config `config/settings.php` — 7 group

- **general** — `site_name`, `site_description`, `site_logo`, `site_favicon`
- **contact** — `contact_email` (hiển thị), `contact_notification_emails` (nhận thông báo), `contact_phone`, `contact_address`
- **social** — `social_facebook/instagram/youtube/tiktok`
- **seo** — `seo_default_title`, `seo_default_description`, `seo_keywords`, `google_analytics_id`
- **theme** — màu chính/phụ + H1–H6 (color + font)
- **about** — `about_page_slug` ← **MỚI Đợt 6**, `about_hero_title`, `about_hero_subtitle`, `about_story_title`, `about_mission_*`, `about_vision_*`, `about_values_*`
- **hero** ← **MỚI Đợt 5** — `hero_image_blog`, `hero_image_tour`, `hero_image_destination`, `hero_image_about`, `hero_image_contact`, `hero_image_faq`

### 7.2. Model

`Setting::get($key, $default)` — cache TTL 3600s. **Đợt 7 (A5):** gom toàn bộ settings vào 1 cache entry `settings.all` (cấu trúc `['values' => [...], 'groups' => [...]]`), thay vì mỗi key 1 entry `settings.{key}`. Có `set/forget/flushCache/getGroup/warmUp`.

**Hành vi giữ nguyên:** key `NULL` → trả `null` (không fallback default); key không có trong DB → trả `$default`; key đã soft-delete → không tìm thấy.

**Thêm key mới:**
- Qua admin hoặc `Setting::set()` → tự động có trong cache (đã gọi `Cache::forget('settings.all')`).
- Insert trực tiếp DB (`DB::table()->insert()`, phpMyAdmin) → phải chạy `php artisan cache:clear` sau đó.
- Khuyến nghị: luôn dùng `Setting::set()` thay vì insert trực tiếp.

**Lưu ý hiệu năng:** layout gọi `Setting::get()` ~20–30 lần/trang. Sau A5, số file cache khác nhau giảm từ 25 → 1, `flushCache()` từ N lần xóa → 1 lần. Bản hiện tại **không có static in-memory cache**, nên trong 1 request vẫn đọc file `settings.all` N lần (cùng 1 file). Nếu cần tối ưu sâu hơn, có thể thêm static — chưa làm.

---

## 8. CACHE

`app/Helpers/CacheHelper.php`:

- `clearHomepage()` — `home_sliders`, `home_featured_tours`, `home_featured_destinations`, `home_latest_posts`
- `clearDestinationCaches()` — `destination_regions`, `home_featured_destinations`
- `clearTourCaches()` — `tour_categories`, `home_featured_tours`
- `clearPostCaches()` — `post_categories`, `post_recent`, `post_popular_tags`, `home_latest_posts`
- `clearAll()`

**Chưa có:** `clearMenuCaches()`.

**Key cache khác:**
- `tour_related_{category_id}` (600s)
- `post_related_{category_id}` (600s)
- `settings.all` (3600s) ← **Đợt 7 A5** (thay cho `settings.{key}`)

---

## 9. CÁC HẠNG MỤC

| # | Hạng mục | Trạng thái | Ghi chú |
|---|---|---|---|
| 1 | Menu động | ✅ | Hỗ trợ không giới hạn cấp (controller), composer chỉ load 3 cấp |
| 2 | Dashboard thực | ✅ | Số liệu thật, biểu đồ 7 ngày, top xem nhiều, AJAX stats |
| 3 | Email thông báo liên hệ | ✅ | Queue + markdown, reply-to người gửi |
| 4 | Trang 404 + Breadcrumb | ✅ | Component Breadcrumb + BreadcrumbSchema |
| 5 | Hero Banner | ✅ | Component 3 variant |
| 6 | Review tour | ✅ | User đăng nhập đánh giá, admin duyệt; **đã có trash/restore** |
| 7 | Thông báo Real-time | ✅ | Soketi + Echo + AJAX; Contact dùng `ShouldBroadcast` (Đợt 4, xác nhận lại Đợt 7) |
| 8 | Ảnh phụ (Gallery) | ✅ | Backend + UI đầy đủ; đã validate (Đợt 1), dùng ImageService (Đợt 3) |
| 9 | Soft delete + Thùng rác | ✅ | 10 module admin + Review + Media |
| 10 | TinyMCE | ✅ | Component `<x-tinymce>`, copy vendor vào public |
| 11 | UI Components cơ bản | ✅ | alert, badge, button, checkbox, input, modal, pagination, select, textarea |
| 12 | CSS/JS module | ✅ | CSS chia base/tokens/components/utilities/pages; JS chia gallery/header/scroll/slider |
| 13 | Media Manager | ✅ **MỚI** | Trang `admin/media` + thùng rác + soft delete + force delete |
| 14 | Hero ảnh theo trang | ✅ **MỚI** | Setting `hero_image_*` + fallback slider đầu tiên |
| 15 | Page about linh hoạt | ✅ **MỚI** | Slug từ setting `about_page_slug` |
| 16 | Tối ưu hiệu năng A1–A3, A5 | ✅ **MỚI Đợt 7** | Xem mục 11, 12 |

---

## 10. CÁC QUYẾT ĐỊNH KỸ THUẬT ĐÃ THỐNG NHẤT

1. Menu động dùng **View Composer** — không dùng helper hay static method.
2. Menu động **không cache** trong lần triển khai đầu. *(Đang đề xuất xem lại — xem mục 12, A4. Chưa đổi khi chưa được xác nhận.)*
3. Chỉ dùng location `main` (header) và `footer`.
4. Dropdown menu con render bằng Alpine.js có sẵn.
5. Active state menu dùng `request()->is(...)`.
6. Settings schema để trong `config/settings.php`.
7. Cache dùng `CacheHelper` — key dạng chuỗi.
8. **Email thông báo:** Tách `contact_email` (hiển thị) và `contact_notification_emails` (nhận thông báo).
9. **Queue:** Dùng `QUEUE_CONNECTION=database`, chạy qua `composer dev`.
10. **Hero Banner:** Component 3 variant (`full/compact/minimal`).
11. **Review tour:** User đăng nhập đánh giá, admin duyệt trước khi hiển thị.
12. **Real-time:** Soketi (self-host) + Echo + AJAX stats.
13. **AJAX dashboard:** JS gọi `admin.dashboard.stats` khi có event.
14. **Ảnh phụ (Gallery):** Dùng quan hệ `media()` (morphMany), collection `gallery`.
15. **Soft delete:** Tất cả model chính dùng `SoftDeletes`. Admin có trash/restore/force-destroy.
16. **TinyMCE:** Copy vào `public/vendor/tinymce/`, dùng component `<x-tinymce>`. *(Vì vậy không cần import `tinymce` npm trong `app.js` — đã xóa Đợt 7 A2.)*
17. **UI Components:** 9 component cơ bản (alert, badge, button, checkbox, input, modal, pagination, select, textarea).
18. **Gallery upload:** Dùng `TourController@uploadGallery()` gọi trong `store()/update()`. **Đợt 3:** refactor dùng `ImageService` (thumbnail, path chuẩn).
19. **Media manager:** Trang `admin/media` (xem + filter + xóa). Upload vẫn qua form tour. **Đợt 2.**
20. **Hero ảnh:** Mỗi trang index/pages có setting riêng (`hero_image_*`), fallback ảnh đầu tiên slider. Trang chủ dùng Swiper. Trang chi tiết dùng ảnh đại diện. **Đợt 5.**
21. **Review trash:** Đồng bộ với 10 module khác — có `trash/restore/forceDestroy`. **Đợt 6.**
22. **Page about:** Slug lấy từ setting `about_page_slug` (mặc định `gioi-thieu`). **Đợt 6.**
23. **Gom settings cache (A5 — Đợt 7):** Toàn bộ settings lưu trong 1 cache entry `settings.all`, thay vì mỗi key 1 entry. `flushCache()` chỉ xóa 1 key. Không đổi schema bảng `settings`, không đổi API `Setting::get/set/forget/flushCache/getGroup`. Có thêm `warmUp()` để nạp cache chủ động sau deploy.
24. **Tách `echo.js` khỏi `app.js` (A3 — Đợt 7):** `echo.js` chỉ nạp ở layout admin qua `@vite('resources/js/echo.js')`. Trang khách không mở WebSocket. `NewContactNotification` đổi sang `ShouldBroadcast` (qua queue) — cần Soketi + `queue:work` chạy song song để realtime hoạt động.
25. **Bỏ import TinyMCE khỏi `app.js` (A2 — Đợt 7):** TinyMCE chỉ load qua `public/vendor/tinymce/tinymce.min.js` trong component `<x-tinymce>`. Không dùng `window.tinymce` toàn cục từ `app.js`.

---

## 11. VIỆC TIẾP THEO

### Đã hoàn thành 7 đợt sửa (27/10/2026):

| Đợt | Nội dung | Kết quả |
|---|---|---|
| 1 | Validate gallery + xóa route mồ côi + khôi phục `upload.image` | ✅ |
| 2 | MediaController + 2 view + route + sidebar | ✅ |
| 3 | Refactor `uploadGallery` dùng ImageService | ✅ |
| 4 | Event `ShouldBroadcast` + giới hạn HeroImageComposer | ✅ |
| 5 | Setting hero ảnh + sửa 6 view | ✅ |
| 6 | Review trash + Page about slug | ✅ |
| 7 | Hiệu năng: A1 (không áp dụng), A2 (bỏ tinymce khỏi app.js), A3 (tách echo.js sang admin + ShouldBroadcast), A5 (gom settings cache) | ✅ |

### Vấn đề còn tồn đọng:

| # | Vấn đề | Mức độ |
|---|---|---|
| 1 | Menu composer 3 cấp vs controller không giới hạn | 🟡 |
| 2 | `HeroImageComposer` vẫn query slider mỗi request (đã giảm còn 6 view) | 🟢 |
| 3 | File rác ở webroot | 🟢 |
| 4 | `dashboard/stats` route đặt sai vị trí trong `admin.php` | 🟢 |
| 5 | ~~Trang khách chuyển trang chậm~~ — **đã xử lý A2, A3, A5 (Đợt 7)**. Còn A4 (cache menu), A6–A8 (môi trường, ảnh, font) | 🟡 |

### Việc có thể làm tiếp:

- ~~**Đợt 7:** Xử lý hiệu năng A1–A3, A5~~ ✅ Hoàn thành.
- **Đợt 8 (tùy chọn):** A4 (cache menu — cần xác nhận đổi quyết định #2), A6–A8 (môi trường, ảnh, font).
- **Đợt 9+:** Nâng cấp giao diện theo mục 13.
- **SEO:** Meta tags động, OG tags, robots.txt, schema.org đầy đủ.
- **PHPUnit:** Viết test cho controller/model quan trọng.
- **Production:** Cấu hình mail thật, queue supervisor, cache, xóa file rác.
- **Luồng đánh giá user:** Tạm hoãn — hiện user phải đăng nhập (admin tạo tài khoản).

---

## 12. PHÂN TÍCH HIỆU NĂNG — "CHUYỂN TRANG LOAD LÂU" (27/10/2026)

> **Phạm vi và độ tin cậy:** phân tích này dựa trên việc đọc source trong file zip `rungadventure.zip` mà người dùng gửi. Mình chưa chạy site thật, chưa đo thời gian thực tế (Network/Lighthouse/Debugbar). Các mục dưới là **phát hiện từ code**, chưa phải số đo.
>
> **Cập nhật 27/10/2026 (sau Đợt 7):** A1 không áp dụng (không có `public/hot`). A2, A3, A5 đã xử lý — xem chi tiết bên dưới. A4, A6–A8 chưa làm.

### A. Nguyên nhân nghi ngờ, xếp theo mức ảnh hưởng

| # | Phát hiện (từ code) | Hướng xử lý đề xuất | Mức độ |
|---|---|---|---|
| A1 | ~~`public/hot` tồn tại~~ — **đã kiểm tra: KHÔNG tồn tại trên máy hiện tại** | Không cần làm | ✅ Đã xử lý (không áp dụng) |
| A2 | ~~`app.js` import tinymce~~ — **đã xóa**. Build lại: `app.js` = 269.10 kB (gzip 86.49), giảm từ >1.5 MB | Đã xóa khối import tinymce + `window.tinymce` khỏi `app.js` | ✅ Đã xử lý |
| A3 | ~~`app.js` import `./echo`~~ — **đã tách**. `echo.js` = 93.67 kB, chỉ admin tải qua `@vite('resources/js/echo.js')`. `NewContactNotification` đã đổi sang `ShouldBroadcast` | Đã tách + đổi event. Cần Soketi + `queue:work` chạy song song | ✅ Đã xử lý |
| A4 | `MenuComposer`: 2 truy vấn menu × eager load 3 cấp, không cache, chạy mọi request | `Cache::remember` + thêm `CacheHelper::clearMenuCaches()` gọi từ `MenuController` khi lưu. **Mâu thuẫn quyết định #2 → cần người dùng xác nhận** | 🟠 Chưa làm |
| A5 | ~~Layout gọi `Setting::get()` ~20–30 lần/trang, mỗi lần đọc 1 file cache~~ — **đã gom vào 1 key `settings.all`**. `flushCache()` từ N lần xóa → 1 lần | Đã sửa `Setting.php` | ✅ Đã xử lý |
| A6 | Môi trường: `bootstrap/cache` chưa có config/route cache; `SESSION_DRIVER=file`, `CACHE_DRIVER=file` trên Windows/Laragon | Production: `php artisan config:cache && route:cache && view:cache`. Dev: cân nhắc `SESSION_DRIVER=database` | 🟡 Chưa làm |
| A7 | Ảnh: ảnh webp lớn nhất ~468 KB; chỉ 11/28 thẻ `<img>` (frontend) có `loading="lazy"` | Resize khi upload (`ImageService`), thêm `loading="lazy"` + `width/height`; hero ảnh đầu dùng `fetchpriority="high"` | 🟡 Chưa làm |
| A8 | Font Google: tải tối đa 7 family × 5 độ đậm | Giảm độ đậm không dùng (ví dụ bỏ 800), cân nhắc self-host font | 🟡 Chưa làm |

### B. Thứ tự đề xuất

1. ~~A1~~ ✅ Không áp dụng.
2. ~~A2~~ ✅ Hoàn thành.
3. ~~A3~~ ✅ Hoàn thành (kèm event `ShouldBroadcast`).
4. ~~A5~~ ✅ Hoàn thành. **A4 chưa làm** — cần xác nhận đổi quyết định #2.
5. A6 → A8 khi chuẩn bị production.

### C. Cách kiểm tra sau khi sửa

- Chrome DevTools → Network: xem tổng dung lượng JS, có request tới `:5173` hoặc `:6001` không.
- Laravel Debugbar (đang có trong `storage/debugbar`): xem số query và thời gian mỗi trang.
- Lighthouse (bản Mobile) trước và sau, ghi lại điểm để so sánh.

### D. Việc bảo mật phát hiện được khi đọc zip

- File `.env` nằm trong zip chứa mật khẩu DB, mail, admin. **Nên đổi các mật khẩu đó** và không đóng gói `.env` khi chia sẻ code.
- `public/info.php` và `rungadventure.sql` trong webroot: xóa trước khi deploy (xem mục 4.4).

### E. Ghi chú vận hành sau Đợt 7

- **Realtime admin cần 2 tiến trình chạy song song:** `php artisan queue:work` (xử lý broadcast qua queue) + Soketi (`soketi start`). Nếu thiếu 1 trong 2, chuông thông báo admin sẽ không nhận tin mới realtime.
- **Khi Soketi tắt:** job broadcast fail và tích vào `failed_jobs`. Kiểm tra bằng `php artisan queue:failed`. Retry bằng `php artisan queue:retry all`.
- **`admin-topbar.blade.php`** dùng guard `if (typeof window.Echo === 'undefined') return;` — an toàn về thứ tự load, nhưng nếu mạng rất chậm có thể realtime "im lặng" (không lỗi) cho đến khi F5. Chấp nhận được, không cần sửa.
- **Kích thước bundle sau Đợt 7:** `app.js` 269.10 kB (gzip 86.49), `app.css` 49.37 + 84.16 kB (gzip 6.70 + 13.48), `echo.js` 93.67 kB (gzip 26.62 — chỉ admin tải).
- **Cache settings:** thêm key qua admin hoặc `Setting::set()` → tự động có trong cache. Insert trực tiếp DB → phải `php artisan cache:clear` sau đó.

---

## 13. KẾ HOẠCH NÂNG CẤP GIAO DIỆN (ĐỀ XUẤT — CHƯA TRIỂN KHAI)

> Tham chiếu: ảnh thiết kế trang chủ do người dùng cung cấp (hero toàn màn hình, thanh tìm kiếm nổi, card tour, khối điểm đến nền xanh đậm, khối "Vì sao chọn Rung Adventure?").
>
> **Nguyên tắc:** làm xong mục 12 (A1–A4) trước. Nâng giao diện nghĩa là thêm ảnh/hiệu ứng, nếu bundle vẫn nặng thì trang sẽ chậm hơn. Mọi thay đổi vẫn tuân thủ mục 3.6 (không đổi kiến trúc, không nâng Tailwind lên v4).

### 13.1. Bóc tách thiết kế → công cụ

| Phần trong thiết kế | Công cụ đề xuất | Ghi chú |
|---|---|---|
| Hero: đổi slide, ảnh zoom chậm, mũi tên | Swiper (fade + autoplay) + CSS scale | Đã có Swiper |
| Bộ đếm "01 / 04", thanh tiến trình dọc | Swiper pagination `fraction`/`progressbar` + CSS | |
| Gradient phủ ảnh để chữ dễ đọc | Tailwind gradient | |
| Header trong suốt đè hero, đặc khi cuộn | Alpine (`scroll` listener) | Đã có `modules/header.js` |
| Nút "Xem video hành trình" | GLightbox (hỗ trợ YouTube/Vimeo) | Đã có |
| Chữ viết tay ("bản linh", "Đi để trưởng thành") | Google Font có hỗ trợ tiếng Việt: Caveat / Dancing Script / Playwrite VN | Chỉ tải độ đậm cần dùng |
| Nhấn từ trong tiêu đề do admin nhập | Cú pháp `*từ*` → `<span class="font-script text-green-400">` trong Blade | Cần sửa view hero + escape an toàn |
| Cạnh giấy xé, hoa văn đồng mức | SVG + `mask-image`/background | Không cần thư viện |
| Thanh tìm kiếm nổi (điểm đến, thời gian, số người) | `<select>` + Alpine (hoặc Tom Select); Flatpickr nếu chọn ngày | Cần bổ sung filter ở `Frontend\TourController@index` |
| Card tour: badge Bán chạy/Mới/Yêu thích | Cột `badge` trong bảng `tours` + Tailwind | Cần migration |
| Card tour: "3 ngày 2 đêm", "12–20 người" | Dữ liệu thật trong bảng `tours` | **Kiểm tra bảng đã có cột nào chưa; không bịa dữ liệu** |
| Icon | Heroicons/Lucide dạng SVG inline (`blade-ui-kit/blade-icons`) | Không dùng icon font |
| Khối điểm đến nền xanh + 4 thumbnail | Grid/Swiper + gradient | |
| Nút lên đầu trang | Alpine | |

### 13.2. Về GSAP (đề xuất, chưa quyết)

- **Dùng khi cần:** timeline phối hợp nhiều thứ cùng lúc (đổi slide + chữ ra vào + bộ đếm + thanh tiến trình), parallax ảnh hero bằng ScrollTrigger (`scrub`), tách chữ tiêu đề bằng SplitText, section ghim khi cuộn.
- **Không cần cho:** hover card, đổi màu nút, dropdown (Tailwind), slider (Swiper).
- **Trùng chức năng:** nếu dùng GSAP + ScrollTrigger thì bỏ AOS để khỏi nạp hai thư viện làm cùng việc.
- **Kích thước (ước tính):** GSAP core ~25–30 KB gzip, ScrollTrigger ~10–12 KB. **Kiểm tra lại số liệu và điều khoản giấy phép trên trang chính thức của GSAP trước khi dùng cho dự án thương mại.**
- **Lenis** (cuộn mượt, ~3 KB): tùy chọn, tắt trên mobile/thiết bị yếu nếu thấy giật.
- **Cách tích hợp để không làm chậm web:**
  1. Cài qua npm, import từng phần (`gsap`, `gsap/ScrollTrigger`), không import toàn bộ.
  2. Tách `resources/js/modules/home-animations.js`, chỉ nạp ở trang chủ bằng dynamic import (kiểm tra `document.querySelector('[data-home-hero]')`).
  3. Bọc bằng `gsap.matchMedia()` với `(prefers-reduced-motion: no-preference)`.
  4. Chỉ animate `transform` và `opacity`.
  5. Ảnh hero đầu tiên phải hiện ngay (không để JS ẩn rồi mới hiện).
  6. Nối Swiper với GSAP qua sự kiện `slideChangeTransitionStart`.
- **Lộ trình gợi ý:** bắt đầu chỉ với GSAP core + ScrollTrigger cho hero; Lenis và SplitText thêm sau nếu thật sự cần.

### 13.3. Thứ tự triển khai đề xuất

1. Hero (Swiper fade, header trong suốt, font viết tay, gradient, bộ đếm).
2. Thanh tìm kiếm nổi + filter ở `TourController@index`.
3. Card tour có badge + hover (cần migration `badge`, kiểm tra cột thời lượng/sức chứa).
4. Cạnh giấy xé, hoa văn, khối điểm đến nền xanh, khối "Vì sao chọn chúng tôi".
5. Nút liên hệ nổi Zalo/Hotline, khối đánh giá khách hàng (dùng bảng `reviews`), schema.org `TouristTrip` ở `seo.blade.php`.
6. Hiệu ứng GSAP (nếu quyết định dùng).

### 13.4. Ảnh (yếu tố quyết định chất lượng)

- Hero cần ảnh thật chất lượng cao, tỷ lệ 16:9, xuất WebP/AVIF, mục tiêu < ~200 KB (mục tiêu đề xuất, cần kiểm tra thực tế).
- Ảnh đầu tiên `preload` + `fetchpriority="high"`, các ảnh sau lazy load.
- Mở rộng `ImageService` để tạo nhiều kích cỡ (`srcset`).

### 13.5. Cần người dùng xác nhận trước khi làm

- Có dùng GSAP/Lenis không, hay chỉ Swiper + CSS + Alpine (nhẹ hơn)?
- Cache menu (xem lại quyết định #2) có đồng ý không?
- Bảng `tours` hiện có những cột nào cho thời lượng, sức chứa, badge? (cần xem migration thật trước khi thêm cột)
- Số liệu hotline, giá, điểm đến trong ảnh thiết kế chỉ là mẫu; dùng dữ liệu thật từ DB/settings.

---

## 14. CÁCH DÙNG FILE BÀN GIAO NÀY

- File này là nguồn thông tin chính thức về trạng thái dự án.
- Khi mở cuộc trò chuyện mới với AI → **dán toàn bộ file này vào đầu cuộc trò chuyện**.
- Cập nhật file mỗi khi hoàn thành một hạng mục hoặc có quyết định kỹ thuật mới.
- Không giả định những gì mới lên kế hoạch đã thực sự được triển khai — luôn đối chiếu với source code thực tế.
- Mục 12 và 13 là **phát hiện/đề xuất**, chưa phải thay đổi đã thực hiện.
- **Đợt 7 đã hoàn thành** (A1–A3, A5). A4 và A6–A8 vẫn là đề xuất, chưa triển khai.