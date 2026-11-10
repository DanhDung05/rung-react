# RÙNG ADVENTURE — KIỂM KÊ FRONTEND PUBLIC

> **Giai đoạn:** chuẩn bị redesign Frontend public
> **Ngày:** 10/11/2026
> **Phạm vi:** toàn bộ route/controller/view/asset phía public (không gồm Admin)
> **Nguyên tắc:** chỉ đọc — **không sửa code, không xóa Blade, không đổi DB, không đổi dependency**

---

## 0. TÓM TẮT ĐIỀU HÀNH

| Chỉ số | Giá trị |
|---|---:|
| Route frontend | **21** (15 trang GET + 4 POST + sitemap + đăng nhập) |
| Trang public dùng Inertia | 14 component |
| Trang public **đã có React** | **1** (`Home`) |
| Trang public **đang rơi vào `GenericPage`** | **13 / 14 (93%)** |
| Blade frontend (tham chiếu thiết kế) | **69 file / 6.194 dòng** |
| Dữ liệu public | 8 tour published, 13 điểm đến, 9 bài viết, 10 FAQ, 1 slider, 0 review approved |
| Test frontend tự động | **0** (chỉ có test server-side) |

### Ba kết luận quan trọng nhất

1. **Website public hiện gần như chưa có giao diện React.** Chỉ trang chủ có component thật (bản rút gọn), 13 trang còn lại hiển thị bảng dữ liệu thô của `GenericPage`. Bản Blade cũ (đã ngắt khỏi routing) mới là thiết kế tham chiếu đầy đủ.
2. **Không tồn tại chức năng "Đặt tour".** Tìm toàn bộ `app/`, `routes/`, `database/migrations`, `resources` với `booking|dat-tour|order|checkout|payment|thanh-toan`: **0 kết quả**. Kênh chuyển đổi duy nhất hiện tại là **form liên hệ** và **đánh giá tour** (yêu cầu đăng nhập).
3. **Có 3 khoảng trống dữ liệu** sẽ chặn việc dựng trang nếu không bổ sung controller: review của tour chưa được eager-load, `hero_image_*` và `about_*` không nằm trong shared props Inertia.

---

## 1. PHƯƠNG PHÁP

- `php artisan route:list --json` → đối chiếu 21 route frontend.
- Đọc 9 controller frontend + `HomeController` + `SitemapController`, ghi lại chính xác props.
- Inventory 69 file Blade frontend (line count, include, component, JSON-LD, Alpine).
- Kiểm tra asset: CSS, JS, ảnh upload, `public/build`, `public/hot`.
- Grep chức năng: booking/order, filter, review, SEO/schema, sitemap.
- So khớp `Inertia::render()` với file trong `resources/js/Pages` (14 component public, 1 có file).

**Giới hạn:** không chạy trình duyệt để đo Lighthouse; không đọc hết 6.194 dòng Blade (đọc cấu trúc qua heading/include/field).

---

## 2. HIỆN TRẠNG KỸ THUẬT

### 2.1. Stack

| Thành phần | Phiên bản |
|---|---|
| Laravel / PHP | 10.50.4 / 8.1.10 |
| Inertia Laravel / React | 2.0.28 / 18.3.1 |
| Vite / Tailwind | 5.4 / 3.4 |
| Alpine.js + Swiper + AOS + GLightbox | 3.17 / 14.3 / 2.3 / 3.3 |
| TinyMCE | 8.10 (self-host `public/vendor/tinymce`) |

### 2.2. Trạng thái render thực tế

| Trang | Component Inertia | File React | Kết quả hiện tại |
|---|---|:---:|---|
| `/` | `Home` | ✅ | Hero + 3 khối card + giá trị + CTA (bản rút gọn) |
| `/tour` | `Tour/Index` | ❌ | **GenericPage** (bảng thô) |
| `/tour/{slug}` | `Tour/Show` | ❌ | **GenericPage** |
| `/diem-den` | `Destination/Index` | ❌ | **GenericPage** |
| `/diem-den/{slug}` | `Destination/Show` | ❌ | **GenericPage** |
| `/bai-viet` | `Blog/Index` | ❌ | **GenericPage** |
| `/bai-viet/danh-muc/{slug}` | `Blog/Category` | ❌ | **GenericPage** |
| `/bai-viet/tag/{slug}` | `Blog/Tag` | ❌ | **GenericPage** |
| `/bai-viet/{slug}` | `Blog/Show` | ❌ | **GenericPage** |
| `/gioi-thieu` | `Pages/About` | ❌ | **GenericPage** |
| `/faq` | `Pages/Faq` | ❌ | **GenericPage** |
| `/lien-he` | `Pages/Contact` | ❌ | **GenericPage** (không có form) |
| `/tim-kiem` | `Search/Index` | ❌ | **GenericPage** |
| `/trang-tinh/{slug}` | `Pages/Show` | ❌ | **GenericPage** |
| 404 | — | ❌ | Blade `errors/404.blade.php` |

