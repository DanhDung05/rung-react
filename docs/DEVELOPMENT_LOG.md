# RUNG ADVENTURE — NHẬT KÝ PHÁT TRIỂN

> Ghi lại **mỗi giai đoạn**: file đã tạo, file đã sửa, chức năng hoàn thành, chức năng chưa hoàn thành, lỗi còn tồn tại, kết quả kiểm thử.
> Mới nhất ở trên cùng.

---

## [2026-11-10] FRONTEND P1.3 — TỔNG THỂ TRANG CHỦ (HOÀN THÀNH)

### Đã làm

- Thêm section Lựa chọn hành trình (nhóm tour thật) và band ảnh toàn chiều rộng + trích dẫn.
- Giá trị: chương full-width; Giới thiệu: text 2 cột, bỏ ảnh.
- Gallery chỉ dùng ảnh thật, tự solo/duo.
- Nhịp nền xen kẽ, giữ nguyên Hero/Header.

### Kiểm thử

| Mục | Kết quả |
|---|---|
| `npm run build` | exit 0 |
| Toàn bộ suite | 93 passed / 582 assertions |
| Screenshot 1440 | đã đối chiếu: không còn khoảng trống/khung ảnh lỗi |
| 390/768 | Chrome headless ép min-width ~500px → cần kiểm tra thiết bị thật |
| Hero/Header | không sửa |
| Backend/Admin | không sửa |

### Dữ liệu còn thiếu (cần xác nhận trước khi bổ sung backend)

- Điểm đến: 0/13 ảnh → chưa làm được section ảnh toàn khung + tên overlay.
- Bài viết: 0/9 ảnh → journal hiện dùng typography.
- Tour: 7/9 thiếu ảnh; 1 file `.webp` thực chất là JPEG (sai MIME) → Chrome không decode.
- "Trải nghiệm thiên nhiên": chưa có bảng/field mô tả hoạt động (trekking, cắm trại, kayak...) → đề xuất bảng `experiences` (title, slug, description, image, icon, sort_order, is_active).

---

## [2026-11-10] FRONTEND P1.2 — VISUAL REFINEMENT (HOÀN THÀNH)

### Đã làm

- Redesign 7 section (About, Tour, Destination, Values, Gallery, Journal, CTA) + tinh chỉnh typography/spacing.
- Destination chuyển từ mosaic ảnh sang **danh mục typographic** vì 12/13 điểm đến không có ảnh.
- Gallery preload, loại ảnh lỗi; About ẩn toàn bộ figure khi ảnh lỗi.
- Cover tour có pattern + mô tả + CTA khi thiếu ảnh.
- Fix hero title màu xanh đậm, `em` thiếu tương phản trên nền rừng.

### Phát hiện dữ liệu ảnh (không sửa DB)

| Nguồn | Có ảnh | Ghi chú |
|---|---:|---|
| 13 điểm đến | 0 | 1 path trỏ file không tồn tại |
| 9 tour | 2 | 1 file `.webp` thực chất là JPEG → server gửi MIME `image/webp`, Chrome không decode |
| 9 bài viết | 0 | |
| slider | 1 | hợp lệ |
| `hero_image_about` | 1 | hợp lệ |

### Kiểm thử

| Mục | Kết quả |
|---|---|
| `npm run build` | exit 0 |
| Toàn bộ suite | 93 passed / 582 assertions |
| Screenshot 1440 | đã chụp và đối chiếu |
| Screenshot 390 | Chrome headless ép chiều rộng tối thiểu ~500px nên ảnh 390 bị cắt — cần kiểm tra trên thiết bị thật |
| Backend/Admin | không sửa |

### Ghi chú

- Chưa chuyển P2.
- Đề xuất: chuẩn hoá lại file ảnh (đúng định dạng/MIME) và upload ảnh cho tour/điểm đến/bài viết.

---

## [2026-11-10] FRONTEND P1.1 — REDESIGN SECTION TRANG CHỦ (HOÀN THÀNH)

### Đã làm

- 7 section đổi **bố cục**, không chỉ đổi màu: About (media + editorial), Tour (feature + list đánh số), Destination (mosaic), Values (manifesto 2 cột), Gallery (collage), Journal (bài lớn + list), CTA (full-bleed).
- `Gallery` thêm `layout="collage"` với pattern ô lớn/dọc/ngang.
- `public.css` +857 dòng (layout, responsive 1100/900/680).
- **Không sửa** `HeroSlider.jsx` và `Header.jsx` (kiểm tra bằng git).

### Kiểm thử

| Bài kiểm tra | Kết quả |
|---|---|
| `npm run build` | exit 0 — Home 16,8 KB; CSS 150,8 KB chứa đủ class mới |
| Toàn bộ suite | 93 passed / 582 assertions |
| `GET /` | 200, component `Home`, asset `/build/assets/`, không `:5173` |
| CSS | 408 `{` / 408 `}` — cân bằng |
| Phạm vi | chỉ 3 file: `Home.jsx`, `public.css`, `Gallery.jsx` |

### Ghi chú

- `public/hot` không xuất hiện lại trong lần build này.
- Chưa chuyển P2.

---

## [2026-11-10] FRONTEND P1 — REDESIGN HOME / HEADER / FOOTER (HOÀN THÀNH)

### Đã làm

