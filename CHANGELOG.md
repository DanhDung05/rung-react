# Changelog

Mọi thay đổi đáng chú ý của dự án Rung Adventure được ghi tại đây.

Định dạng dựa trên [Keep a Changelog](https://keepachangelog.com/vi/1.1.0/),
phiên bản theo [Semantic Versioning](https://semver.org/lang/vi/).

> **Quy ước ghi:**
> - `Added` — chức năng mới
> - `Changed` — thay đổi hành vi
> - `Deprecated` — sắp bị loại bỏ
> - `Removed` — đã loại bỏ
> - `Fixed` — sửa lỗi
> - `Security` — vá bảo mật
>
> Mỗi mục ghi kèm module và file chính. Chi tiết kỹ thuật xem `docs/DEVELOPMENT_LOG.md`.

---

## [Unreleased]

### Added — Frontend P0: nền tảng giao diện public

- `resources/css/public.css` (~970 dòng): design system public, namespace `rp-*` — hero, section, card, filter, gallery, accordion, prose, CTA, reveal animation, responsive 1024/768/560.
- 9 component nền: `PageHero`, `SectionHeading`, `Breadcrumb`, `Prose`, `RatingStars`, `Gallery` (lightbox không phụ thuộc thư viện), `Accordion`, `Reveal`, `ErrorPage`.
- `hooks/useReveal.js` (IntersectionObserver, tôn trọng `prefers-reduced-motion`).
- `lib/schema.js`: JSON-LD cho Breadcrumb, TouristTrip, Article, FAQPage, TouristDestination.
- 4 trang lỗi Inertia: `Errors/NotFound`, `Forbidden`, `ServerError`, `Expired`.
- `AppLayout`: skip-link + wrapper `.rp-app`; bỏ `public/hot` cũ (Vite đã dừng).

### Added — Backend props (được duyệt)

- `TourController@show` eager-load `reviews` (đã duyệt) kèm `user`.
- Shared props `settings.hero_images` (6 ảnh) và `settings.about` (9 khoá).
- `Handler::render` trả trang lỗi Inertia cho public (404/403/419/500 khi tắt debug); giữ nguyên Admin/API.

### Added — Frontend P1: redesign Home / Header / Footer

- `Header`: trong suốt trên hero, chuyển nền cream khi cuộn, dropdown 2 cấp, drawer mobile toàn màn hình, nút tìm kiếm.
- `Footer`: 4 cột (thương hiệu + social, khám phá, liên hệ, CTA), bottom bar.
- `Home` thiết kế editorial: hero slider (autoplay, dots, arrows, reduced-motion), intro bất đối xứng, tour nổi bật (1 lớn + 4 nhỏ), điểm đến band nền rừng, 4 giá trị dạng số, gallery lightbox, 3 bài viết, CTA rộng.
- Component mới: `HeroSlider`, `TourCard`, `DestinationCard`, `PostCard`.
- `public.css` +927 dòng: header/footer/slider/editorial/responsive.
- Dùng dữ liệu thật: `sliders`, `featuredTours`, `featuredDestinations`, `latestPosts`, `settings.hero_images`, `settings.about`.

### Added — Frontend P1.1: redesign section nội dung trang chủ

- **Giới thiệu**: bố cục media + editorial copy 12 cột, ảnh dọc 4:4.6 với caption đè, trích dẫn lớn, chữ ký.
- **Tour nổi bật**: feature lớn toàn chiều cao (ảnh tràn + overlay) bên trái, 4 tour dạng danh sách đánh số bên phải (thay grid card).
- **Điểm đến**: mosaic bất đối xứng — 1 ảnh lớn dọc + 2 card ngang overlay.
- **Giá trị**: manifesto 2 cột (tuyên ngôn lớn + 4 giá trị dạng cột có đường kẻ, hover đổi màu viền).
- **Thư viện**: collage 4 cột với ô lớn 2×3, ô dọc, ô ngang (`layout="collage"`).
- **Bài viết**: journal 1 bài lớn + 2 dòng tiêu đề tối giản có đường kẻ.
- **CTA**: band full-bleed nền ảnh + overlay tối, tiêu đề serif, khối liên hệ bên phải.
- `public.css` +857 dòng; giữ nguyên HeroSlider và Header.

### Changed — Frontend P1.2: visual refinement trang chủ

- **Giới thiệu**: bố cục 2 cột media + editorial; tiêu đề lớn hơn, trích dẫn serif, chữ ký; ảnh chỉ render khi tải được.
- **Tour**: khối boxed split; cover có pattern khi thiếu ảnh, thêm mô tả + CTA; cột danh sách chia đều chiều cao, thumbnail chỉ hiện với ảnh thật.
- **Điểm đến**: chuyển sang **danh mục typographic** (số thứ tự, tên lớn, vùng, số tour) — loại bỏ hoàn toàn khối tối trống do thiếu ảnh.
- **Giá trị**: manifesto 2 cột, chữ tăng cỡ và tương phản.
- **Gallery**: preload và loại ảnh lỗi/MIME sai; layout duo/solo; không còn khung trống.
- **Bài viết**: journal 1 bài lớn + 2 dòng tiêu đề, không phụ thuộc ảnh.
- **CTA**: band full-bleed nền rừng, khối liên hệ bên phải.
- Thêm guard `overflow-x`, `min-width:0`, tăng cỡ chữ các nhãn nhỏ.

### Fixed — Frontend P1.2

- Hero title bị màu xanh đậm (kế thừa `h1` base) → trắng, có text-shadow.
- Chữ `em` trên nền rừng thiếu tương phản → vàng sáng `#e8c48d`.
- Ảnh lỗi để lại caption/khung trống → ẩn toàn bộ figure hoặc loại khỏi gallery.

### Planned — Giai đoạn 2 (React hoàn thiện)

- Tầng nền React: `AppLayout`, `AdminLayout`, `AuthLayout`, design system, shared props (settings/menu/theme).
- 71 page component còn thiếu (58 admin + 13 frontend).
- Media Library có upload, chọn nhiều, MediaPicker.
- Trang lỗi Inertia (404/403/419/500).
- Loại bỏ `GenericPage` fallback.

### Planned — Giai đoạn 3–6

- Phân quyền Policy/Gate; vá lỗi leo thang đặc quyền của `editor`.
- Dashboard nâng cao.
- Version Management, Changelog, What's New, `/cap-nhat`.
- Activity Log / Audit Log.
- Bộ test tự động + build production.

---

## [0.3.1] — 2026-11-10

**Sửa lỗi Dashboard trắng do gọi route helper không tồn tại.**

### Fixed

- `Uncaught TypeError: routes.admin.menus.items is not a function` tại `Dashboard.jsx`.
- **Nguyên nhân gốc:** `routes.admin.menus` được tạo bằng `resource('/admin/menu')` — helper này chỉ sinh 9 method CRUD chuẩn (`index/create/store/show/edit/update/destroy/trash/restore/forceDestroy`), **không có** `items`. Route `admin.menus.items` là route bổ sung trong `routes/admin.php`, trước đó được khai báo rời ở `routes.admin.menuItems`.
- **Cách sửa:** bổ sung `items` và `itemsSave` vào chính `routes.admin.menus` (khớp tên route Laravel), bỏ 2 alias trùng `menuItems`/`menuItemsSave`, cập nhật `Menus/Items.jsx` sang `menus.itemsSave`.
- Sửa luôn 2 chỗ cùng lỗi tiềm ẩn: `Menus/Index.jsx`, `Menus/Show.jsx` (dùng chung helper nên không phải sửa code).

### Verified

- Node kiểm tra **76 lời gọi route** trong toàn bộ `resources/js` → tất cả là hàm hợp lệ.
- `menus.items(5)` → `/admin/menu/5/items`; `menus.itemsSave(5)` → cùng URL (GET/PUT).
- `npm run build`: exit 0; bundle chứa đúng `items: e => \`/admin/menu/${e}/items\``.
- `DashboardTest` + `AdminPagesExistTest`: 8 passed (75 assertions).
- Không đổi URL, không đổi route backend, không đổi DB, không đổi giao diện.

---

## [0.3.0] — 2026-11-10

**Toàn bộ giao diện quản trị chuyển sang React + Inertia.**

### Added

- **59 trang Admin React** — không còn trang nào dùng `GenericPage`:
  - Dashboard (đủ 11 prop: thống kê, biểu đồ 7 ngày, thùng rác, top nội dung, feed, phân bố độ khó, menu).
  - Tour (5), Bài viết (5), Tag (5), Điểm đến (5), Danh mục (5), Trang tĩnh (5), FAQ (5), Slider (5).
  - Liên hệ (2), Đánh giá (2), Media (2), Menu (6, gồm trình sửa cấu trúc), Người dùng (4), Cài đặt (2).
- **Toolkit Admin**: `DataTable`, `FilterBar`, `ImageField`, `SeoFields`, `StatusField`, `SlugField`, `RichTextEditor` (TinyMCE self-host), `useConfirm`.
- **Cài đặt render động** theo `config/settings.php`: string/text/boolean/number/color/select/image/json.
- **Menu Items editor**: thêm/xóa/sắp xếp/phân cấp, lưu qua `PUT admin/menu/{id}/items`.
- **Test Admin**: 9 file, 50 test — bao gồm test chống hồi quy `AdminPagesExistTest` (quét controller, đảm bảo 59/59 component tồn tại và không dùng GenericPage).
- `scripts/copy-tinymce.mjs` — tự copy TinyMCE vào `public/vendor` trước khi dev/build.

### Changed

- `package.json`: `dev`/`build` chạy copy TinyMCE trước.
- `Admin/Dashboard.jsx` viết lại dùng toàn bộ dữ liệu backend.
- `resources/css/react-ui.css` mở rộng cho toàn bộ Admin.

### Fixed

- TinyMCE bundle gây lỗi esbuild `Access is denied` trên Windows → chuyển sang self-host.
- Đường dẫn import sai ở trang Settings (phát hiện qua build, đã sửa trước khi bàn giao).

### Security

- Editor bị **403** khi truy cập Người dùng và Cài đặt (kiểm tra ở policy + controller, không chỉ ẩn menu).

### Notes

- **Không** xóa 132 file Blade, không sửa giao diện Public, không đổi dependency, không đổi database.
- **Chưa triển khai**: module "Đơn đặt tour" (không có backend) và upload trong Media Library (thiếu endpoint) — đã báo cáo, chờ phê duyệt.
- Tổng test toàn dự án: **89 passed / 534 assertions**. Build exit 0.

---

## [0.2.1] — 2026-11-10

**Bản vá phân quyền module Cài đặt (Settings).**

### Security

- **Chặn editor truy cập Settings**: thêm `App\Policies\SettingPolicy`.
  - `viewAny`/`view`/`update` chỉ dành cho `admin`.
  - `SettingController` gọi `authorize()` ở **cả 3 action** (index, edit, update) — không chỉ ẩn menu.
  - `UpdateSettingRequest::authorize()` chỉ cho admin.
  - Editor truy cập trực tiếp URL nhận **HTTP 403**; nhóm không tồn tại cũng trả 403 (không lộ thông tin).

### Added

- `tests/Feature/Security/SettingManagementTest.php` — 9 test, 44 assertion.

### Changed

- Không đổi route, URL, controller logic nghiệp vụ hay giao diện.
- Không đổi dependency, không đổi database.

### Verified

- Toàn bộ suite: **28 passed / 140 assertions**.
- `npm run build`: exit 0.
- Settings thật trong DB không đổi.

---

## [0.2.0] — 2026-11-10

**Giai đoạn 0 + vá bảo mật + Giai đoạn 2.0 (tầng nền React).**

### Added

- **Shared props Inertia**: `settings` (7 group), `theme` (màu + font H1–H6), `navigation` (menu main/footer), `app`, `flash`, `auth.user` rút gọn — `HandleInertiaRequests`.
- **`App\Services\NavigationService`**: dựng cây menu 3 cấp, cache 1 giờ, tự xóa cache khi admin sửa menu.
- **3 layout dùng chung**: `AppLayout` (công khai), `AdminLayout` (sidebar + topbar), `AuthLayout`.
- **4 partial layout**: `Header`, `Footer`, `AdminSidebar`, `AdminTopbar`.
- **14 UI component**: Button, FormField, Input, Textarea, Select, Checkbox, Badge, Card, Modal, Pagination, EmptyState, Spinner, FlashMessage, ConfirmDialog.
- **`Seo` component**: title, description, canonical, Open Graph, Twitter card, JSON-LD.
- **`ThemeStyles`**: inject màu/font từ Settings vào CSS variables → theme trong admin có tác dụng thật.
- **Thư viện tiện ích**: `lib/routes.js` (toàn bộ URL), `lib/format.js`, `lib/constants.js`, `hooks/useFlash.js`.
- **`resources/css/react-ui.css`**: CSS cho design system, tách khỏi khối inline cũ.
- **Test**: `InertiaFoundationTest` (6 test), `UserManagementTest` (11 test).

### Changed

- `resources/js/app.jsx`: bỏ `eager: true` → lazy-load từng page, tách bundle theo trang; cảnh báo ở dev khi thiếu page component.
- `Home.jsx`, `GenericPage.jsx`, `Admin/Dashboard.jsx`, `Auth/Login.jsx` chuyển sang dùng layout chung; bỏ header/footer/sidebar tự dựng.
- `Home.jsx` dùng slider thật từ DB thay vì ảnh nền hardcode.
- `CacheHelper` bổ sung `clearMenuCaches()`.
- `phpunit.xml`: trỏ DB test riêng `rungadventure_test`.

### Fixed

- **404 trả 500** khi build production: thêm `resources/js/app.js` vào Vite input.
- **`GenericPage` fallback im lặng**: nay có cảnh báo rõ trong môi trường dev.
- **Menu / Settings / Theme không tới được React**: đã chia sẻ qua Inertia.
- **`admin.css` rỗng gây warning build**: bỏ import, thay bằng `react-ui.css`.
- **Môi trường không chạy được**: `.env` + `APP_KEY` + `storage/logs` + `npm install` + build.
- **`public/storage` là thư mục copy** → đã chuyển thành symlink sau khi xác minh SHA256.

### Security

- **Vá lỗ hổng High**: editor tự nâng quyền thành admin.
  - `App\Policies\UserPolicy` — chỉ admin quản lý người dùng.
  - `UserController` gọi `authorize()` ở 7 action.
  - Chặn tự đổi `role` và tự vô hiệu hóa tài khoản.
  - `StoreUserRequest`/`UpdateUserRequest` chỉ cho admin.
- **Xóa cache cũ** chứa hash bcrypt admin + email/PII.

### Notes

- **Không sửa nghiệp vụ backend** nào ngoài phần phân quyền người dùng.
- **Không chạy `migrate:fresh`**, không import lại `rungadventure.sql`.
- DB thật sau toàn bộ thay đổi: users 3, tours 9, posts 9, settings 45, contacts 23, menu_items 14 — **không đổi**.
- Backup tại `C:\laragon\www\_backups-rung-react\20261110-115656` — **chưa xóa**, chờ xác nhận website ổn định.
- 71 page component vẫn còn thiếu (thuộc 2A/2B/2C/2D).

---

## [0.1.0] — 2026-11-10

**Giai đoạn 1 — Audit Source.** Bản phát hành **chỉ gồm tài liệu**, không thay đổi source nghiệp vụ.

### Added

- `docs/PROJECT_AUDIT.md` — báo cáo kiểm toán toàn bộ source:
  - Hiện trạng stack, môi trường, kiến trúc.
  - 152 route (120 admin + 22 frontend), 0 trùng lặp.
  - Ma trận đối chiếu 74 `Inertia::render()` ↔ 4 page component ↔ 142 Blade view.
  - Danh sách 71 component React còn thiếu.
  - Phân tích Models, 23 migration, dữ liệu thật 23 bảng.
  - 11 vấn đề bảo mật, 6 vấn đề dữ liệu, 6 vấn đề hiệu năng.
  - Rủi ro tổng hợp và điều kiện bắt đầu Giai đoạn 2.
- `docs/IMPLEMENTATION_PLAN.md` — kế hoạch 6 giai đoạn:
  - Giai đoạn 0 (ổn định môi trường), Giai đoạn 2 (React), 3 (CMS nâng cao), 4 (Version), 5 (Activity Log), 6 (Test & Deploy).
  - Chi tiết từng đợt, file cần tạo, tiêu chí nghiệm thu, ước lượng 68–87 dev-day.
  - Kiến trúc thư mục đích, quy ước code, rủi ro và biện pháp.
- `docs/FEATURES.md` — danh mục 32 chức năng theo 3 tầng (backend / admin UI / frontend UI).
- `docs/DATABASE.md` — 23 bảng, quan hệ, ràng buộc, quy ước bảng mới, quy tắc backup.
- `docs/DEPLOYMENT.md` — yêu cầu hệ thống, cài đặt, build, web server, queue, checklist, troubleshooting, rollback.
- `docs/DEVELOPMENT_LOG.md` — nhật ký Giai đoạn 1.
- `CHANGELOG.md` — file này.

### Changed

- Không thay đổi source nghiệp vụ.

### Fixed

- Không sửa lỗi nào trong Giai đoạn 1 (theo phạm vi). 12 lỗi đã được ghi nhận trong `docs/DEVELOPMENT_LOG.md`.

### Security

- Ghi nhận **1 lỗ hổng High**: tài khoản `editor` có thể tự nâng thành `admin` qua `/admin/tai-khoan/{id}/edit`.
- Ghi nhận 4 vấn đề Medium: không throttle đăng nhập, broadcast channel công khai, cho upload SVG, `APP_DEBUG=true` trong `.env.example`.
- Ghi nhận 5 vấn đề Low: admin route thiếu `auth`, cache cũ chứa hash/PII, `HOME` sai, thiếu security headers, contact form không captcha.
- **Chưa vá** — thuộc Giai đoạn 3.

### Notes

- Source hiện **chưa chạy được** ở môi trường sạch: thiếu `.env`, `public/build`, `node_modules`; `public/storage` là thư mục copy thay vì symlink.
- DB thật đã migrate đủ 23/23. **Không** import `rungadventure.sql` (dump cũ, thiếu bảng `jobs` và `reviews`).
- **Không** chạy `migrate:fresh`.

---

## Mốc tham chiếu của source tại thời điểm audit

| Hạng mục | Giá trị |
|---|---|
| Laravel | 10.50.4 |
| Inertia Laravel | 2.0.28 |
| React / @inertiajs/react | 18.3.1 / 2.0 |
| Vite / Tailwind | 5 / 3.4 |
| PHP | 8.1.10 |
| Migration | 23/23 |
| Route | 152 |
| Controller / Model / FormRequest | 26 / 16 / 25 |
| Blade view | 142 (132 dead code) |
| React page | 4 (3 thật + GenericPage) |
| Test nghiệp vụ | 0 |