**Hệ quả:** khách không thể lọc tour, không xem chi tiết tour, không gửi liên hệ, không tìm kiếm, không đọc bài viết tử tế.

### 2.3. Asset hiện có

| Nhóm | Chi tiết | Đánh giá |
|---|---|---|
| `resources/css/app.css` | 12 KB, chỉ 26 dòng — **dòng 23 là khối CSS minify khổng lồ** cho React/Home | Khó bảo trì |
| `resources/css/tokens.css` | 169 dòng design tokens (màu, font, spacing, radius) | Tốt, tái sử dụng được |
| `resources/css/base.css` | 139 dòng reset/typography | Tốt |
| `resources/css/components.css` | 4 dòng | Gần như trống |
| `resources/css/utilities.css` | 7 dòng | Gần như trống |
| `resources/css/pages/*.css` | home 57, tour-list 47, tour-detail 92, blog 251, destination 75 | **Chỉ phục vụ Blade đã chết** |
| `resources/css/react-ui.css` | 29.9 KB / 1.763 dòng | **Dành cho Admin**, không dùng cho public |
| `resources/js/app.js` | 39 dòng: Alpine + Swiper/AOS/GLightbox + 4 module | Không được React dùng; chỉ nạp cho trang 404 |
| `resources/js/modules/*.js` | header, slider, gallery, scroll | Chỉ Blade dùng |
| Ảnh upload | 14 file / 7,65 MB trong `storage/app/public/media` | Nặng, chưa có biến thể responsive |
| `public/build` | Manifest 112 entry, code-split theo trang | Tốt |
| `public/hot` | **Đang tồn tại** (`http://[::1]:5173`, 13:35) | ⚠ Chế độ dev server |
| `public/favicon.ico` | **0 byte** | ⚠ Hỏng |

---

## 3. DANH SÁCH ĐẦY ĐỦ ROUTES FRONTEND (21)

| # | Method | URI | Name | Controller | Trả về |
|---:|---|---|---|---|---|
| 1 | GET | `/` | `home` | `HomeController@index` | Inertia `Home` |
| 2 | GET | `/tour` | `tours.index` | `Frontend\TourController@index` | Inertia `Tour/Index` |
| 3 | GET | `/tour/danh-muc/{slug}` | `tours.category` | `@category` | **redirect** → `tours.index?category=` |
| 4 | GET | `/tour/{slug}` | `tours.show` | `@show` | Inertia `Tour/Show` |
| 5 | GET | `/diem-den` | `destinations.index` | `Frontend\DestinationController@index` | Inertia `Destination/Index` |
| 6 | GET | `/diem-den/{slug}` | `destinations.show` | `@show` | Inertia `Destination/Show` |
| 7 | GET | `/bai-viet` | `posts.index` | `Frontend\PostController@index` | Inertia `Blog/Index` |
| 8 | GET | `/bai-viet/danh-muc/{slug}` | `posts.category` | `@category` | Inertia `Blog/Category` |
| 9 | GET | `/bai-viet/tag/{slug}` | `posts.tag` | `@tag` | Inertia `Blog/Tag` |
| 10 | GET | `/bai-viet/{slug}` | `posts.show` | `@show` | Inertia `Blog/Show` |
| 11 | GET | `/gioi-thieu` | `about` | `Frontend\PageController@about` | Inertia `Pages/About` |
| 12 | GET | `/faq` | `faq` | `Frontend\FaqController@index` | Inertia `Pages/Faq` |
| 13 | GET | `/lien-he` | `contact` | `Frontend\ContactController@show` | Inertia `Pages/Contact` |
| 14 | POST | `/lien-he` | `contact.store` | `@store` | redirect + flash |
| 15 | GET | `/tim-kiem` | `search` | `Frontend\SearchController@index` | Inertia `Search/Index` |
| 16 | GET | `/trang-tinh/{slug}` | `pages.show` | `Frontend\PageController@show` | Inertia `Pages/Show` |
| 17 | POST | `/tour/{tour}/review` | `tours.review.store` | `Frontend\ReviewController@store` | redirect + flash (auth) |
| 18 | GET | `/sitemap.xml` | `sitemap` | `SitemapController@index` | XML |
| 19 | GET | `/dang-nhap` | `login` | `Auth\LoginController@showLoginForm` | Inertia `Auth/Login` |
| 20 | POST | `/dang-nhap` | `login.attempt` | `@login` | redirect theo role |
| 21 | POST | `/dang-xuat` | `logout` | `@logout` | redirect login |