- `Header`: fixed, trong suốt trên hero → nền cream khi cuộn; dropdown; drawer mobile; nút tìm kiếm.
- `Footer`: 4 cột + social + contact + CTA, bottom bar.
- `Home`: hero slider thật (`sliders`), intro editorial dùng `settings.about`, tour nổi bật (1 lớn + 4 nhỏ), điểm đến nền rừng, 4 giá trị, gallery lightbox (ảnh thật từ tour/điểm đến/bài viết/slider), 3 bài viết, CTA.
- Component mới: `HeroSlider`, `TourCard`, `DestinationCard`, `PostCard`.
- `public.css` +927 dòng; `Gallery` hỗ trợ layout strip; `AppLayout` prop `overlayHeader`; `GenericPage`/`ErrorPage` dùng header nền sáng.

### Kiểm thử

| Bài kiểm tra | Kết quả |
|---|---|
| `npm run build` | exit 0 (Home chunk 15,1 KB) |
| Toàn bộ suite | 93 passed / 582 assertions |
| HTTP `/` | 200, đúng component `Home`, có sliders/featuredTours/latestPosts/settings.about |
| HTTP 404 | 404 + `Errors/NotFound` |
| Asset | `/build/assets/`, không còn `:5173` |
| Backend/Admin | Không file nào bị sửa |

### Ghi chú

- `public/hot` xuất hiện lại (Vite không chạy) — đã xóa lần 2 trước khi commit.
- Chưa chuyển P2.

---

## [2026-11-10] FRONTEND P0 — NỀN TẢNG GIAO DIỆN PUBLIC (HOÀN THÀNH)

### Bối cảnh

Bắt đầu triển khai kế hoạch redesign frontend (P0–P6). P0 chỉ làm nền tảng: design system, component dùng chung, trang lỗi và 3 bổ sung dữ liệu backend đã được duyệt. **Chưa dựng trang nội dung nào** (thuộc P1–P5).

### Đã hoàn thành trước khi gián đoạn quota

- `resources/css/public.css` — design system public hoàn chỉnh (970 dòng).
- `TourController@show` — eager-load `reviews` + `user`.
- `HandleInertiaRequests` — thêm `settings.hero_images` và `settings.about`.
- Xóa `public/hot` (Vite đã dừng, xác nhận trước khi xóa).

### Tiếp tục hoàn thành sau gián đoạn

- 9 component nền trong `Components/Public/`.
- `hooks/useReveal.js`, `lib/schema.js`.
- 4 trang lỗi `Pages/Errors/*`.
- `Handler::render` — trang lỗi Inertia cho public, không đổi Admin/API.
- `AppLayout` — skip-link + `.rp-app`; `app.css` import `public.css`.
- Test `PublicFoundationTest` (4 test).

### File đã sửa

| File | Thay đổi |
|---|---|
| `app/Exceptions/Handler.php` | +57 dòng render lỗi Inertia cho public |
| `app/Http/Controllers/Frontend/TourController.php` | +4 dòng eager-load reviews |
| `app/Http/Middleware/HandleInertiaRequests.php` | +38 dòng hero_images/about |
| `resources/css/app.css` | +1 dòng import public.css |
| `resources/js/Layouts/AppLayout.jsx` | skip-link + wrapper |

### File đã tạo

`resources/css/public.css`; `Components/Public/{PageHero,SectionHeading,Breadcrumb,Prose,RatingStars,Gallery,Accordion,Reveal,ErrorPage}.jsx`; `Pages/Errors/{NotFound,Forbidden,ServerError,Expired}.jsx`; `hooks/useReveal.js`; `lib/schema.js`; `tests/Feature/Frontend/PublicFoundationTest.php`.

### Kiểm thử

| Bài kiểm tra | Kết quả |
|---|---|
| `npm run build` | **exit 0** (14.62s) |
| `PublicFoundationTest` | 4 passed / 48 assertions |
| Toàn bộ suite | **93 passed / 582 assertions** |
| HTTP smoke | `/` 200 · `/tour` 200 · 404 đúng + component `Errors/NotFound` · không còn asset `:5173` |
| Admin | Không file Admin nào bị sửa; test Admin vẫn pass |

### Lỗi còn tồn tại

- MySQL cục bộ tự dừng sau thời gian dài (đã khởi động lại) — vấn đề môi trường, không phải code.
- 404/403 giờ render bằng Inertia; nếu Inertia lỗi trong lúc render, Handler fallback về response gốc.

---

## [2026-11-10] HOTFIX — DASHBOARD TRẮNG (HOÀN THÀNH)

### Triệu chứng

`/admin/dashboard` trắng trang, console báo:
```
Uncaught TypeError: routes.admin.menus.items is not a function at Dashboard.jsx
```

### Nguyên nhân gốc

`resources/js/lib/routes.js` khai báo `menus: resource('/admin/menu')`. Hàm `resource()` chỉ sinh 9 method CRUD chuẩn, **không có `items`**. Trong khi đó `routes/admin.php` có thêm 2 route bổ sung:
- `GET admin/menu/{menu}/items` → `admin.menus.items`
- `PUT admin/menu/{menu}/items` → `admin.menus.items.save`

Hai route này trước đây được khai báo rời thành `routes.admin.menuItems` / `routes.admin.menuItemsSave`, nên `routes.admin.menus.items` là `undefined`. Gọi nó trong `Dashboard.jsx:257` ném TypeError → React unmount toàn trang.

Ảnh hưởng thực tế: **3 nơi** cùng gọi `routes.admin.menus.items` — `Dashboard.jsx`, `Menus/Index.jsx`, `Menus/Show.jsx` (2 trang sau cũng sẽ trắng khi mở).

### Cách sửa