> Không có route: đặt tour, thanh toán, giỏ hàng, tài khoản khách, đăng ký, quên mật khẩu.

---

## 4. TRANG ↔ BLADE ↔ REACT

| Trang | Blade tham chiếu | Dòng | Partial đi kèm | React cần |
|---|---|---:|---|---|
| Trang chủ | `home.blade.php` | 22 | `partials/home/*` (8 file, 643 dòng) | `Home.jsx` (viết lại) |
| Danh sách tour | `tour/index.blade.php` | 79 | `tour/partials/filter` (156), `tour/components/*` | `Tour/Index` |
| Chi tiết tour | `tour/show.blade.php` | 324 | gallery 29, itinerary 76, destinations 60, related 33, reviews 126 | `Tour/Show` |
| Danh sách điểm đến | `destination/index.blade.php` | 69 | `destination/partials/filter` 116 | `Destination/Index` |
| Chi tiết điểm đến | `destination/show.blade.php` | 219 | children 51, tours 15 | `Destination/Show` |
| Danh sách bài viết | `blog/index.blade.php` | 73 | `blog/partials/filter` 23, sidebar 70 | `Blog/Index` |
| Bài viết theo danh mục | `blog/partials/category.blade.php` | 130 | sidebar, related | `Blog/Category` |
| Bài viết theo tag | `blog/partials/tag.blade.php` | 133 | sidebar, related | `Blog/Tag` |
| Chi tiết bài viết | `blog/show.blade.php` | 162 | hero 94, content 7, tags 18, share 44, author 40, sidebar 70, related 33 | `Blog/Show` |
| Giới thiệu | `pages/about.blade.php` | 124 | — | `Pages/About` |
| Liên hệ | `pages/contact.blade.php` | 141 | — | `Pages/Contact` |
| FAQ | `pages/faq.blade.php` | 244 | — | `Pages/Faq` |
| Trang tĩnh | `pages/show.blade.php` | 61 | — | `Pages/Show` |
| Tìm kiếm | `search/index.blade.php` | 269 | — | `Search/Index` |
| 404 | `errors/404.blade.php` | 89 | dùng `layouts/app` | `Errors/NotFound` |
| Layout | `layouts/app.blade.php` + head/header/footer | 444 | menu-item-desktop/mobile | `AppLayout` (đã có) |

**Tổng Blade frontend:** 69 file / 6.194 dòng (gồm 17 component `<x-...>`: tour-card 142, post-card 160, destination-card 174, hero-banner 51, breadcrumb 42, pagination 58, input/select/textarea/checkbox/button/alert/badge…).

---

## 5. CONTROLLER & DỮ LIỆU BACKEND

| Trang | Props hiện tại | Dữ liệu còn thiếu / rủi ro |
|---|---|---|
| Home | `sliders`, `featuredTours` (6), `featuredDestinations` (6), `latestPosts` (3) | Không có `gallery` (Blade lấy từ setting/slider), không có nội dung `intro` từ settings |
| Tour/Index | `tours` (paginate 12), `categories`, `difficulties` | Đủ cho filter q/category/difficulty/price_min/max/sort |
| Tour/Show | `tour` (category, destinations, itineraries), `relatedTours` (3) | ⚠ **Thiếu `reviews` + average rating** (Blade lazy-load `$tour->reviews`) |
| Destination/Index | `destinations` (paginate 12), `regions` | Đủ (q, region, featured, sort) |
| Destination/Show | `destination` (parent, children published, tours published), `counts` | Đủ; bản Blade chỉ hiện lat/lng dạng số, **không có bản đồ nhúng** |
| Blog/Index | `posts` (paginate 9), `categories`, `recentPosts` (5), `popularTags` (15) | Đủ (q, category, sort) |
| Blog/Category | `category`, `posts`, `categories`, `recentPosts`, `popularTags` | Đủ |
| Blog/Tag | `tag`, `posts`, `categories`, `recentPosts`, `popularTags` | Đủ |
| Blog/Show | `post` (category, author, tags), `relatedPosts` (3), `recentPosts`, `popularTags` | Đủ |
| Pages/About | `page` (theo `about_page_slug`) | ⚠ **Thiếu 9 setting `about_*`** (hero, sứ mệnh, tầm nhìn, giá trị) |
| Pages/Contact | — | Dùng settings contact (đã share) |
| Pages/Faq | `faqs` (groupBy category) | Đủ |
| Search/Index | `keyword`, `type`, `results` (tour 8, destination 8, post 8, page 5), `total` | Đủ; **không có phân trang** |
| Pages/Show | `page` | Đủ |
| Sitemap | XML: trang chủ, 6 route tĩnh, tour, destination, post, page | Domain lấy từ `APP_URL` |

**Shared props Inertia hiện có** (`HandleInertiaRequests`): `auth.user`, `flash`, `settings` (site_name, description, logo, favicon, contact_*, social, seo_*), `theme` (primary, secondary, headings H1–H6), `navigation` (menu main/footer 3 cấp), `app` (name, url).

**Không có trong shared props:** `hero_image_blog|tour|destination|about|contact|faq`, `about_hero_title`, `about_story_title`, `about_mission_*`, `about_vision_*`, `about_values_*`, `google_analytics_id`.

---

## 6. CHỨC NĂNG TỪNG TRANG (theo Blade tham chiếu)

### 6.1. Trang chủ
Hero slider (Swiper, ảnh từ `sliders` + `hero_image_*`, overlay, CTA) → Intro (giới thiệu + 2 số liệu) → Featured tours (6 card) → Featured destinations (6 card) → Latest posts (3 card) → Why choose (4 giá trị) → Gallery (ảnh từ slider/media) → Contact CTA.
**Hiện tại React:** hero 1 ảnh + 3 khối card + 4 giá trị + CTA. **Thiếu:** slider nhiều ảnh, intro settings, gallery, số liệu.

### 6.2. Danh sách tour
Hero + filter sidebar (từ khóa `q`, danh mục, độ khó, khoảng giá, sort) + grid card (ảnh, tên, độ khó, thời lượng, giá, địa điểm) + phân trang 12.
Backend hỗ trợ: `q`, `category`, `difficulty`, `price_min`, `price_max`, `sort` (newest/price_asc/price_desc/popular).

### 6.3. Chi tiết tour
Hero ảnh lớn + breadcrumb → tổng quan → thông số (ngày/đêm, km, độ cao, độ khó, giá, khởi hành/kết thúc, thời điểm) → lịch trình theo ngày → gallery (GLightbox) → điểm đến liên quan → đánh giá (sao + danh sách + form nếu đăng nhập) → tour liên quan → JSON-LD `Product` + `BreadcrumbList`.

### 6.4. Danh sách điểm đến
Hero + filter (`q`, `region`, `featured`) + grid card (ảnh, tên, tỉnh/vùng, số tour) + phân trang 12.

### 6.5. Chi tiết điểm đến
Hero + tổng quan → vị trí (vĩ/kinh độ dạng số) → điểm đến con → tour tại điểm đến → JSON-LD `TouristDestination` + `BreadcrumbList`.

### 6.6. Bài viết
- Index: hero + filter danh mục + grid 9 bài + sidebar (danh mục, bài mới, tag phổ biến) + phân trang.
- Category/Tag: tiêu đề + danh sách + sidebar.
- Show: hero ảnh → nội dung HTML → tag → chia sẻ (Facebook/X/copy link) → author box → sidebar → bài liên quan → JSON-LD `Article` + `Breadcrumb`.