| File | Thay đổi |
|---|---|
| `resources/js/lib/routes.js` | `menus` = `{ ...resource('/admin/menu'), items, itemsSave }`; bỏ alias trùng `menuItems`/`menuItemsSave` |
| `resources/js/Pages/Admin/Menus/Items.jsx` | `routes.admin.menuItemsSave` → `routes.admin.menus.itemsSave` |

Không sửa `Dashboard.jsx`, `Menus/Index.jsx`, `Menus/Show.jsx` — chúng đã gọi đúng tên, chỉ thiếu định nghĩa.

### Kiểm chứng

| Kiểm tra | Kết quả |
|---|---|
| Node: `typeof routes.admin.menus.items` | `function`, trả `/admin/menu/5/items` |
| Node: quét toàn bộ `routes.*(` trong `resources/js` | **76 call site — 0 lỗi** |
| `npm run build` | exit 0, bundle có `items: e => \`/admin/menu/${e}/items\`` |
| `DashboardTest` + `AdminPagesExistTest` | **8 passed / 75 assertions** |
| Route backend | Không đổi (3 route Settings/Menu giữ nguyên) |
| DB / giao diện | Không đổi |

### Bài học / phòng ngừa

Route helper dạng `resource()` không tự có các route bổ sung. Khi backend thêm route ngoài CRUD chuẩn, phải bổ sung vào đúng object module. Có thể thêm bước kiểm tra tĩnh này vào CI (script Node đã dùng để audit 76 call site).

---

## [2026-11-10] TOÀN BỘ ADMIN SANG REACT (HOÀN THÀNH)

### Mục tiêu

Chuyển **toàn bộ 59 trang quản trị** sang React + Inertia, dùng backend Laravel hiện có; không trang nào còn rơi về `GenericPage`.

### Kết quả

| Chỉ số | Trước | Sau |
|---|---:|---:|
| Trang Admin có React component | 1 | **59** |
| Trang Admin dùng `GenericPage` | 58 | **0** |
| Test Admin | 0 | **50** |
| Tổng test toàn dự án | 28 | **89** (534 assertion) |
| Build | ✅ | ✅ exit 0 (112 entry manifest) |

### Triển khai theo nhóm

| Nhóm | Nội dung | Trang | Commit |
|---|---|---:|---|
| A | Dashboard đủ 11 prop (biểu đồ 7 ngày, thùng rác, feed, phân bố độ khó) | 1 | `96c5770` |
| B1 | Tour + toolkit (DataTable, FilterBar, ImageField, RichTextEditor, useConfirm) | 5 | `f93e921` |
| B2 | Bài viết + Tag | 10 | `2711f3f` |
| B3 | Điểm đến + Danh mục | 10 | `9b622f2` |
| B4 | Trang tĩnh + FAQ + Slider | 15 | `24eb048` |
| C | Liên hệ + Đánh giá | 4 | `16daff6` |
| D | Media + Menu (kèm Items editor) | 8 | `51cddcf` |
| E | Người dùng + Cài đặt (render động 45 field) | 6 | `827e65d` |
| F | Rà soát + test chống hồi quy | — | *(commit này)* |

### File đã tạo (chính)

- **Toolkit (8)**: `Components/Data/{DataTable,FilterBar}.jsx`, `Components/Form/{ImageField,SeoFields,StatusField,SlugField,RichTextEditor}.jsx`, `hooks/useConfirm.jsx`.
- **Trang Admin (59 component)** trong `resources/js/Pages/Admin/<Module>/`.
- **Test (9 file)**: `Admin/DashboardTest`, `TourManagementTest`, `ContentManagementTest`, `TaxonomyManagementTest`, `MiscContentTest`, `EngagementManagementTest`, `MediaMenuManagementTest`, `UserPagesTest`, `AdminPagesExistTest`.
- `scripts/copy-tinymce.mjs` — self-host TinyMCE.

### File đã sửa

- `package.json` — thêm `vendor:tinymce`, gắn vào `dev`/`build`.
- `.gitignore` — bỏ qua `public/vendor`.
- `resources/css/react-ui.css` — bổ sung CSS cho bảng, form, chi tiết, media grid, menu tree, settings, dashboard.
- `resources/js/Pages/Admin/Dashboard.jsx` — viết lại.
- `docs/*`, `CHANGELOG.md`.

### Quyết định kỹ thuật

1. **TinyMCE self-host** trong `public/vendor/tinymce`: bundle TinyMCE qua Vite tạo file >1MB khiến esbuild lỗi `Access is denied` trên Windows. Copy asset + load bằng script tag ổn định hơn, không phình JS bundle.
2. **Không tạo module giả**: "Đơn đặt tour" không có backend → không triển khai.
3. **Media chưa có upload**: hiển thị notice, không tạo nút giả.
4. **Phân quyền ở backend**: editor bị 403 với Users/Settings; UI chỉ ẩn menu.
5. **Không xóa Blade**, không sửa Public UI, không đổi dependency.

### Khoảng trống cần phê duyệt (chưa tự mở rộng)

| # | Vấn đề | Đề xuất |
|---|---|---|
| 1 | Không có module **Đơn đặt tour** | Cần migration + model + controller + route mới |
| 2 | Media Library **thiếu endpoint upload** | Thêm `MediaController@store` + 1 route (thay đổi nhỏ) |
| 3 | Menu Items cấp 3 có thể sai parent | Sửa `MenuController@saveItems` |
| 4 | Post chặn xóa cứng khi còn tag | Giữ nguyên, đã ghi chú trong UI |

### Kết quả kiểm thử