### 6.7. Giới thiệu
Hero (setting `hero_image_about`) + nội dung page `gioi-thieu` + 4 khối setting (sứ mệnh, tầm nhìn, giá trị) + CTA.

### 6.8. Liên hệ
Thông tin (địa chỉ, hotline, email, giờ mở cửa, social, bản đồ nhúng/placeholder) + form 5 trường (name, email, phone, subject, message) + validate + flash.

### 6.9. FAQ
Hero + nhóm câu hỏi theo `category` + accordion (Alpine) + CTA liên hệ + JSON-LD `FAQPage`.

### 6.10. Tìm kiếm
Ô nhập `q` + tab loại (`all|tour|destination|post|page`) + 4 nhóm kết quả + trạng thái rỗng + tổng số.

### 6.11. Trang tĩnh & 404
`Pages/Show`: hero + nội dung HTML từ page.
`404`: layout public + thông điệp + CTA.

---

## 7. KIỂM TRA CHỨC NĂNG THEO YÊU CẦU

| Chức năng | Trạng thái backend | Đang có UI? | Ghi chú |
|---|---|---|---|
| Tìm kiếm & lọc tour | ✅ `q`, category, difficulty, price_min/max, sort | ❌ | Chỉ có trong Blade chết |
| Lọc điểm đến | ✅ `q`, region, featured, sort | ❌ | |
| Tìm kiếm tổng hợp | ✅ 4 loại, giới hạn 8/8/8/5 | ❌ | Không phân trang |
| **Đặt tour** | ❌ **Không tồn tại** | ❌ | Không bảng/model/route/controller. Chỉ có form liên hệ |
| Form liên hệ | ✅ validate 5 trường, lưu DB, gửi mail queue, flash | ❌ | `Pages/Contact` chưa có form |
| Đánh giá tour | ✅ yêu cầu đăng nhập, 1 review/tour, mặc định `pending`, admin duyệt | ❌ | 0 review approved; controller chưa load reviews |
| SEO / metadata | ✅ `Seo.jsx` + `SeoFields` (Admin) | 🟡 | Chỉ `Home` dùng `<Seo>`; 13 trang kia không có meta |
| JSON-LD | 🟡 có trong Blade chết (Product, Article, FAQ, Breadcrumb, TouristDestination) | ❌ | Chưa có trong React |
| Sitemap | ✅ `/sitemap.xml` động, không cache | ✅ | Không phụ thuộc React |
| robots.txt | ✅ | ✅ | Domain hardcode `rungadventure.com` |
| 404 | ✅ | ❌ Blade | Sẽ thành 500 nếu thiếu `app.js` trong manifest (đã thêm) |
| Menu động | ✅ 3 cấp | ✅ | Header/footer React đã dùng |
| Theme từ Settings | ✅ | ✅ | `ThemeStyles` inject CSS variables |
| Đa ngôn ngữ | ❌ | ❌ | Chỉ tiếng Việt |

---

## 8. COMPONENT / LAYOUT / CSS / JS / ẢNH ĐANG DÙNG

### 8.1. React (đã có)
`Layouts/AppLayout` + `Partials/Header`, `Footer`; `Components/Seo`, `ThemeStyles`; `Components/UI/*` (14) dùng chung với Admin; `lib/routes|format|constants`; `hooks/useFlash`.

### 8.2. Blade (tham chiếu, đã chết)
17 component `<x-...>`: `tour-card`, `post-card`, `destination-card`, `hero-banner`, `breadcrumb`, `breadcrumb-schema`, `json-ld`, `pagination`, `alert`, `badge`, `button`, `input`, `select`, `textarea`, `checkbox`, `tinymce`, `modal` (rỗng).

### 8.3. JS library
Alpine (menu mobile, accordion FAQ, review form), Swiper (hero slider), AOS (scroll animation), GLightbox (gallery) — **chỉ dùng trong Blade**, React không dùng.

### 8.4. Ảnh
14 file / 7,65 MB trong `storage/app/public/media` (categories, pages, settings, sliders, tours/gallery). Chưa có biến thể kích thước; thumbnail 400px do `ImageService` tạo cho ảnh mới.

---

## 9. TRANG CẦN CHUYỂN ĐỔI

| Ưu tiên | Trang | Component |
|---|---|---|
| P1 | Trang chủ (nâng cấp) | `Home` |
| P1 | Danh sách tour | `Tour/Index` |
| P1 | Chi tiết tour | `Tour/Show` |
| P2 | Danh sách điểm đến | `Destination/Index` |
| P2 | Chi tiết điểm đến | `Destination/Show` |
| P2 | Danh sách bài viết | `Blog/Index` |
| P2 | Chi tiết bài viết | `Blog/Show` |
| P3 | Bài viết theo danh mục | `Blog/Category` |
| P3 | Bài viết theo tag | `Blog/Tag` |
| P3 | Giới thiệu | `Pages/About` |
| P3 | Liên hệ | `Pages/Contact` |
| P3 | FAQ | `Pages/Faq` |
| P3 | Tìm kiếm | `Search/Index` |
| P3 | Trang tĩnh | `Pages/Show` |
| P0 | Trang lỗi | `Errors/NotFound` (+ 403/419/500) |

**Tổng: 15 component** (13 thiếu + Home viết lại + Errors).

---

## 10. CHỨC NĂNG CÓ NGUY CƠ MẤT KHI CHUYỂN ĐỔI

| # | Chức năng | Nguồn hiện tại | Cách giữ |
|---|---|---|---|
| 1 | Hero slider nhiều ảnh | Blade `partials/home/hero` + Swiper | Component `HeroSlider` (CSS scroll-snap hoặc Swiper đã có) |
| 2 | Gallery ảnh + lightbox | GLightbox | Component `Gallery` (dialog + keyboard) |
| 3 | Lịch trình tour theo ngày | `tour/partials/itinerary` | `Itinerary` accordion |
| 4 | Đánh giá + form sao | `tour/partials/reviews` (Alpine) | `ReviewList` + `ReviewForm` (React state) — **cần backend load reviews** |
| 5 | Filter tour nhiều tiêu chí | `tour/partials/filter` | `TourFilter` giữ query string |
| 6 | Sidebar blog (danh mục/bài mới/tag) | `blog/partials/sidebar` | `BlogSidebar` |
| 7 | Chia sẻ bài viết (FB/X/copy) | `blog/partials/share` | `ShareBar` (copy dùng Clipboard API) |
| 8 | Author box | `blog/partials/author` | `AuthorBox` |
| 9 | Breadcrumb + JSON-LD | component + 5 view | `Breadcrumb` + `lib/schema.js` |
| 10 | FAQ accordion | Alpine trong `pages/faq` | `Accordion` React (không cần Alpine) |
| 11 | Hero image theo trang | `HeroImageComposer` + settings | Đưa `hero_image_*` vào shared props |
| 12 | Nội dung About từ settings | 9 key `about_*` | Đưa vào shared props hoặc props trang |
| 13 | Form liên hệ (validate + flash) | `pages/contact` + `StoreContactRequest` | `ContactForm` dùng `useForm` |
| 14 | Tìm kiếm 4 nhóm + tab | `search/index` | `SearchTabs` + 4 result group |
| 15 | Phân trang giữ filter | `components/pagination` | `Pagination` (đã có) + `preserveScroll` |
| 16 | Scroll animation | AOS | Hook `useReveal` (IntersectionObserver), tôn trọng `prefers-reduced-motion` |
| 17 | Menu mobile 3 cấp | Alpine header | Đã có trong `Header.jsx` |

---

## 11. KIẾN TRÚC REACT + INERTIA ĐỀ XUẤT

### 11.1. Cấu trúc thư mục

```
resources/js/
├── Layouts/
│   ├── AppLayout.jsx          (đã có — mở rộng)
│   └── Partials/Header|Footer  (đã có)
├── Components/
│   ├── Common/                Breadcrumb, PageHero, SectionHeading, Prose,
│   │                          RatingStars, ShareBar, Gallery, Accordion
│   ├── Tour/                  TourCard, TourFilter, Itinerary, TourSpecs, TourReviews
│   ├── Destination/           DestinationCard, DestinationFilter, ChildGrid
│   ├── Blog/                  PostCard, BlogSidebar, AuthorBox, PostMeta
│   ├── Home/                  HeroSlider, Intro, FeaturedTours, FeaturedDestinations,
│   │                          LatestPosts, ValuesBand, GalleryStrip, ContactCta
│   ├── Forms/                 ContactForm, ReviewForm, SearchBox
│   └── UI/                    (đã có: Button, Input, …)
├── Pages/
│   ├── Home.jsx
│   ├── Tour/{Index,Show}.jsx
│   ├── Destination/{Index,Show}.jsx
│   ├── Blog/{Index,Category,Tag,Show}.jsx
│   ├── Pages/{About,Contact,Faq,Show}.jsx
│   ├── Search/Index.jsx
│   └── Errors/{NotFound,Forbidden,ServerError,Expired}.jsx
├── lib/  routes.js, format.js, constants.js, schema.js (JSON-LD builders), media.js
└── hooks/ useReveal.js, useFlash.js
```