| Bài kiểm tra | Kết quả |
|---|---|
| `php artisan test` (toàn bộ) | **89 passed / 534 assertions** |
| `AdminPagesExistTest` | 59/59 component tồn tại, 0 dùng GenericPage |
| `npm run build` | exit 0, 112 entry |
| HTTP smoke test | `/` 200 · `/dang-nhap` 200 · `/tour` 200 · `/sitemap.xml` 200 · 404 đúng · `/admin/dashboard` → redirect login |
| DB thật | Không thay đổi (chỉ đọc/ghi qua test DB riêng) |

---

## [2026-11-10] ĐỢT BỔ SUNG — PHÂN QUYỀN SETTINGS (HOÀN THÀNH)

### Vấn đề

Sau GĐ2.0, menu Settings đã ẩn với editor nhưng **chỉ ở giao diện**. Editor vẫn truy cập trực tiếp `/admin/cau-hinh` và `/admin/cau-hinh/{group}` để xem/sửa cấu hình website, vì:
- Middleware `admin` cho cả `admin` + `editor`.
- `SettingController` không kiểm tra quyền.
- `UpdateSettingRequest::authorize()` trả `true`.

### Rà soát

| Hạng mục | Kết quả |
|---|---|
| Route Settings | 3 route: `admin.settings.index` (GET), `admin.settings.edit` (GET), `admin.settings.update` (PUT) |
| Controller | `Admin\SettingController` — index/edit/update; **không có** action nào khác |
| Nơi khác ghi settings | Chỉ `SettingController@update` (grep `Setting::set|updateOrCreate|create` toàn `app/`) |
| FormRequest | `UpdateSettingRequest` |
| Tổng route toàn hệ thống | 152 (không đổi) |

### Cách vá

1. **`app/Policies/SettingPolicy.php`** (mới): `viewAny`, `view`, `update` → chỉ `isAdmin()`.
2. **`SettingController`**: gọi `$this->authorize(...)` ở cả 3 action, đặt **trước** mọi xử lý khác để không lộ thông tin nhóm cấu hình có tồn tại hay không.
3. **`UpdateSettingRequest::authorize()`**: `return (bool) $this->user()?->isAdmin();`

### Quyền sau khi vá

| Hành động | admin | editor | khách |
|---|:---:|:---:|:---:|
| Xem `/admin/cau-hinh` | ✅ 200 | ❌ 403 | redirect login |
| Xem `/admin/cau-hinh/{group}` | ✅ 200 | ❌ 403 | redirect login |
| Cập nhật `PUT /admin/cau-hinh/{group}` | ✅ 302 | ❌ 403 | redirect login |
| Nhóm không tồn tại | 404 | 403 (không lộ) | redirect login |
| Thấy menu "Cài đặt" | ✅ | ❌ (ẩn từ GĐ2.0) | — |

### File đã tạo

- `app/Policies/SettingPolicy.php`
- `tests/Feature/Security/SettingManagementTest.php`

### File đã sửa

- `app/Http/Controllers/Admin/SettingController.php` (thêm 3 `authorize`)
- `app/Http/Requests/Admin/UpdateSettingRequest.php` (`authorize` = admin)

### Kết quả kiểm thử

| Test | Kết quả |
|---|---|
| `SettingManagementTest` | **9 passed / 44 assertions** |
| Toàn bộ suite | **28 passed / 140 assertions** |
| `npm run build` | **exit 0**, 7.58s |
| Route Settings | 3 route, không đổi |
| DB thật | `site_name = "Rừng Adventure"`, `contact_phone`, `theme_primary_color = #a80554` — **không đổi** |

### Ghi chú

- Không sửa route, không đổi URL, không ảnh hưởng menu/theme/shared props (InertiaFoundationTest vẫn PASS).
- Backup `_backups-rung-react\20261110-115656` **không bị chạm**.

---

## [2026-11-10] GIAI ĐOẠN 2.0 — TẦNG NỀN REACT (HOÀN THÀNH)

### Bối cảnh

Sau khi GĐ0 ổn định môi trường và vá lỗ hổng leo thang đặc quyền, xây tầng nền cho toàn bộ giao diện React: layout, design system, shared props, SEO, tiện ích. **Chưa xây trang CRUD nào** (thuộc 2A/2B/2C/2D).

### Việc đã làm

| # | Việc | Kết quả |
|---|---|---|
| 1 | Shared props từ Laravel → React | `settings`, `theme`, `navigation`, `app`, `flash`, `auth.user` (rút gọn) |
| 2 | Menu động vào React | `NavigationService` + cache 1h + tự xóa cache khi admin sửa menu |
| 3 | Theme động vào React | `ThemeStyles` inject CSS variables từ settings |
| 4 | 3 layout dùng chung | AppLayout, AdminLayout, AuthLayout + 4 partial |
| 5 | Design system | 14 component UI (Button, Input, Select, Textarea, Checkbox, Badge, Card, Modal, Pagination, EmptyState, Spinner, FlashMessage, FormField, ConfirmDialog) |
| 6 | SEO component | title/description/canonical/OG/Twitter/JSON-LD |
| 7 | Tiện ích | `routes.js` (toàn bộ URL), `format.js`, `constants.js`, `useFlash` |
| 8 | Lazy-load page | `import.meta.glob` không eager → tách chunk theo trang |
| 9 | Cảnh báo thiếu page | `console.warn` ở dev thay vì im lặng rơi vào GenericPage |
| 10 | Chuyển 4 trang hiện có sang layout | Home, GenericPage, Admin/Dashboard, Auth/Login |
| 11 | Sửa lỗi 404 → 500 | Thêm `resources/js/app.js` vào Vite input |
| 12 | Tách CSS React | `resources/css/react-ui.css` (bỏ import `admin.css` rỗng) |

### File đã tạo

**Backend (2)**
- `app/Services/NavigationService.php`
- `app/Policies/UserPolicy.php` (từ đợt vá bảo mật)

**React — lib/hooks (4)**
- `resources/js/lib/routes.js`, `resources/js/lib/format.js`, `resources/js/lib/constants.js`
- `resources/js/hooks/useFlash.js`

**React — Components (16)**
- `resources/js/Components/Seo.jsx`, `resources/js/Components/ThemeStyles.jsx`
- `resources/js/Components/UI/`: `Button`, `FormField`, `Input`, `Textarea`, `Select`, `Checkbox`, `Badge`, `Card`, `Modal`, `Pagination`, `EmptyState`, `Spinner`, `FlashMessage`, `ConfirmDialog` (14 file)

**React — Layouts (7)**
- `resources/js/Layouts/AppLayout.jsx`, `AdminLayout.jsx`, `AuthLayout.jsx`
- `resources/js/Layouts/Partials/Header.jsx`, `Footer.jsx`, `AdminSidebar.jsx`, `AdminTopbar.jsx`

**CSS (1)**
- `resources/css/react-ui.css`

**Test (2)**
- `tests/Feature/InertiaFoundationTest.php`
- `tests/Feature/Security/UserManagementTest.php`

### File đã sửa

| File | Thay đổi |
|---|---|
| `app/Http/Middleware/HandleInertiaRequests.php` | Viết lại `share()`: settings/theme/navigation/app + user rút gọn, có try/catch |
| `app/Helpers/CacheHelper.php` | Thêm `clearMenuCaches()`, gọi trong `clearAll()` |
| `app/Http/Controllers/Admin/MenuController.php` | Xóa cache menu sau store/update/destroy/restore/forceDestroy/saveItems |
| `app/Http/Controllers/Admin/UserController.php` | Thêm `authorize()` + chặn tự đổi role/tự vô hiệu hóa |
| `app/Http/Requests/Admin/StoreUserRequest.php` | `authorize()` = admin |
| `app/Http/Requests/Admin/UpdateUserRequest.php` | `authorize()` = admin |
| `resources/js/app.jsx` | Bỏ eager glob, lazy import, fallback động, cảnh báo dev |
| `resources/js/Pages/Home.jsx` | Dùng AppLayout + Seo + settings; dùng slider thật |
| `resources/js/Pages/GenericPage.jsx` | Dùng 3 layout thay vì tự dựng header/footer |
| `resources/js/Pages/Admin/Dashboard.jsx` | Dùng AdminLayout + Card + Badge + EmptyState |
| `resources/js/Pages/Auth/Login.jsx` | Dùng AuthLayout + Button + Checkbox |
| `resources/css/app.css` | Bỏ import `pages/admin.css` (rỗng), thêm `react-ui.css` |
| `vite.config.js` | Thêm `resources/js/app.js` vào input (fix 404) |
| `phpunit.xml` | Trỏ DB test `rungadventure_test` |

### Chức năng đã hoàn thành

- Header/footer/nav đọc **dữ liệu thật** từ `menus` + `settings` (không còn hardcode).
- Màu/font từ Settings áp dụng được lên React qua CSS variables.
- Admin sidebar có 15 module, ẩn Người dùng/Cài đặt với editor.
- Admin topbar có user menu + đăng xuất.
- Mọi trang có flash message thống nhất.
- SEO đầy đủ cho trang dùng `Seo`.
- Bundle tách theo trang (Home 6.9KB, Dashboard 2.4KB, Login 3.1KB riêng).

### Chức năng chưa hoàn thành

- 71 page component (58 admin + 13 frontend) — thuộc 2A/2B/2C/2D.
- Media Library upload — 2C.
- Trang lỗi Inertia (404/403/419/500) — 2E (404 Blade hiện đã chạy đúng, không còn 500).
- Rich text editor component — 2B (khi làm form Tour/Post).
- Upload ảnh component — 2B/2C.

### Lỗi còn tồn tại (mới phát hiện trong 2.0)

| ID | Lỗi | Mức | Ghi chú |
|---|---|---|---|
| N1 | `Setting::get()` gọi nhiều lần trong 1 request (theme 14 lần) | Thấp | Đã có cache `settings.all` nên chỉ 1 query; có thể gộp sau |
| N2 | `Page 404` vẫn là Blade, phụ thuộc `layouts.app` + MenuComposer | Thấp | Sẽ chuyển Inertia ở 2E |
| N3 | Editor vẫn vào được Settings (chưa khóa) | **Trung bình** | Thuộc phạm vi GĐ3 (Policy cho Setting) |
| N4 | `route:cache` vẫn lỗi do closure upload TinyMCE | Thấp | Xử lý ở 2B/2C khi chuyển sang controller |

### Kết quả kiểm thử

| Test | Kết quả |
|---|---|
| `php artisan test` | **19 passed, 96 assertions** |
| UserManagementTest (bảo mật) | 11 passed |
| InertiaFoundationTest (tầng nền) | 6 passed |
| `php -l` 10 file PHP đã sửa/tạo | 0 lỗi |
| `npm run build` | **exit 0**, 2442 module, 9.35s |
| Smoke test HTTP | `/` 200, `/dang-nhap` 200, `/tour` 200, `/sitemap.xml` 200, `/khong-ton-tai` **404** (trước là 500) |
| Shared props | `settings.site_name = "Rừng Adventure"`, `navigation.main` có 6 item thật từ DB |
| DB thật sau kiểm thử | users 3, tours 9, posts 9, settings 45, contacts 23, menu_items 14 — **không đổi** |