### 11.2. Data contract bổ sung cần thống nhất

| Prop | Trang | Cách làm |
|---|---|---|
| `tour.reviews` + `tour.average_rating` | Tour/Show | Eager load `reviews` (đã duyệt) trong `Frontend\TourController@show` |
| `settings.hero_images.{page}` | Tour/Destination/Blog index, About, Contact, FAQ | Thêm 6 key vào `HandleInertiaRequests::settings()` |
| `settings.about` | Pages/About | Thêm object 9 key `about_*` vào shared props (hoặc prop riêng của trang) |
| `navigation.main` | tất cả | Đã có |

> Các thay đổi này là **bổ sung props**, không đổi route/nghiệp vụ.

### 11.3. SEO

- `Seo.jsx` đã có: title, description, canonical, OG, Twitter, JSON-LD.
- Bổ sung `lib/schema.js`: `tourSchema()`, `articleSchema()`, `faqSchema()`, `destinationSchema()`, `breadcrumbSchema()`.
- Mỗi trang public bắt buộc dùng `<Seo>`; Admin dùng `noindex`.
- `sitemap.xml` giữ nguyên (đã động), cân nhắc cache 1 giờ.

### 11.4. Hình ảnh & hiệu năng

- Dùng `mediaUrl()` (đã có) + `loading="lazy"` + `width/height` cố định để tránh CLS.
- Ảnh hero `fetchpriority="high"`, preload.
- **Đề xuất (cần duyệt):** mở rộng `ImageService` sinh 3 kích thước (480/960/1600) để dùng `srcset` — thay đổi backend nhỏ.
- Không thêm thư viện UI/animation mới; dùng CSS + IntersectionObserver.

### 11.5. Animation

- Fade/slide nhẹ khi section vào viewport (`useReveal`).
- Hover card: translateY + shadow.
- Tôn trọng `prefers-reduced-motion`.
- Không dùng AOS để tránh 2 hệ animation song song.

### 11.6. Responsive

Breakpoint: 480 / 768 / 1024 / 1280. Grid tour 3→2→1 cột; sidebar filter chuyển drawer dưới 1024; hero giảm chiều cao, giữ tỷ lệ ảnh.

---

## 12. KẾ HOẠCH TRIỂN KHAI THEO NHÓM

| Nhóm | Nội dung | Trang | Ước lượng |
|---|---|---|---:|
| **P0** | Nền tảng: design tokens công khai, mở rộng `AppLayout`, `PageHero`, `SectionHeading`, `Breadcrumb`, `Prose`, `RatingStars`, `Gallery`, `Accordion`, `useReveal`, `lib/schema.js`, trang lỗi Inertia | 4 component lỗi | 3–4 ngày |
| **P1** | Trang chủ đầy đủ (slider, intro, 3 khối nội dung, giá trị, gallery, CTA) | 1 | 2–3 |
| **P2** | Tour: danh sách + filter + chi tiết + lịch trình + gallery + đánh giá + related | 2 | 5–6 |
| **P3** | Điểm đến: danh sách + filter + chi tiết + con + tour | 2 | 2–3 |
| **P4** | Blog: index + category + tag + show (sidebar, share, author, related) | 4 | 3–4 |
| **P5** | Trang tĩnh: About, Contact (form), FAQ (accordion), Search (tab + 4 nhóm), Pages/Show | 5 | 3–4 |
| **P6** | SEO/JSON-LD toàn site, responsive QA, Lighthouse, test server-side cho 13 route, dọn CSS | — | 2–3 |
| | **Tổng** | **15** | **20–27 ngày** |

**Thứ tự bắt buộc:** P0 → P1 → P2 → P3 → P4 → P5 → P6. Mỗi nhóm: build + smoke test + commit riêng.