### Git

| Commit | Nội dung |
|---|---|
| `61d0db5` | `chore(gd0)`: ổn định môi trường |
| `88d0e59` | `fix(security)`: chặn editor leo thang đặc quyền |
| *(sắp tạo)* | `feat(gd2.0)`: tầng nền React |

### Bước tiếp theo

Chờ phê duyệt để thực hiện **GĐ2A** (Admin shell + Dashboard nâng cao), sau đó 2B.

---

## [2026-11-10] GIAI ĐOẠN 0 — ỔN ĐỊNH MÔI TRƯỜNG (HOÀN THÀNH)

### Việc đã làm

| # | Việc | Chi tiết |
|---|---|---|
| 1 | Backup toàn bộ | DB dump + `public/storage` + `storage/app/public` + `resources/js` + `resources/css` + controllers/requests/middleware/routes → `C:\laragon\www\_backups-rung-react\20261110-115656` (124 file) |
| 2 | Khởi động lại MySQL | Máy mất nguồn làm MySQL tắt; khởi động lại bằng `mysqld --defaults-file`, không đổi dữ liệu |
| 3 | `public/storage` → symlink | Xác minh 15 file / SHA256 khớp tuyệt đối **trước khi xóa**, sau đó `storage:link` → Junction |
| 4 | `.env` | Copy từ `.env.example` (file chưa tồn tại), sửa `APP_NAME`, `APP_URL`, `MAIL_MAILER=log` |
| 5 | `APP_KEY` | `php artisan key:generate` |
| 6 | `storage/logs` | Tạo thư mục + `.gitignore` |
| 7 | Xóa cache cũ | `config:clear`, `cache:clear`, `view:clear` (cache cũ chứa PII + hash bcrypt) |
| 8 | JS dependencies | `npm install` → 186 package, `package-lock.json` được tạo |
| 9 | Build | `npm run build` → `public/build/manifest.json` + assets |
| 10 | Git | `git init` + commit baseline 372 file |
| 11 | DB test | Tạo `rungadventure_test` (DB riêng, không đụng DB thật) |

### Kiểm chứng

- `public/storage` là **Junction**, 15 file hiển thị qua link.
- `/` → 200, `/dang-nhap` → 200, `/admin/dashboard` → redirect `/dang-nhap` (middleware hoạt động).
- `php artisan about` chạy đúng: Laravel 10.50.4, PHP 8.1.10, APP_NAME "Rung Adventure".

### Ghi chú

- Không chạy `migrate:fresh`, `db:wipe`, hay import lại `rungadventure.sql`.
- Backup **chưa xóa** — chờ chủ dự án xác nhận website ổn định.

---

## [2026-11-10] VÁ BẢO MẬT — LEO THANG ĐẶC QUYỀN (HOÀN THÀNH)

### Lỗ hổng

Tài khoản `editor` có thể tự đổi `role` thành `admin` qua `/admin/tai-khoan/{id}/edit` vì:
- `EnsureUserIsAdmin` cho cả admin và editor vào khu quản trị.
- `UpdateUserRequest` cho phép `role` và `authorize()` trả `true`.
- `UserController@update` không kiểm tra người thực hiện.

### Cách vá

1. `UserPolicy`: `viewAny`/`create`/`update` chỉ admin; `toggleActive` chỉ admin và không tự tác động.
2. `UserController`: gọi `authorize()` ở index/create/store/show/edit/update/toggleActive.
3. Chặn tự đổi `role` và tự vô hiệu hóa tài khoản của chính mình (kể cả admin).
4. `StoreUserRequest`/`UpdateUserRequest`: `authorize()` = `user()->isAdmin()`.

### Kiểm thử

`tests/Feature/Security/UserManagementTest.php` — **11 test, 20 assertion, PASS**:
- Khách chưa đăng nhập → redirect login.
- Editor: không xem danh sách, không mở form tạo, **không tự nâng quyền**, không tạo admin, không sửa người khác, không bật/tắt tài khoản.
- Admin: xem danh sách, nâng quyền editor, **không tự đổi role**, **không tự vô hiệu hóa**.

**Test hồi quy quan trọng nhất:** `test_editor_khong_the_tu_nang_quyen_thanh_admin` — trước khi vá sẽ fail.

---

## [2026-11-10] GIAI ĐOẠN 1 — AUDIT SOURCE (HOÀN THÀNH)

### Bối cảnh

Bắt đầu dự án trên source có sẵn tại `C:\laragon\www\rung-react`. Chỉ thực hiện kiểm toán và lập kế hoạch; **không sửa source nghiệp vụ, không tạo migration, không xóa file**.

### Việc đã làm

| # | Việc | Kết quả |
|---|---|---|
| 1 | Khảo sát cấu trúc workspace | 9 thư mục Laravel chuẩn, không có `.git` |
| 2 | Đọc toàn bộ routes (web, admin, auth, api, channels, console) | 152 route, 0 trùng |
| 3 | Đọc 27 file controller | 26 controller nghiệp vụ |
| 4 | Đọc 25 FormRequest | Validation đầy đủ, `authorize()` toàn `true` |
| 5 | Đọc 16 model + 23 migration | DB khớp, thiếu `job_batches` |
| 6 | Đọc toàn bộ React (4 page) + Vite/Tailwind config | 71 component thiếu |
| 7 | Inventory 142 Blade view | 132 dead code |
| 8 | Kiểm tra auth/authorization | Phát hiện leo thang đặc quyền |
| 9 | Kiểm tra SEO, cache, media, testing | Ghi nhận khoảng trống |
| 10 | Đối chiếu `Inertia::render` ↔ file page | 74 yêu cầu / 4 tồn tại |
| 11 | Lint toàn bộ PHP | 165 file, 0 lỗi |
| 12 | Chạy `route:list`, `migrate:status`, đọc DB thật | Xác minh số liệu |
| 13 | Viết bộ tài liệu dự án | 7 file |