---

## 13. VẤN ĐỀ KỸ THUẬT CẦN XỬ LÝ TRƯỚC

| # | Vấn đề | Mức | Cách xử lý đề xuất |
|---|---|---|---|
| T1 | `public/hot` đang tồn tại → Laravel trỏ asset sang Vite dev server; nếu dev server tắt, CSS/JS public hỏng | **Cao** | Xóa `public/hot` khi build production; thêm bước kiểm tra vào checklist deploy |
| T2 | Tour chưa load `reviews` → không thể dựng khối đánh giá bằng dữ liệu thật | **Cao** | Bổ sung eager load trong `TourController@show` (1 dòng + accessor) |
| T3 | `hero_image_*`, `about_*` không có trong shared props | **Cao** | Bổ sung vào `HandleInertiaRequests` |
| T4 | 404 vẫn là Blade, phụ thuộc `layouts/app` | Trung bình | Chuyển sang `Errors/NotFound` + xử lý `Handler` |
| T5 | CSS: khối minify 12 KB trong `app.css`, `pages/*.css` phục vụ Blade chết, `react-ui.css` dành cho Admin | Trung bình | Tách `public.css` riêng cho frontend; giữ `react-ui.css` cho Admin |
| T6 | `favicon.ico` 0 byte | Thấp | Thay bằng favicon thật hoặc dùng setting `site_favicon` |
| T7 | `robots.txt` + sitemap dùng domain hardcode `rungadventure.com` | Thấp | Lấy từ `APP_URL` |
| T8 | Ảnh 7,65 MB, chưa có biến thể responsive | Trung bình | `srcset` + sinh size (cần duyệt backend) |
| T9 | Không có test frontend; test hiện chỉ kiểm tra component name/props | Trung bình | Thêm test `assertInertia` cho 13 route trong P6 |
| T10 | File rác `resources/js/.write-probe` (6 byte, **đang được git track**) | Thấp | Xóa ở đợt dọn dẹp (cần bạn đồng ý vì quy định không xóa file) |
| T11 | `shop`/booking không tồn tại | — | Quyết định có xây module đặt tour hay không (cần migration + model) |
| T12 | `sliders` chỉ còn 1 bản ghi active | Thấp | Nếu thiết kế cần 3 slide, admin cần thêm dữ liệu (không phải lỗi code) |

---

## 14. RỦI RO

| Rủi ro | Ảnh hưởng | Giảm thiểu |
|---|---|---|
| Dựng lại thiếu chức năng so với Blade | Mất trải nghiệm | Checklist mục 10 + đối chiếu từng section |
| Bổ sung props làm thay đổi payload Admin | Hồi quy Admin | Chỉ thêm key mới, không đổi key cũ; test Admin hiện có |
| Ảnh lớn làm chậm trang | SEO/UX | lazy + srcset + hero preload |
| CSS cũ chồng chéo | Giao diện lệch | Tách `public.css`, đặt namespace class |
| `public/hot` lọt lên production | Trắng giao diện | Checklist deploy + xóa trước build |

---

## 15. PHỤ LỤC — SỐ LIỆU

**Dữ liệu public hiện tại**

| Bảng | Published / tổng |
|---|---|
| tours | 8 / 9 |
| destinations | 13 |
| posts | 9 |
| categories | 11 |
| tags | 12 |
| faqs | 10 |
| pages | 6 |
| sliders | 1 (4 đã xóa mềm) |
| reviews | 0 approved |

**Lệnh đã dùng để kiểm kê**

```powershell
php artisan route:list --json
Get-ChildItem resources\views -Recurse -File | Measure-Object
Get-ChildItem resources\views -Recurse -File | ForEach-Object { (Get-Content $_.FullName).Count }
Select-String -Path app\*,routes\* -Pattern 'booking|dat-tour|order|checkout|payment'
Select-String -Path resources\views -Pattern 'Inertia::render'  # qua controller
```

**Kết luận:** frontend public cần được xây mới **15 component**, kèm 3 bổ sung props nhỏ ở backend. Khối lượng ước tính **20–27 ngày**, chia 7 nhóm P0–P6. Không có chức năng đặt tour để bảo toàn; các chức năng còn lại đều có backend sẵn sàng.