### File đã tạo

| File | Nội dung | Trạng thái |
|---|---|---|
| `docs/PROJECT_AUDIT.md` | Báo cáo kiểm toán đầy đủ (545 dòng) | ✅ |
| `docs/IMPLEMENTATION_PLAN.md` | Kế hoạch 6 giai đoạn, ước lượng, tiêu chí (674 dòng) | ✅ |
| `docs/FEATURES.md` | Danh mục chức năng theo 3 tầng (302 dòng) | ✅ |
| `docs/DATABASE.md` | 23 bảng, quan hệ, quy ước, backup (528 dòng) | ✅ |
| `docs/DEPLOYMENT.md` | Hướng dẫn triển khai, troubleshooting (409 dòng) | ✅ |
| `docs/DEVELOPMENT_LOG.md` | File này | ✅ |
| `CHANGELOG.md` | Lịch sử phiên bản | ✅ |

### File đã sửa

**Không có file source nghiệp vụ nào bị sửa.**

Thay đổi ngoài source (hạ tầng, có thể hoàn nguyên):

| Đối tượng | Hành động | Lý do | Hoàn nguyên |
|---|---|---|---|
| ACL thư mục `C:\laragon\www\rung-react` | Cấp quyền FullControl cho user hiện tại | Workspace bị chặn ghi (`SetNamedSecurityInfoW failed`) | Chạy script rollback do công cụ in ra |
| `vendor/` | `composer install --no-blocking` | Cần autoload để chạy `artisan` | Xóa `vendor/` |
| `composer.lock` | Được tạo bởi composer | Chưa từng có lock file | Xóa `composer.lock` |
| `bootstrap/cache/packages.php`, `services.php` | Laravel tự sinh khi chạy artisan | Cache manifest | `php artisan clear-compiled` |
| `routes-dump.json` | Tạo tạm để debug rồi **đã xóa** | File rác từ lệnh kiểm thử | Đã xóa |

> ⚠ `composer install` **chưa hoàn tất bước `package:discover`** vì `storage/logs` không tạo được. `vendor/autoload.php` đã có, nhưng cần chạy lại sau khi tạo `storage/logs`.

### Chức năng đã hoàn thành (trong phạm vi Giai đoạn 1)

- Bản đồ đầy đủ routes, controller, model, view, page.
- Ma trận đối chiếu Controller ↔ React ↔ Blade cho 74 điểm render.
- Xác định 71 component React còn thiếu (danh sách cụ thể từng file).
- Xác định 132/142 Blade là dead code + lý do.
- Xác định 11 vấn đề bảo mật (1 High, 4 Medium, 5 Low, 1 Info).
- Xác định 6 vấn đề dữ liệu/migration.
- Xác định 6 vấn đề hiệu năng/cache.
- Kế hoạch 6 giai đoạn với ước lượng và tiêu chí nghiệm thu.
- Quy ước kiến trúc thư mục đích cho React.

### Chức năng chưa hoàn thành

Toàn bộ Giai đoạn 2–6 (theo kế hoạch). Cụ thể:

- 71 React page component.
- Media Library upload.
- Layout dùng chung + design system.
- Phân quyền Policy/Gate.
- Version Management + `/cap-nhat`.
- Activity Log.
- Test tự động.

### Lỗi còn tồn tại (phát hiện, **chưa sửa** — đúng phạm vi Giai đoạn 1)

| ID | Lỗi | Mức | Nơi |
|---|---|---|---|
| B1 | Editor có thể tự nâng thành admin | **High** | `UpdateUserRequest.php:29`, `UserController.php:99-121` |
| B2 | `RouteServiceProvider::HOME='/home'` không tồn tại → 404 | Medium | `RouteServiceProvider.php:16` |
| B3 | 404 page gọi `@vite('resources/js/app.js')` không có trong input → 500 | Medium | `layouts/partials/head.blade.php:116` |
| B4 | `public/storage` là thư mục copy, không phải symlink | Medium | `public/storage` |
| B5 | Dashboard thiếu layout, bỏ phí 8/11 prop | Medium | `Admin/Dashboard.jsx` |
| B6 | `GenericPage` thay 71 trang thật | **Cao** | `app.jsx:9` |
| B7 | Menu/Settings/Theme backend không tới được React | Cao | `HandleInertiaRequests.php:17-28` |
| B8 | `PostController@forceDestroy` chặn xóa khi còn tag | Thấp | `PostController.php:226-234` |
| B9 | `TourController@forceDestroy` báo sai tên (dùng ID) | Thấp | `TourController.php:269` |
| B10 | Menu items cấp 3 có thể sai parent khi lưu | Thấp | `MenuController.php:148-153` |
| B11 | Cache `tour_related_*`, `post_related_*` không được xóa | Thấp | `CacheHelper.php` |
| B12 | `route:cache` sẽ lỗi do closure upload | Thấp | `routes/admin.php:340-356` |

### Kết quả kiểm thử

| Test | Lệnh | Kết quả |
|---|---|---|
| Cú pháp PHP toàn bộ | `php -l` trên `app,routes,config,database,tests` | **165 file — 0 lỗi** |
| Route table | `php artisan route:list --json` | **152 route**; 0 trùng tên; 0 trùng URI+method; 1 route không tên (`api/user`) |
| Migration | `php artisan migrate:status` | **23/23 Ran** (batch 1–5) |
| DB kết nối | MySQL 8.0.30, DB `rungadventure` | OK |
| Đếm dữ liệu | `SELECT COUNT(*)` 23 bảng | tours 9, posts 9, destinations 13, categories 11, pages 6, faqs 10, sliders 5, tags 12, menus 4, menu_items 14, itineraries 18, contacts 23, settings 45, media 2, reviews 0, users 3 |
| Đối chiếu component | grep `Inertia::render` | 74 yêu cầu / 4 file / **71 thiếu** |
| Build frontend | `npm install` | ⛔ chưa chạy (ngoài phạm vi GĐ1) |
| HTTP smoke test | — | ⛔ chưa chạy (thiếu `.env`, `public/build`) |
| PHPUnit | — | ⛔ chưa chạy (sẽ chạy GĐ6) |

### Sự cố môi trường gặp phải

1. **ACL workspace bị chặn ghi** — đã xử lý bằng skill `diagnose-windows-sandbox-acl` (cấp FullControl cho user, có backup + rollback).
2. **Composer 2.10 chặn Laravel 10** do advisory — đã dùng `--no-blocking`.
3. **`npm` bị ExecutionPolicy chặn** (`npm.ps1`) — phải dùng `npm.cmd`.
4. **Sandbox chạy ở Low Integrity** — chỉ ghi được thư mục gốc và `docs/`; không ghi được `storage/`, `bootstrap/cache/`, `public/`, `resources/`. Vì vậy `artisan` chỉ chạy được khi cấp quyền đầy đủ cho từng lệnh.
5. **Mất nguồn đột ngột** giữa phiên — đã kiểm tra lại sau khi khởi động: 5 file tài liệu còn nguyên (UTF-8, đúng số dòng, kết thúc đúng), `vendor/autoload.php` và `composer.lock` còn, `public/build` và `node_modules` vẫn chưa có. Không mất dữ liệu.

### Ghi chú sau sự cố mất nguồn (kiểm tra lại 2026-11-10)

| Hạng mục | Trạng thái sau khởi động lại |
|---|---|
| `docs/PROJECT_AUDIT.md` | ✅ 545 dòng, UTF-8 hợp lệ |
| `docs/IMPLEMENTATION_PLAN.md` | ✅ 674 dòng |
| `docs/FEATURES.md` | ✅ 302 dòng |
| `docs/DATABASE.md` | ✅ 528 dòng |
| `docs/DEPLOYMENT.md` | ✅ 409 dòng |
| `vendor/autoload.php` | ✅ tồn tại |
| `composer.lock` | ✅ tồn tại (318 KB) |
| `node_modules`, `public/build`, `.env` | ❌ vẫn chưa có |
| `storage/logs` | ❌ vẫn chưa có |
| `routes-dump.json` (rác) | 🗑 đã xóa |

### Bước tiếp theo

1. Chờ phê duyệt `docs/IMPLEMENTATION_PLAN.md`.
2. Thực hiện **Giai đoạn 0** (ổn định môi trường) — xem `IMPLEMENTATION_PLAN.md` §4.
3. Bắt đầu **Giai đoạn 2, đợt 2.0** (tầng nền React).

---

## MẪU GHI CHO CÁC GIAI ĐOẠN SAU

```markdown
## [YYYY-MM-DD] GIAI ĐOẠN X — <TÊN> (<TRẠNG THÁI>)

### Bối cảnh
### Việc đã làm
### File đã tạo
### File đã sửa
### Chức năng đã hoàn thành
### Chức năng chưa hoàn thành
### Lỗi còn tồn tại
### Kết quả kiểm thử
### Bước tiếp theo
```

---

## 2026-11-16 � Ki?m k� t�i li?u & Tr? gi�p Admin t? Markdown

- R� so�t Git �?n `3e7854a`; ph�t hi?n ADMIN_FEATURES/PROJECT_STATUS/ARCHITECTURE d?ng ? `9f03af2`, thi?u releases, carousel, dark mode, brand, dev script.
- T?o `docs/ADMIN_GUIDE.md`; c?p nh?t ADMIN_FEATURES, PROJECT_STATUS, ARCHITECTURE, DATABASE, README, DOCUMENTATION_RULES.
- Trang `/admin/huong-dan` chuy?n sang render Markdown t? `docs/` qua `config/admin_docs.php`; b? d�ng `config/admin_guide.php`.
- Ki?m th?: 6 test Guide (quy?n, ch?n t�i li?u, fallback, rewrite link, sanitize); to�n b? 126 pass.


## 2026-11-16 � v2.3.0: ��nh gi� kh�ch, t� v?n tour, ch?ng spam, realtime

- A: review kh�ch v?ng lai (migration nullable user_id + author_name/email, unique tour+email, StoreReviewRequest, route b? auth, UI 2 � h? t�n/email); email kh�ng l? public.
- B: RateLimiter contact/review/login, honeypot website, LoginController is_active + kh�a 5 l?n/ph�t.
- C: contacts th�m tour_id/people_count/preferred_date/type; TourInquiryController + modal; ContactNotifier d�ng chung mail/event; admin hi?n th? lead.
- D: private channel admin-notifications + AdminChannel authorize; payload t?i thi?u; Echo ch? b?t khi c� key; badge/toast Admin.
- Test: 149 passed; build OK; VERSION 2.3.0.
